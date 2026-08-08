/* ==========================================================================
   travelblog2.js — /travel-blog2
   Weltkarte mit eingefaerbten Laendern, klickbare Punkte und ein Foto-Feed,
   der am Ende einer Reise automatisch in die naechste weiterlaeuft.
   amCharts5 wird im Blade per CDN geladen.
   ========================================================================== */

(function () {
    'use strict';

    const root = document.querySelector('.nn-root.tb2');
    if (!root) return;

    const cfg = {
        countries: root.dataset.countriesUrl,
        feed: root.dataset.feedUrl,
    };

    const els = {
        map: root.querySelector('[data-tb2-map]'),
        loading: root.querySelector('[data-tb2-loading]'),
        list: root.querySelector('[data-tb2-list]'),
        stats: root.querySelector('[data-tb2-stats]'),
        feed: root.querySelector('[data-tb2-feed]'),
        feedBody: root.querySelector('[data-tb2-feed-body]'),
        feedTitle: root.querySelector('[data-tb2-feed-title]'),
        feedSub: root.querySelector('[data-tb2-feed-sub]'),
        close: root.querySelector('[data-tb2-close]'),
    };

    // Farben aus der Palette. Canvas/amCharts koennen keine CSS-Variablen lesen,
    // deshalb hier einmal aus dem berechneten Stil geholt statt hart kodiert.
    const css = getComputedStyle(root);
    const COL = {
        cyan: (css.getPropertyValue('--nn-cyan') || '#00e5ff').trim(),
        amber: (css.getPropertyValue('--nn-amber') || '#ffb84d').trim(),
        bg: (css.getPropertyValue('--nn-bg') || '#04070f').trim(),
    };

    let countries = [];
    const byCode = new Map();

    /* ---------------------------------------------------------------------
       Daten laden
       --------------------------------------------------------------------- */
    fetch(cfg.countries, { headers: { 'Accept': 'application/json' } })
        .then((r) => r.json())
        .then((data) => {
            countries = data.countries || [];
            countries.forEach((c) => { if (c.code) byCode.set(c.code, c); });

            renderStats(data.totals || {});
            renderList();
            buildMap();

            if (els.loading) els.loading.classList.add('is-done');
        })
        .catch((err) => {
            console.error('travelblog2: Laden fehlgeschlagen', err);
            if (els.loading) els.loading.textContent = 'Daten konnten nicht geladen werden';
        });

    function renderStats(totals) {
        if (!els.stats) return;
        const items = [
            [totals.countries || 0, 'Länder'],
            [totals.photos || 0, 'Fotos'],
            [totals.points || 0, 'Orte'],
        ];
        els.stats.innerHTML = items
            .map(([n, label]) => `<div class="tb2-stat"><b>${n}</b><span>${label}</span></div>`)
            .join('');
    }

    function renderList() {
        if (!els.list) return;
        els.list.innerHTML = countries
            .map((c) => `
                <button type="button" class="tb2-country" data-gallery="${c.gallery_id}">
                    <span>${escapeHtml(c.name)}<br><span class="tb2-code">${c.code}</span></span>
                    <span class="tb2-num">${c.pic_count}</span>
                </button>
            `)
            .join('');

        els.list.querySelectorAll('.tb2-country').forEach((btn) => {
            btn.addEventListener('click', () => openFeed(parseInt(btn.dataset.gallery, 10)));
        });
    }

    function markActive(galleryId) {
        root.querySelectorAll('.tb2-country').forEach((b) => {
            b.classList.toggle('is-active', parseInt(b.dataset.gallery, 10) === galleryId);
        });
    }

    /* ---------------------------------------------------------------------
       Weltkarte
       --------------------------------------------------------------------- */
    function buildMap() {
        if (!els.map || typeof window.am5 === 'undefined') {
            if (els.loading) els.loading.textContent = 'Kartenbibliothek nicht geladen';
            return;
        }

        const r = am5.Root.new(els.map);
        r.setThemes([am5themes_Animated.new(r)]);
        if (r._logo) r._logo.dispose();

        const chart = r.container.children.push(
            am5map.MapChart.new(r, {
                projection: am5map.geoNaturalEarth1(),
                panX: 'rotateX',
                panY: 'none',
                wheelY: 'zoom',
            })
        );

        const polygons = chart.series.push(
            am5map.MapPolygonSeries.new(r, {
                geoJSON: am5geodata_worldLow,
                exclude: ['AQ'],
            })
        );

        polygons.mapPolygons.template.setAll({
            fill: am5.color(0x101a2c),
            stroke: am5.color(0x1d2b45),
            strokeWidth: 0.5,
            interactive: true,
        });

        // Besuchte Laender einfaerben
        polygons.mapPolygons.template.adapters.add('fill', (fill, target) => {
            const id = target.dataItem && target.dataItem.get('id');
            return byCode.has(id) ? am5.color(COL.cyan) : fill;
        });

        polygons.mapPolygons.template.adapters.add('fillOpacity', (op, target) => {
            const id = target.dataItem && target.dataItem.get('id');
            if (!byCode.has(id)) return op;
            // mehr Fotos -> kraeftiger
            const n = byCode.get(id).pic_count || 1;
            return Math.min(0.28 + n * 0.05, 0.75);
        });

        polygons.mapPolygons.template.adapters.add('tooltipText', (t, target) => {
            const id = target.dataItem && target.dataItem.get('id');
            if (!byCode.has(id)) return null;
            const c = byCode.get(id);
            return `[bold]${c.name}[/]\n${c.pic_count} Fotos`;
        });

        polygons.mapPolygons.template.states.create('hover', {
            fill: am5.color(COL.amber),
            fillOpacity: 0.85,
        });

        polygons.mapPolygons.template.events.on('click', (ev) => {
            const id = ev.target.dataItem && ev.target.dataItem.get('id');
            if (byCode.has(id)) openFeed(byCode.get(id).gallery_id);
        });

        // Kartenpunkte
        const points = chart.series.push(
            am5map.MapPointSeries.new(r, {})
        );

        points.bullets.push((bulletRoot, series, dataItem) => {
            const container = am5.Container.new(bulletRoot, { cursorOverStyle: 'pointer' });

            container.children.push(am5.Circle.new(bulletRoot, {
                radius: 9,
                fill: am5.color(COL.amber),
                fillOpacity: 0.18,
            }));

            container.children.push(am5.Circle.new(bulletRoot, {
                radius: 3.6,
                fill: am5.color(COL.amber),
                stroke: am5.color(COL.bg),
                strokeWidth: 1,
                tooltipText: dataItem.dataContext.title,
            }));

            container.events.on('click', () => openFeed(dataItem.dataContext.galleryId));

            return am5.Bullet.new(bulletRoot, { sprite: container });
        });

        countries.forEach((c) => {
            (c.points || []).forEach((p) => {
                points.data.push({
                    geometry: { type: 'Point', coordinates: [p.lon, p.lat] },
                    title: `${p.name} — ${c.name}`,
                    galleryId: c.gallery_id,
                });
            });
        });
    }

    /* ---------------------------------------------------------------------
       Feed
       --------------------------------------------------------------------- */
    let state = { gallery: null, offset: 0, loading: false, done: false };
    let observer = null;

    // Alle bereits eingehaengten Bilder, in Feed-Reihenfolge — Grundlage der Lightbox
    let shots = [];

    function openFeed(galleryId) {
        if (!els.feed || !galleryId) return;

        state = { gallery: galleryId, offset: 0, loading: false, done: false };
        shots = [];
        els.feedBody.innerHTML = '';
        els.feed.classList.add('is-open');
        markActive(galleryId);

        const c = countries.find((x) => x.gallery_id === galleryId);
        if (c) {
            els.feedTitle.textContent = c.name;
            els.feedSub.textContent = `${c.code} // ${c.pic_count} Fotos`;
        }

        loadPage(true);
    }

    function closeFeed() {
        if (els.feed) els.feed.classList.remove('is-open');
        if (observer) { observer.disconnect(); observer = null; }
    }

    if (els.close) els.close.addEventListener('click', closeFeed);

    /* ---------------------------------------------------------------------
       Lightbox
       --------------------------------------------------------------------- */
    let lb = null;
    let shotIndex = -1;

    function buildLightbox() {
        if (lb) return lb;

        const el = document.createElement('div');
        el.className = 'tb2-lightbox';
        el.innerHTML =
            '<button type="button" class="tb2-lb-close" aria-label="Schließen">&times;</button>' +
            '<div class="tb2-lightbox-stage">' +
                '<button type="button" class="tb2-lb-btn tb2-lb-prev" aria-label="Vorheriges Bild">&lsaquo;</button>' +
                '<img alt="">' +
                '<button type="button" class="tb2-lb-btn tb2-lb-next" aria-label="Nächstes Bild">&rsaquo;</button>' +
            '</div>' +
            '<div class="tb2-lightbox-bar"><span data-lb-date></span><span data-lb-count></span></div>' +
            '<div class="tb2-lightbox-caption" data-lb-caption></div>';

        root.appendChild(el);

        lb = {
            el,
            img: el.querySelector('img'),
            date: el.querySelector('[data-lb-date]'),
            count: el.querySelector('[data-lb-count]'),
            caption: el.querySelector('[data-lb-caption]'),
        };

        el.querySelector('.tb2-lb-close').addEventListener('click', closeShot);
        el.querySelector('.tb2-lb-prev').addEventListener('click', (e) => { e.stopPropagation(); step(-1); });
        el.querySelector('.tb2-lb-next').addEventListener('click', (e) => { e.stopPropagation(); step(1); });

        // Klick auf den Hintergrund schliesst, Klick aufs Bild nicht
        el.addEventListener('click', (e) => { if (e.target === el) closeShot(); });

        return lb;
    }

    function openShot(i) {
        if (!shots.length) return;
        buildLightbox();
        shotIndex = i;
        renderShot();
        lb.el.classList.add('is-open');
    }

    function renderShot() {
        const p = shots[shotIndex];
        if (!p) return;

        lb.img.src = p.src;
        lb.date.textContent = p.taken_at || '';
        lb.count.innerHTML = `<b>${shotIndex + 1}</b> / ${shots.length}`;
        lb.caption.textContent = p.text || '';
    }

    function step(dir) {
        if (!shots.length) return;
        shotIndex = (shotIndex + dir + shots.length) % shots.length;
        renderShot();
    }

    function closeShot() {
        if (lb) lb.el.classList.remove('is-open');
        shotIndex = -1;
    }

    function shotOpen() {
        return lb && lb.el.classList.contains('is-open');
    }

    document.addEventListener('keydown', (e) => {
        if (shotOpen()) {
            // Lightbox hat Vorrang vor dem Feed
            if (e.key === 'Escape') { closeShot(); }
            else if (e.key === 'ArrowRight') { step(1); }
            else if (e.key === 'ArrowLeft') { step(-1); }
            return;
        }

        if (e.key === 'Escape') closeFeed();
    });

    function loadPage(isFirst) {
        if (state.loading || state.done) return;
        state.loading = true;

        const url = `${cfg.feed}?gallery=${state.gallery}&offset=${state.offset}`;

        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then((r) => r.json())
            .then((data) => {
                if (!data.trip) { state.done = true; return; }

                // Ueberschrift, wenn eine neue Reise beginnt
                if (isFirst || state.offset === 0) {
                    appendTripHead(data.trip);
                }

                (data.photos || []).forEach(appendPhoto);

                if (data.has_more) {
                    state.offset = data.offset;
                } else if (data.next_trip) {
                    // nahtlos in die naechste Reise wechseln
                    state.gallery = data.next_trip.gallery_id;
                    state.offset = 0;
                } else {
                    state.done = true;
                    appendEnd();
                }

                state.loading = false;
                armSentinel();
            })
            .catch((err) => {
                console.error('travelblog2: Feed fehlgeschlagen', err);
                state.loading = false;
            });
    }

    function appendTripHead(trip) {
        const head = document.createElement('div');
        head.className = 'tb2-trip-head';
        head.innerHTML =
            `<span>${trip.code} // ${trip.pic_count} Fotos</span>` +
            `<h4>${escapeHtml(trip.name)}</h4>`;
        els.feedBody.appendChild(head);

        els.feedTitle.textContent = trip.name;
        els.feedSub.textContent = `${trip.code} // ${trip.pic_count} Fotos`;
        markActive(trip.gallery_id);
    }

    function appendPhoto(p) {
        const card = document.createElement('article');
        card.className = 'tb2-photo';

        const media = p.is_video && p.video
            ? `<video controls preload="none" ${p.src ? `poster="${p.src}"` : ''} src="${p.video}"></video>`
            : (p.src ? `<img loading="lazy" src="${p.src}" alt="">` : '');

        const geo = (p.lat !== null && p.lon !== null)
            ? `<a href="https://www.google.com/maps?q=${p.lat},${p.lon}" target="_blank" rel="noopener">Karte</a>`
            : '<span></span>';

        card.innerHTML =
            `<figure>${media}</figure>` +
            `<div class="tb2-photo-meta"><span>${p.taken_at || ''}</span>${geo}</div>` +
            (p.text ? `<div class="tb2-photo-text">${escapeHtml(p.text)}</div>` : '');

        // Nur Bilder in die Lightbox; Videos behalten ihre eigenen Bedienelemente
        if (!p.is_video && p.src) {
            const idx = shots.length;
            shots.push(p);
            const img = card.querySelector('img');
            if (img) img.addEventListener('click', () => openShot(idx));
        }

        els.feedBody.appendChild(card);
    }

    function appendEnd() {
        const end = document.createElement('div');
        end.className = 'tb2-end';
        end.textContent = 'Ende — alle Reisen durchlaufen';
        els.feedBody.appendChild(end);
    }

    /**
     * Sentinel ans Ende haengen; sobald er sichtbar wird, die naechste Seite
     * holen. Wird nach jedem Nachladen neu gesetzt.
     */
    function armSentinel() {
        if (observer) { observer.disconnect(); observer = null; }

        const old = els.feedBody.querySelector('.tb2-sentinel');
        if (old) old.remove();

        if (state.done) return;

        const s = document.createElement('div');
        s.className = 'tb2-sentinel';
        s.textContent = 'lädt …';
        els.feedBody.appendChild(s);

        observer = new IntersectionObserver((entries) => {
            if (entries.some((e) => e.isIntersecting)) loadPage(false);
        }, { root: els.feedBody, rootMargin: '200px' });

        observer.observe(s);
    }

    function escapeHtml(str) {
        return String(str == null ? '' : str).replace(/[&<>"']/g, (ch) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
        }[ch]));
    }
})();
