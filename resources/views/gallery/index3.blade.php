<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Travel Map ✈</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #06060d;
            color: #e2e8f0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            overflow: hidden;
            height: 100vh;
        }

        /* ─── Layout shell ──────────────────────────── */
        #app {
            display: flex;
            height: 100vh;
        }

        /* ─── Left Sidebar ──────────────────────────── */
        #sidebar {
            width: 270px;
            flex-shrink: 0;
            background: rgba(8, 8, 18, 0.97);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            z-index: 20;
        }

        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-logo h1 {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-logo p {
            font-size: 11px;
            color: #475569;
            margin-top: 3px;
        }

        .trip-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            scrollbar-width: thin;
            scrollbar-color: rgba(249, 115, 22, 0.2) transparent;
        }

        .trip-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            margin-bottom: 4px;
            border: 1px solid transparent;
            transition: all 0.15s;
        }

        .trip-item:hover {
            background: rgba(249, 115, 22, 0.07);
            border-color: rgba(249, 115, 22, 0.15);
        }

        .trip-item.active {
            background: rgba(249, 115, 22, 0.12);
            border-color: rgba(249, 115, 22, 0.35);
        }

        .trip-icon {
            width: 34px;
            height: 34px;
            background: rgba(249, 115, 22, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .trip-item.active .trip-icon {
            background: rgba(249, 115, 22, 0.2);
        }

        .trip-name {
            font-size: 13px;
            font-weight: 600;
            color: #f1f5f9;
            line-height: 1.2;
        }

        .trip-code {
            font-size: 11px;
            color: #475569;
            margin-top: 1px;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .upload-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            background: rgba(249, 115, 22, 0.08);
            border: 1px solid rgba(249, 115, 22, 0.25);
            border-radius: 10px;
            color: #f97316;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }

        .upload-btn:hover {
            background: rgba(249, 115, 22, 0.15);
            border-color: rgba(249, 115, 22, 0.5);
        }

        /* ─── Map Area ──────────────────────────────── */
        #map-wrapper {
            flex: 1;
            position: relative;
            min-width: 0;
        }

        #chartdiv {
            width: 100%;
            height: 100%;
        }

        /* Top pill */
        #trip-pill {
            position: absolute;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(6, 6, 13, 0.88);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 50px;
            padding: 7px 20px;
            display: none;
            align-items: center;
            gap: 10px;
            z-index: 5;
            white-space: nowrap;
        }

        #trip-pill .pill-name {
            font-size: 13px;
            font-weight: 700;
            color: #f97316;
        }

        #trip-pill .pill-meta {
            font-size: 11px;
            color: #475569;
            border-left: 1px solid rgba(255, 255, 255, 0.07);
            padding-left: 10px;
        }

        /* ─── Photo Panel (right) ──────────────────── */
        #photo-panel {
            width: 0;
            flex-shrink: 0;
            background: rgba(6, 6, 13, 0.99);
            border-left: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: width 0.38s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 20;
        }

        #photo-panel.open {
            width: 420px;
        }

        .panel-head {
            padding: 16px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            flex-shrink: 0;
        }

        .panel-head h2 {
            font-size: 15px;
            font-weight: 700;
            color: #f97316;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 320px;
        }

        .close-btn {
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 7px;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
            transition: all 0.15s;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.09);
            color: #e2e8f0;
        }

        /* Feed */
        #photos-feed {
            flex: 1;
            overflow-y: auto;
            padding: 14px;
            scrollbar-width: thin;
            scrollbar-color: rgba(249, 115, 22, 0.25) transparent;
        }

        /* Photo card */
        .photo-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            margin-bottom: 14px;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .photo-card:hover {
            border-color: rgba(249, 115, 22, 0.25);
        }

        .photo-card .pic-wrap {
            position: relative;
            cursor: pointer;
        }

        .photo-card img {
            width: 100%;
            display: block;
            max-height: 360px;
            object-fit: cover;
        }

        .photo-card video {
            width: 100%;
            display: block;
        }

        .photo-meta {
            padding: 10px 12px 6px;
        }

        .photo-date {
            font-size: 11px;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .photo-date a {
            color: #f97316;
            text-decoration: none;
            font-size: 11px;
        }

        .photo-text {
            font-size: 13px;
            line-height: 1.65;
            color: #94a3b8;
        }

        .photo-actions {
            display: flex;
            gap: 16px;
            padding: 8px 12px 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            margin-top: 6px;
        }

        .action-btn {
            font-size: 12px;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            user-select: none;
            transition: color 0.15s;
        }

        .action-btn:hover {
            color: #f97316;
        }

        /* Loader dots */
        .loader {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
            gap: 6px;
        }

        .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #f97316;
            animation: blink 1.4s ease-in-out infinite;
        }

        .dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes blink {

            0%,
            80%,
            100% {
                opacity: 0.25;
                transform: scale(0.8);
            }

            40% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Alternatives at end */
        #alternatives {
            display: none;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding: 14px;
            flex-shrink: 0;
        }

        .alt-label {
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
        }

        .alt-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 9px;
            cursor: pointer;
            transition: background 0.15s;
            margin-bottom: 4px;
        }

        .alt-card:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .alt-card .alt-name {
            font-size: 13px;
            font-weight: 600;
            color: #e2e8f0;
        }

        .alt-card .alt-code {
            font-size: 11px;
            color: #475569;
        }

        /* Lightbox */
        #lightbox {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.96);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #lightbox.show {
            display: flex;
        }

        #lightbox img {
            max-height: 95vh;
            max-width: 95vw;
            border-radius: 6px;
            box-shadow: 0 0 80px rgba(249, 115, 22, 0.15);
        }
    </style>
