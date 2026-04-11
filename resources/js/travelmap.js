import * as am5 from '@amcharts/amcharts5';
import * as am5map from '@amcharts/amcharts5/map';
import am5geodata_worldLow from '@amcharts/amcharts5-geodata/worldLow';
import am5themes_Animated from '@amcharts/amcharts5/themes/Animated';

// ── PHP-Daten aus JSON-Script-Tag lesen ──────────────────
const mapConfig = JSON.parse(document.getElementById('map-config')?.textContent || '{}');
const visitedCodes = mapConfig.visitedCodes || [];

// ── Colors ───────────────────────────────────────────────
const C_BG      = am5.color(0x06060d);
const C_LAND    = am5.color(0x111827);
const C_VISITED = am5.color(0x1a3a2a);
const C_BORDER  = am5.color(0x06060d);
const C_HOVER   = am5.color(0x1e3a5f);
const C_PIN     = am5.color(0xf97316);
const C_RING    = am5.color(0xf97316);
const C_LINE    = am5.color(0xf97316);
const C_PLANE   = am5.color(0xffffff);
const C_LABEL   = am5.color(0xf1f5f9);
const C_LABBG   = am5.color(0x06060d);

// ── State ────────────────────────────────────────────────
let currentGalleryId  = null;
let currentMappointId = null;
let photoPage         = 1;
let loadingPhotos     = false;
let noMorePhotos      = false;

// ── Map init ─────────────────────────────────────────────
am5.ready(function() {

    const root  = am5.Root.new("chartdiv");
    root.setThemes([am5themes_Animated.new(root)]);

    const chart = root.container.children.push(
        am5map.MapChart.new(root, {
            panX: "rotateX",
            projection: am5map.geoNaturalEarth1(),
            maxZoomLevel: 64,
        })
    );

    chart.chartContainer.set("background",
        am5.Rectangle.new(root, { fill: C_BG, fillOpacity: 1 })
    );

    // Countries
    const polySeries = chart.series.push(
        am5map.MapPolygonSeries.new(root, {
            geoJSON: am5geodata_worldLow,
            exclude: ["AQ"],
        })
    );
    polySeries.mapPolygons.template.setAll({
        tooltipText: "{name}",
        fill: C_LAND,
        stroke: C_BORDER,
        strokeWidth: 0.6,
        interactive: true,
    });
    polySeries.mapPolygons.template.states.create("hover", { fill: C_HOVER });
    polySeries.events.on("datavalidated", function() {
        polySeries.mapPolygons.each(function(polygon) {
            const id = polygon.dataItem.get("id");
            if (visitedCodes.indexOf(id) !== -1) {
                polygon.set("fill", C_VISITED);
            }
        });
    });

    // Lines
    const lineSeries = chart.series.push(am5map.MapLineSeries.new(root, {}));
    lineSeries.mapLines.template.setAll({
        stroke: C_LINE, strokeOpacity: 0.5, strokeWidth: 1.2, strokeDasharray: [5, 5],
    });

    const planeLineSeries = chart.series.push(am5map.MapLineSeries.new(root, {}));
    planeLineSeries.mapLines.template.setAll({ strokeOpacity: 0, strokeWidth: 0 });

    // Pins
    const pinSeries = chart.series.push(am5map.MapPointSeries.new(root, {}));
    pinSeries.bullets.push(function() {
        const cont = am5.Container.new(root, { cursorOverStyle: "pointer" });
        cont.children.push(am5.Circle.new(root, { radius: 13, fill: C_RING, fillOpacity: 0.15 }));
        cont.children.push(am5.Circle.new(root, {
            radius: 6, fill: C_PIN, stroke: am5.color(0xffffff), strokeWidth: 1.5,
        }));
        cont.children.push(am5.Label.new(root, {
            text: "{title}", fontSize: 11, fontWeight: "600", fill: C_LABEL,
            dy: -22, centerX: am5.p50, populateText: true,
            paddingLeft: 6, paddingRight: 6, paddingTop: 2, paddingBottom: 2,
            background: am5.RoundedRectangle.new(root, {
                fill: C_LABBG, fillOpacity: 0.85,
                cornerRadiusTL: 4, cornerRadiusTR: 4, cornerRadiusBL: 4, cornerRadiusBR: 4,
            }),
        }));
        cont.events.on("click", function(e) {
            const ctx = e.target.dataItem.dataContext;
            if (ctx && ctx.mappoint_id) openMappoint(ctx.mappoint_id, ctx.title);
        });
        return am5.Bullet.new(root, { sprite: cont });
    });

    // Plane
    const planeSeries = chart.series.push(am5map.MapPointSeries.new(root, {}));
    const planeShape  = am5.Graphics.new(root, {
        svgPath: "m2,106h28l24,30h72l-44,-133h35l80,132h98c21,0 21,34 0,34l-98,0 -80,134h-35l43,-133h-71l-24,30h-28l15,-47",
        scale: 0.045, centerY: am5.p50, centerX: am5.p50, fill: C_PLANE,
    });
    planeSeries.bullets.push(function() {
        const c = am5.Container.new(root, {});
        c.children.push(planeShape);
        return am5.Bullet.new(root, { sprite: c });
    });

    // Home button
    chart.children.push(am5.Button.new(root, {
        paddingTop: 8, paddingBottom: 8, paddingLeft: 8, paddingRight: 8,
        x: am5.percent(100), centerX: am5.percent(100), y: 12,
        background: am5.RoundedRectangle.new(root, {
            fill: am5.color(0x111827),
            cornerRadiusTL: 6, cornerRadiusTR: 6, cornerRadiusBL: 6, cornerRadiusBR: 6,
        }),
        icon: am5.Graphics.new(root, {
            svgPath: "M16,8 L14,8 L14,16 L10,16 L10,10 L6,10 L6,16 L2,16 L2,8 L0,8 L8,0 L16,8 Z",
            fill: am5.color(0xffffff),
        }),
    })).events.on("click", function() { chart.goHome(); });

    window._map = { root, chart, pinSeries, lineSeries, planeLineSeries, planeSeries, planeShape };
});