</head>

<body>

    <div id="app">

        {{-- ── Sidebar ── --}}
        <nav id="sidebar">
            <div class="sidebar-logo">
                <h1>✈ Travel Map</h1>
                <p>Deine Reisen auf einen Blick</p>
            </div>
            <div class="trip-list">
                @foreach ($galleries as $gallery)
                    <div class="trip-item" data-id="{{ $gallery->id }}"
                        onclick="loadGallery({{ $gallery->id }}, '{{ addslashes($gallery->name) }}', '{{ $gallery->code }}')">
                        <div class="trip-icon">✈</div>
                        <div>
                            <div class="trip-name">{{ $gallery->name }}</div>
                            <div class="trip-code">{{ $gallery->code }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="sidebar-footer">
                <a href="/gallery-admin" class="upload-btn"
                    style="margin-bottom: 6px; background: rgba(59,130,246,0.08); border-color: rgba(59,130,246,0.25); color: #3b82f6;">
                    <i class="fa-solid fa-gear"></i> Administration
                </a>
                <a href="/travel-blog/upload-bulk" class="upload-btn">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Fotos hochladen
                </a>
            </div>
        </nav>

        {{-- ── Map ── --}}
        <div id="map-wrapper">
            <div id="trip-pill">
                <span class="pill-name" id="pill-name"></span>
                <span class="pill-meta" id="pill-meta"></span>
            </div>
            <div id="chartdiv"></div>
        </div>

        {{-- ── Photo Panel ── --}}
        <aside id="photo-panel">
            <div class="panel-head">
                <h2 id="panel-title">...</h2>
                <button class="close-btn" onclick="closePanel()" title="Schließen">✕</button>
            </div>
            <div id="photos-feed">
                <div class="loader">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
            <div id="alternatives">
                <div class="alt-label">Noch nicht gesehen</div>
                <div id="alt-list"></div>
            </div>
        </aside>

    </div>

    {{-- Lightbox --}}
    <div id="lightbox" onclick="closeLightbox()">
        <img id="lightbox-img" src="" alt="">
    </div>

    {{-- amCharts --}}
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <script>
        am5.ready(function() {

            // ── Visited country codes (from PHP) ────────────
            var visitedCodes = @json($galleries->pluck('code')->unique()->values());

            // ── Colors ──────────────────────────────────────
            var C_BG = am5.color(0x06060d);
            var C_LAND = am5.color(0x111827); // dark blue-grey
            var C_VISITED = am5.color(0x1a3a2a); // dark green for visited
            var C_BORDER = am5.color(0x06060d);
            var C_HOVER = am5.color(0x1e3a5f);
            var C_PIN = am5.color(0xf97316); // orange
            var C_RING = am5.color(0xf97316);
            var C_LINE = am5.color(0xf97316);
            var C_PLANE = am5.color(0xffffff);
            var C_LABEL = am5.color(0xf1f5f9);
            var C_LABBG = am5.color(0x06060d);

            // ── Root ─────────────────────────────────────────
            var root = am5.Root.new("chartdiv");
            root.setThemes([am5themes_Animated.new(root)]);

            var chart = root.container.children.push(
                am5map.MapChart.new(root, {
                    panX: "rotateX",
                    projection: am5map.geoNaturalEarth1(),
                    maxZoomLevel: 64,
                })
            );

            // background color
            chart.chartContainer.set("background",
                am5.Rectangle.new(root, {
                    fill: C_BG,
                    fillOpacity: 1
                })
            );

            // ── Countries ────────────────────────────────────
            var polySeries = chart.series.push(
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

            polySeries.mapPolygons.template.states.create("hover", {
                fill: C_HOVER
            });

            // Highlight visited countries after geodata is loaded
            polySeries.events.on("datavalidated", function() {
                polySeries.mapPolygons.each(function(polygon) {
                    var id = polygon.dataItem.get("id");
                    if (visitedCodes.indexOf(id) !== -1) {
                        polygon.set("fill", C_VISITED);
                    }
                });
            });

            // ── Line series ──────────────────────────────────
            var lineSeries = chart.series.push(am5map.MapLineSeries.new(root, {}));
            lineSeries.mapLines.template.setAll({
                stroke: C_LINE,
                strokeOpacity: 0.5,
                strokeWidth: 1.2,
                strokeDasharray: [5, 5],
            });

            // Invisible full-route line for plane animation
            var planeLineSeries = chart.series.push(am5map.MapLineSeries.new(root, {}));
            planeLineSeries.mapLines.template.setAll({
                strokeOpacity: 0,
                strokeWidth: 0,
            });

            // ── Point series (pins) ──────────────────────────
            var pinSeries = chart.series.push(am5map.MapPointSeries.new(root, {}));

            pinSeries.bullets.push(function() {
                var cont = am5.Container.new(root, {
                    cursorOverStyle: "pointer"
                });

                // Outer ring
                cont.children.push(am5.Circle.new(root, {
                    radius: 13,
                    fill: C_RING,
                    fillOpacity: 0.15,
                }));

                // Inner dot
                cont.children.push(am5.Circle.new(root, {
                    radius: 6,
                    fill: C_PIN,
                    stroke: am5.color(0xffffff),
                    strokeWidth: 1.5,
                }));

                // Label
                cont.children.push(am5.Label.new(root, {
                    text: "{title}",
                    fontSize: 11,
                    fontWeight: "600",
                    fill: C_LABEL,
                    dy: -22,
                    centerX: am5.p50,
                    populateText: true,
                    paddingLeft: 6,
                    paddingRight: 6,
                    paddingTop: 2,
                    paddingBottom: 2,
                    background: am5.RoundedRectangle.new(root, {
                        fill: C_LABBG,
                        fillOpacity: 0.85,
                        cornerRadiusTL: 4,
                        cornerRadiusTR: 4,
                        cornerRadiusBL: 4,
                        cornerRadiusBR: 4,
                    }),
                }));

                cont.events.on("click", function(e) {
                    var ctx = e.target.dataItem.dataContext;
                    if (ctx && ctx.mappoint_id) {
                        openMappoint(ctx.mappoint_id, ctx.title);
                    }
                });

                return am5.Bullet.new(root, {
                    sprite: cont
                });
            });

            // ── Plane ────────────────────────────────────────
            var planeSeries = chart.series.push(am5map.MapPointSeries.new(root, {}));
            var planeShape = am5.Graphics.new(root, {
                svgPath: "m2,106h28l24,30h72l-44,-133h35l80,132h98c21,0 21,34 0,34l-98,0 -80,134h-35l43,-133h-71l-24,30h-28l15,-47",
                scale: 0.045,
                centerY: am5.p50,
                centerX: am5.p50,
                fill: C_PLANE,
            });
            planeSeries.bullets.push(function() {
                var c = am5.Container.new(root, {});
                c.children.push(planeShape);
                return am5.Bullet.new(root, {
                    sprite: c
                });
            });

            // ── Home button ──────────────────────────────────
            chart.children.push(am5.Button.new(root, {
                paddingTop: 8,
                paddingBottom: 8,
                paddingLeft: 8,
                paddingRight: 8,
                x: am5.percent(100),
                centerX: am5.percent(100),
                y: 12,
                background: am5.RoundedRectangle.new(root, {
                    fill: am5.color(0x111827),
                    cornerRadiusTL: 6,
                    cornerRadiusTR: 6,
                    cornerRadiusBL: 6,
                    cornerRadiusBR: 6,
                }),
                icon: am5.Graphics.new(root, {
                    svgPath: "M16,8 L14,8 L14,16 L10,16 L10,10 L6,10 L6,16 L2,16 L2,8 L0,8 L8,0 L16,8 Z",
                    fill: am5.color(0xffffff),
                }),
            })).events.on("click", function() {
                chart.goHome();
            });

            // Store refs globally
            window._map = {
                root,
                chart,
                pinSeries,
                lineSeries,
                planeLineSeries,
                planeSeries,
                planeShape
            };
        });

        // ════════════════════════════════════════════════════════
        //  State
        // ════════════════════════════════════════════════════════
        var currentGalleryId = null;
        var currentMappointId = null;
        var photoPage = 1;
        var loadingPhotos = false;
        var noMorePhotos = false;
        var planeAnimation = null;

        // ════════════════════════════════════════════════════════
        //  Load a gallery onto the map
        // ════════════════════════════════════════════════════════
        function loadGallery(id, name, code) {
            currentGalleryId = id;

            // highlight sidebar item
            document.querySelectorAll('.trip-item').forEach(function(el) {
                el.classList.toggle('active', el.dataset.id == id);
            });

            document.getElementById('pill-name').textContent = name;
            var pill = document.getElementById('trip-pill');
            pill.style.display = 'flex';

            closePanel();

            fetch('/travel-blog/map-data/' + id)
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    var m = window._map;
                    if (!m) return;

                    m.pinSeries.data.clear();
                    m.lineSeries.data.clear();
                    m.planeLineSeries.data.clear();
                    m.planeSeries.data.clear();

                    var mps = data.mappoints;
                    document.getElementById('pill-meta').textContent = mps.length + ' Orte';

                    if (!mps.length) return;

                    // Pins
                    mps.forEach(function(mp) {
                        m.pinSeries.data.push({
                            geometry: {
                                type: "Point",
                                coordinates: [mp.lon, mp.lat]
                            },
                            title: mp.name,
                            mappoint_id: mp.id,
                        });
                    });

                    // Segment lines
                    if (mps.length > 1) {
                        for (var i = 0; i < mps.length - 1; i++) {
                            m.lineSeries.data.push({
                                geometry: {
                                    type: "LineString",
                                    coordinates: [
                                        [mps[i].lon, mps[i].lat],
                                        [mps[i + 1].lon, mps[i + 1].lat],
                                    ]
                                }
                            });
                        }

                        // Full-route invisible line for plane
                        var allCoords = mps.map(function(mp) {
                            return [mp.lon, mp.lat];
                        });
                        var routeLine = m.planeLineSeries.pushDataItem({
                            geometry: {
                                type: "LineString",
                                coordinates: allCoords
                            }
                        });

                        var planeItem = m.planeSeries.pushDataItem({
                            lineDataItem: routeLine,
                            positionOnLine: 0,
                            autoRotate: true,
                        });

                        planeItem.animate({
                            key: "positionOnLine",
                            to: 1,
                            duration: 14000,
                            loops: Infinity,
                            easing: am5.ease.yoyo(am5.ease.linear),
                        });

                        planeItem.on("positionOnLine", function(val) {
                            m.planeShape.set("rotation", val >= 0.99 ? 180 : 0);
                        });
                    }

                    // Zoom to first point
                    m.chart.zoomToGeoPoint({
                            longitude: mps[0].lon,
                            latitude: mps[0].lat
                        },
                        2.5, true
                    );
                });
        }

        // ════════════════════════════════════════════════════════
        //  Open mappoint photo panel
        // ════════════════════════════════════════════════════════
        function openMappoint(mappointId, name) {
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
        }

        function closePanel() {
            document.getElementById('photo-panel').classList.remove('open');
        }

        // ════════════════════════════════════════════════════════
        //  Load photos (paginated)
        // ════════════════════════════════════════════════════════
        function loadPhotos() {
            if (loadingPhotos || noMorePhotos) return;
            loadingPhotos = true;

            fetch('/travel-blog/mappoint-photos/' + currentMappointId + '?page=' + photoPage)
                .then(function(r) {
                    if (!r.ok) {
                        throw new Error('HTTP Error: ' + r.status);
                    }
                    return r.json();
                })
                .then(function(data) {
                    loadingPhotos = false;

                    var feed = document.getElementById('photos-feed');
                    var loader = document.getElementById('photos-loader');
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
                        // infinite scroll sentinel
                        var sentinel = document.createElement('div');
                        sentinel.id = 'sentinel';
                        sentinel.style.height = '1px';
                        feed.appendChild(sentinel);

                        var io = new IntersectionObserver(function(entries) {
                            if (entries[0].isIntersecting) {
                                io.disconnect();
                                sentinel.remove();
                                loadPhotos();
                            }
                        }, {
                            root: feed
                        });
                        io.observe(sentinel);
                    } else {
                        noMorePhotos = true;
                        renderAlternatives(data.alternatives || []);
                    }
                })
                .catch(function(error) {
                    loadingPhotos = false;
                    console.error('Error loading photos:', error);
                    var feed = document.getElementById('photos-feed');
                    var loader = document.getElementById('photos-loader');
                    if (loader) loader.remove();

                    feed.innerHTML =
                        '<div style="text-align:center;padding:50px;color:#ef4444;font-size:13px">' +
                        '<div style="font-size:32px;margin-bottom:10px">❌</div>Fehler beim Laden der Fotos.<br>' +
                        '<small style="color:#475569">' + error.message + '</small></div>';
                });
        }

        function renderPhotoCard(photo) {
            var feed = document.getElementById('photos-feed');

            var mediaHtml;
            if (photo.is_video) {
                if (photo.content) {
                    mediaHtml = '<video controls style="width:100%"><source src="' + photo.content +
                        '" type="video/mp4"></video>';
                } else {
                    mediaHtml =
                        '<div style="width:100%;height:200px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#475569"><i class="fa-solid fa-video-slash text-4xl"></i></div>';
                }
            } else {
                if (photo.thumbnail) {
                    mediaHtml = '<img src="' + photo.thumbnail + '" alt="" loading="lazy">';
                } else {
                    mediaHtml =
                        '<div style="width:100%;height:200px;background:#1e293b;display:flex;align-items:center;justify-content:center;color:#475569"><i class="fa-solid fa-image-slash text-4xl"></i></div>';
                }
            }

            var dateHtml = photo.taken_at ?
                '<i class="fa-regular fa-calendar" style="color:#f97316"></i> ' + photo.taken_at :
                '';

            var gpsHtml = (photo.lat && photo.lon) ?
                ' &nbsp;<a href="https://www.google.com/maps?q=' + photo.lat + ',' + photo.lon + '" target="_blank">' +
                '<i class="fa-solid fa-location-dot" style="color:#f97316"></i> Maps</a>' :
                '';

            var textHtml = photo.text ?
                '<div class="photo-text">' + photo.text + '</div>' :
                '';

            var clickAttr = (photo.is_video || !photo.thumbnail) ? '' : ' onclick="showFullPic(' + photo.id + ')"';

            var html = '<div class="photo-card">' +
                '<div class="pic-wrap"' + clickAttr + '>' + mediaHtml + '</div>' +
                '<div class="photo-meta">' +
                '<div class="photo-date">' + dateHtml + gpsHtml + '</div>' +
                textHtml +
                '</div>' +
                '<div class="photo-actions">' +
                '<span class="action-btn"><i class="fa-regular fa-heart"></i> Gefällt mir</span>' +
                '<span class="action-btn"><i class="fa-regular fa-comment"></i> Kommentar</span>' +
                '</div>' +
                '</div>';

            feed.insertAdjacentHTML('beforeend', html);
        }

        // ════════════════════════════════════════════════════════
        //  Alternatives (end of feed)
        // ════════════════════════════════════════════════════════
        function renderAlternatives(alts) {
            if (!alts || !alts.length) return;
            var list = document.getElementById('alt-list');
            list.innerHTML = '';
            alts.forEach(function(g) {
                list.insertAdjacentHTML('beforeend',
                    '<div class="alt-card" onclick="loadGallery(' + g.id + ', \'' + g.name.replace(/'/g, "\'") +
                    '\', \'' + g.code + '\'); closePanel();">' +
                    '  <div class="trip-icon" style="font-size:14px">✈</div>' +
                    '  <div><div class="alt-name">' + g.name + '</div><div class="alt-code">' + g.code +
                    '</div></div>' +
                    '</div>'
                );
            });
            document.getElementById('alternatives').style.display = 'block';
        }

        // ════════════════════════════════════════════════════════
        //  Lightbox
        // ════════════════════════════════════════════════════════
        function showFullPic(id) {
            fetch('/travel-blog/getBigPic/' + id)
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    document.getElementById('lightbox-img').src = data.data;
                    document.getElementById('lightbox').classList.add('show');
                });
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('show');
            document.getElementById('lightbox-img').src = '';
        }
    </script>

</body>

</html>