// ── Gallery laden ─────────────────────────────────────────
window.loadGallery = function(id, name, code) {
    currentGalleryId = id;
    document.querySelectorAll('.trip-item').forEach(function(el) {
        el.classList.toggle('active', el.dataset.id == id);
    });
    document.getElementById('pill-name').textContent = name;
    document.getElementById('trip-pill').style.display = 'flex';
    closePanel();

    fetch('/travel-blog/map-data/' + id)
        .then(r => r.json())
        .then(function(data) {
            const m = window._map;
            if (!m) return;

            m.pinSeries.data.clear();
            m.lineSeries.data.clear();
            m.planeLineSeries.data.clear();
            m.planeSeries.data.clear();

            const mps = data.mappoints;
            document.getElementById('pill-meta').textContent = mps.length + ' Orte';
            if (!mps.length) return;

            mps.forEach(function(mp) {
                m.pinSeries.data.push({
                    geometry: { type: "Point", coordinates: [mp.lon, mp.lat] },
                    title: mp.name,
                    mappoint_id: mp.id,
                });
            });

            if (mps.length > 1) {
                for (let i = 0; i < mps.length - 1; i++) {
                    m.lineSeries.data.push({
                        geometry: {
                            type: "LineString",
                            coordinates: [[mps[i].lon, mps[i].lat], [mps[i+1].lon, mps[i+1].lat]],
                        }
                    });
                }

                const allCoords = mps.map(mp => [mp.lon, mp.lat]);
                const routeLine = m.planeLineSeries.pushDataItem({
                    geometry: { type: "LineString", coordinates: allCoords }
                });
                const planeItem = m.planeSeries.pushDataItem({
                    lineDataItem: routeLine, positionOnLine: 0, autoRotate: true,
                });
                planeItem.animate({
                    key: "positionOnLine", to: 1, duration: 14000,
                    loops: Infinity, easing: am5.ease.yoyo(am5.ease.linear),
                });
                planeItem.on("positionOnLine", function(val) {
                    m.planeShape.set("rotation", val >= 0.99 ? 180 : 0);
                });
            }

            m.chart.zoomToGeoPoint({ longitude: mps[0].lon, latitude: mps[0].lat }, 2.5, true);
        });
};

// ── Photo Panel ───────────────────────────────────────────
window.openMappoint = function openMappoint(mappointId, name) {
    currentMappointId = mappointId;
    photoPage = 1;
    noMorePhotos = false;
    loadingPhotos = false;
    document.getElementById('panel-title').textContent = name;
    document.getElementById('photos-feed').innerHTML =
        '<div class="loader" id="photos-loader"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div>';
    document.getElementById('alternatives').style.display = 'none';
    document.getElementById('photo-panel').classList.add('open');
    loadPhotos();
};

window.closePanel = function closePanel() {
    document.getElementById('photo-panel').classList.remove('open');
};

function loadPhotos() {
    if (loadingPhotos || noMorePhotos) return;
    loadingPhotos = true;

    fetch('/travel-blog/mappoint-photos/' + currentMappointId + '?page=' + photoPage)
        .then(function(r) {
            if (!r.ok) throw new Error('HTTP Error: ' + r.status);
            return r.json();
        })
        .then(function(data) {
            loadingPhotos = false;
            const feed   = document.getElementById('photos-feed');
            const loader = document.getElementById('photos-loader');
            if (loader) loader.remove();

            if (!data.photos.length && photoPage === 1) {
                feed.innerHTML =
                    '<div style="text-align:center;padding:50px;color:#475569;font-size:13px">' +
                    '<div style="font-size:32px;margin-bottom:10px">📷</div>Noch keine Fotos hier.</div>';
                renderAlternatives(data.alternatives || []);
                return;
            }

            data.photos.forEach(renderPhotoCard);
            photoPage++;

            if (data.has_more) {
                const sentinel = document.createElement('div');
                sentinel.id = 'sentinel';
                sentinel.style.height = '1px';
                feed.appendChild(sentinel);
                const io = new IntersectionObserver(function(entries) {
                    if (entries[0].isIntersecting) { io.disconnect(); sentinel.remove(); loadPhotos(); }
                }, { root: feed });
                io.observe(sentinel);
            } else {
                noMorePhotos = true;
                renderAlternatives(data.alternatives || []);
            }
        })
        .catch(function(error) {
            loadingPhotos = false;
            const feed   = document.getElementById('photos-feed');
            const loader = document.getElementById('photos-loader');
            if (loader) loader.remove();
            feed.innerHTML =
                '<div style="text-align:center;padding:50px;color:#ef4444;font-size:13px">' +
                '<div style="font-size:32px;margin-bottom:10px">❌</div>Fehler beim Laden.<br>' +
                '<small style="color:#475569">' + error.message + '</small></div>';
        });
}

function renderPhotoCard(photo) {
    const feed = document.getElementById('photos-feed');
    let mediaHtml;

    if (photo.is_video) {
        mediaHtml = photo.content
            ? '<video controls style="width:100%"><source src="' + photo.content + '" type="video/mp4"></video>'
            : '<div style="width:100%;height:200px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#475569"><i class="fa-solid fa-video-slash text-4xl"></i></div>';
    } else {
        mediaHtml = photo.thumbnail
            ? '<img src="' + photo.thumbnail + '" alt="" loading="lazy">'
            : '<div style="width:100%;height:200px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#475569"><i class="fa-solid fa-image-slash text-4xl"></i></div>';
    }

    const dateHtml = photo.taken_at
        ? '<i class="fa-regular fa-calendar" style="color:#f97316"></i> ' + photo.taken_at : '';
    const gpsHtml = (photo.lat && photo.lon)
        ? ' &nbsp;<a href="https://www.google.com/maps?q=' + photo.lat + ',' + photo.lon + '" target="_blank">' +
          '<i class="fa-solid fa-location-dot" style="color:#f97316"></i> Maps</a>' : '';
    const textHtml = photo.text ? '<div class="photo-text">' + photo.text + '</div>' : '';
    const clickAttr = (photo.is_video || !photo.thumbnail) ? '' : ' onclick="showFullPic(' + photo.id + ')"';

    feed.insertAdjacentHTML('beforeend',
        '<div class="photo-card">' +
        '<div class="pic-wrap"' + clickAttr + '>' + mediaHtml + '</div>' +
        '<div class="photo-meta"><div class="photo-date">' + dateHtml + gpsHtml + '</div>' + textHtml + '</div>' +
        '<div class="photo-actions">' +
        '<span class="action-btn"><i class="fa-regular fa-heart"></i> Gefällt mir</span>' +
        '<span class="action-btn"><i class="fa-regular fa-comment"></i> Kommentar</span>' +
        '</div></div>'
    );
}

function renderAlternatives(alts) {
    if (!alts || !alts.length) return;
    const list = document.getElementById('alt-list');
    list.innerHTML = '';
    alts.forEach(function(g) {
        list.insertAdjacentHTML('beforeend',
            '<div class="alt-card" onclick="loadGallery(' + g.id + ', \'' +
            g.name.replace(/'/g, "\\'") + '\', \'' + g.code + '\'); closePanel();">' +
            '<div class="trip-icon" style="font-size:14px">✈</div>' +
            '<div><div class="alt-name">' + g.name + '</div><div class="alt-code">' + g.code + '</div></div>' +
            '</div>'
        );
    });
    document.getElementById('alternatives').style.display = 'block';
}

// ── Lightbox ──────────────────────────────────────────────
window.showFullPic = function(id) {
    fetch('/travel-blog/getBigPic/' + id)
        .then(r => r.json())
        .then(function(data) {
            document.getElementById('lightbox-img').src = data.data;
            document.getElementById('lightbox').classList.add('show');
        });
};

window.closeLightbox = function() {
    document.getElementById('lightbox').classList.remove('show');
    document.getElementById('lightbox-img').src = '';
};
