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
        // nur fuer Admins gesetzt (Blade) - sonst gibt es keine Loesch-Knoepfe
        deleteUrl: root.dataset.deleteUrl || null,
        csrf: root.dataset.csrf || null,
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

            container.events.on('click', () => openFeed(dataItem.dataContext.galleryId, dataItem.dataContext.mappointId));

            return am5.Bullet.new(bulletRoot, { sprite: container });
        });

        countries.forEach((c) => {
            (c.points || []).forEach((p) => {
                points.data.push({
                    geometry: { type: 'Point', coordinates: [p.lon, p.lat] },
                    title: `${p.name} — ${c.name}`,
                    galleryId: c.gallery_id,
                    mappointId: p.id,
                });
            });
        });
    }

    /* ---------------------------------------------------------------------
       Feed
       --------------------------------------------------------------------- */
    let state = { gallery: null, offset: 0, loading: false, done: false, jump: null };
    let observer = null;

    // Alle bereits eingehaengten Bilder, in Feed-Reihenfolge — Grundlage der Lightbox
    let shots = [];

    /**
     * Feed einer Reise oeffnen. Mit mappointId (Klick auf einen Ort auf der
     * Karte) springt der Feed zum ersten Foto dieses Orts statt an den Anfang.
     */
    function openFeed(galleryId, mappointId) {
        if (!els.feed || !galleryId) return;
        if (cancelJump) cancelJump();

        state = { gallery: galleryId, offset: 0, loading: false, done: false, jump: mappointId || null };
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
        if (cancelJump) cancelJump();
        if (els.feed) els.feed.classList.remove('is-open');
        if (observer) { observer.disconnect(); observer = null; }
    }

    if (els.close) els.close.addEventListener('click', closeFeed);

    /* ---------------------------------------------------------------------
       Vorladen
       Die grossen Fassungen kommen aus der Datenbank und brauchen spuerbar
       Zeit. Bilder, die gleich gebraucht werden, laedt der Browser deshalb
       schon im Hintergrund (danach liegen sie im Cache).
       --------------------------------------------------------------------- */
    const preloaded = new Map();   // url -> Image
    const PRELOAD_MAX = 40;        // Speicher begrenzen
    const saveData = !!(navigator.connection && navigator.connection.saveData);

    function preload(url) {
        if (!url || preloaded.has(url)) return;
        const img = new Image();
        img.decoding = 'async';
        img.src = url;
        preloaded.set(url, img);
        if (preloaded.size > PRELOAD_MAX) {
            preloaded.delete(preloaded.keys().next().value);  // aeltesten Eintrag verwerfen
        }
    }

    function isPreloaded(url) {
        const img = preloaded.get(url);
        return !!(img && img.complete && img.naturalWidth > 0);
    }

    /** Nachbarn der offenen Lightbox vorladen: die naechsten zwei und das vorherige */
    function preloadNeighbours() {
        if (!shots.length) return;
        const steps = saveData ? [1] : [1, 2, -1];
        steps.forEach((d) => {
            const s = shots[(shotIndex + d + shots.length) % shots.length];
            if (s) preload(s.is_video ? s.src : (s.full || s.src));
        });
    }

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
                '<video controls playsinline preload="metadata" hidden></video>' +
                '<button type="button" class="tb2-lb-btn tb2-lb-next" aria-label="Nächstes Bild">&rsaquo;</button>' +
            '</div>' +
            '<div class="tb2-lightbox-bar"><span data-lb-date></span>' +
                (cfg.deleteUrl ? '<button type="button" class="tb2-del" data-lb-delete>Löschen</button>' : '') +
                '<span data-lb-count></span></div>' +
            '<div class="tb2-lightbox-caption" data-lb-caption></div>';

        root.appendChild(el);

        lb = {
            el,
            img: el.querySelector('img'),
            video: el.querySelector('video'),
            date: el.querySelector('[data-lb-date]'),
            count: el.querySelector('[data-lb-count]'),
            caption: el.querySelector('[data-lb-caption]'),
        };

        el.querySelector('.tb2-lb-close').addEventListener('click', closeShot);
        const del = el.querySelector('[data-lb-delete]');
        if (del) del.addEventListener('click', (e) => { e.stopPropagation(); if (shots[shotIndex]) deletePhoto(shots[shotIndex]); });
        el.querySelector('.tb2-lb-prev').addEventListener('click', (e) => { e.stopPropagation(); step(-1); });
        el.querySelector('.tb2-lb-next').addEventListener('click', (e) => { e.stopPropagation(); step(1); });

        // Klick auf den Hintergrund schliesst, Klick aufs Bild nicht
        el.addEventListener('click', (e) => { if (e.target === el) closeShot(); });

        return lb;
    }

    /**
     * Oeffnet genau dieses Foto. Die Position wird ueber die id gesucht, damit
     * sie fuer Zaehler und Blaettern stimmt, auch wenn sich die Liste durch
     * Nachladen veraendert hat.
     */
    function openShotFor(p) {
        let i = shots.findIndex((s) => s.id === p.id);
        if (i < 0) { shots.push(p); i = shots.length - 1; }
        openShot(i);
    }

    function openShot(i) {
        if (!shots.length) return;
        buildLightbox();
        shotIndex = i;
        renderShot();
        lb.el.classList.add('is-open');
    }

    /** Laufendes Video anhalten und entladen, damit es nicht weiterspielt/-laedt. */
    function stopVideo() {
        if (!lb || !lb.video.getAttribute('src')) return;
        lb.video.pause();
        lb.video.removeAttribute('src');
        lb.video.load();
    }

    function renderShot() {
        const p = shots[shotIndex];
        if (!p) return;

        stopVideo();

        if (p.is_video && p.video) {
            lb.img.hidden = true;
            lb.video.hidden = false;
            lb.el.classList.remove('is-loading');
            if (p.src) lb.video.poster = p.src; else lb.video.removeAttribute('poster');
            lb.video.src = p.video;
            // Abspielen wurde per Klick ausgeloest, Browser erlauben das daher
            // auch mit Ton. Klappt es trotzdem nicht, bleiben die Controls.
            lb.video.play().catch(() => {});
            renderShotMeta(p);
            preloadNeighbours();
            return;
        }

        lb.video.hidden = true;
        lb.img.hidden = false;

        // Schon vorgeladen: sofort die grosse Fassung, ohne Zwischenschritt
        if (p.full && isPreloaded(p.full)) {
            lb.img.src = p.full;
            lb.el.classList.remove('is-loading');
            renderShotMeta(p);
            preloadNeighbours();
            return;
        }

        // Zuerst das Thumbnail, das liegt schon im Browsercache und erscheint
        // sofort — sonst zeigt der Browser weiter das zuvor geoeffnete Bild,
        // bis die grosse Fassung geladen ist.
        lb.img.src = p.src;
        lb.el.classList.add('is-loading');

        if (p.full && p.full !== p.src) {
            const big = new Image();
            big.onload = () => {
                // nur uebernehmen, wenn inzwischen nicht weitergeblaettert wurde
                if (shots[shotIndex] && shots[shotIndex].id === p.id) {
                    lb.img.src = p.full;
                    lb.el.classList.remove('is-loading');
                }
            };
            big.onerror = () => lb.el.classList.remove('is-loading');
            big.src = p.full;
        } else {
            lb.el.classList.remove('is-loading');
        }
        renderShotMeta(p);
        preloadNeighbours();
    }

    function renderShotMeta(p) {
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
        stopVideo();
        if (lb) lb.el.classList.remove('is-open');
        shotIndex = -1;
    }

    function shotOpen() {
        return lb && lb.el.classList.contains('is-open');
    }

    document.addEventListener('keydown', (e) => {
        if (shotOpen()) {
            // Lightbox hat Vorrang vor dem Feed
            // Hat das Video den Fokus, gehoeren die Pfeiltasten ihm (Spulen)
            if (e.target === lb.video && e.key !== 'Escape') return;

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

        const jump = isFirst ? state.jump : null;
        const url = `${cfg.feed}?gallery=${state.gallery}&offset=${state.offset}`
            + (jump ? `&mappoint=${jump}` : '');

        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then((r) => r.json())
            .then((data) => {
                if (!data.trip) { state.done = true; return; }

                // Ueberschrift, wenn eine neue Reise beginnt
                if (isFirst || state.offset === 0) {
                    appendTripHead(data.trip);
                }

                (data.photos || []).forEach((p) => { p.galleryId = data.trip.gallery_id; appendPhoto(p); });

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

                if (jump) {
                    state.jump = null;
                    const target = els.feedBody.querySelector(`.tb2-photo[data-mappoint="${jump}"]`);
                    if (target) jumpTo(target);
                }
            })
            .catch((err) => {
                console.error('travelblog2: Feed fehlgeschlagen', err);
                state.loading = false;
            });
    }

    /**
     * Feed so scrollen, dass die Karte oben steht. Bilder davor laden erst
     * nach (teils mehrere Sekunden) und schieben den Inhalt dann nach unten -
     * deshalb jede Groessenaenderung oberhalb nachfuehren, bis der Nutzer
     * selbst scrollt oder ein anderer Feed geoeffnet wird.
     */
    let cancelJump = null;

    function jumpTo(card) {
        if (cancelJump) cancelJump();

        const body = els.feedBody;

        // Die Reise-Ueberschrift klebt (sticky) oben im Feed - die Karte muss
        // darunter stehen, nicht dahinter.
        const head = [...body.querySelectorAll('.tb2-trip-head')]
            .filter((h) => h.compareDocumentPosition(card) & Node.DOCUMENT_POSITION_FOLLOWING)
            .pop();

        const align = () => {
            const offset = (head ? head.getBoundingClientRect().height : 0) + 8;
            const delta = card.getBoundingClientRect().top - body.getBoundingClientRect().top - offset;
            // 'instant': der Feed hat scroll-behavior: smooth, sonst rechnet das
            // naechste Nachfuehren mit einer Zwischenposition der Animation.
            body.scrollTo({ top: body.scrollTop + delta, behavior: 'instant' });
        };

        // Alle Karten beobachten: Bilder davor verschieben das Ziel, Bilder
        // dahinter machen den Feed erst lang genug, um bis zum Ziel zu scrollen.
        const ro = new ResizeObserver(align);
        [...body.children].forEach((el) => ro.observe(el));

        // Nachgeladene Seiten haengen neue Karten an - die auch beobachten
        const mo = new MutationObserver((records) => {
            records.forEach((r) => r.addedNodes.forEach((n) => { if (n.nodeType === 1) ro.observe(n); }));
            align();
        });
        mo.observe(body, { childList: true });

        const events = ['wheel', 'touchstart', 'pointerdown', 'keydown'];
        const stop = () => {
            ro.disconnect();
            mo.disconnect();
            events.forEach((ev) => body.removeEventListener(ev, stop));
            cancelJump = null;
        };
        events.forEach((ev) => body.addEventListener(ev, stop, { passive: true }));
        cancelJump = stop;

        align();
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
        card.dataset.id = p.id;
        if (p.mappoint_id) card.dataset.mappoint = p.mappoint_id;

        // Videos zeigen im Feed nur ihr Standbild mit Play-Symbol und laufen
        // wie die Fotos in der Lightbox.
        const isVideo = p.is_video && p.video;
        const media = isVideo
            ? `<button type="button" class="tb2-video-thumb" aria-label="Video abspielen">` +
                  (p.src ? `<img loading="lazy" src="${p.src}" alt="">` : '') +
              `</button>`
            : (p.src ? `<img loading="lazy" src="${p.src}" alt="">` : '');

        const geo = (p.lat !== null && p.lon !== null)
            ? `<a href="https://www.google.com/maps?q=${p.lat},${p.lon}" target="_blank" rel="noopener">Karte</a>`
            : '<span></span>';

        const del = cfg.deleteUrl ? '<button type="button" class="tb2-del">Löschen</button>' : '';

        card.innerHTML =
            `<figure>${media}</figure>` +
            `<div class="tb2-photo-meta"><span>${p.taken_at || ''}</span>${del}${geo}</div>` +
            (p.text ? `<div class="tb2-photo-text">${escapeHtml(p.text)}</div>` : '');

        // Fotos und Videos teilen sich die Lightbox
        const trigger = isVideo ? card.querySelector('.tb2-video-thumb')
                                : (p.src ? card.querySelector('img') : null);
        if (trigger) {
            shots.push(p);
            trigger.dataset.shotId = p.id;
            // Bewusst das Foto-Objekt binden, keine laufende Nummer: eine
            // Indexverschiebung koennte sonst das falsche Bild oeffnen.
            trigger.addEventListener('click', () => openShotFor(p));

            // Absicht erkennen: Maus darueber bzw. Finger drauf -> grosse
            // Fassung schon laden, bis zum Klick ist sie meist da
            const warm = () => preload(isVideo ? p.src : (p.full || p.src));
            trigger.addEventListener('pointerenter', warm, { once: true });
            trigger.addEventListener('touchstart', warm, { once: true, passive: true });
        }

        const delBtn = card.querySelector('.tb2-del');
        if (delBtn) delBtn.addEventListener('click', () => deletePhoto(p));

        els.feedBody.appendChild(card);
    }

    /**
     * Foto/Video endgueltig loeschen (nur Admins) und ohne Neuladen aus Feed,
     * Lightbox und Zaehlern entfernen.
     */
    function deletePhoto(p) {
        if (!cfg.deleteUrl) return;
        const what = p.is_video ? 'Video' : 'Foto';
        if (!window.confirm(`${what} vom ${p.taken_at || 'unbekannten Datum'} endgültig löschen?`)) return;

        const body = new FormData();
        body.append('id', p.id);

        fetch(cfg.deleteUrl, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': cfg.csrf },
            body,
        })
            .then((r) => r.json().catch(() => ({})).then((d) => ({ ok: r.ok, d })))
            .then(({ ok, d }) => {
                if (!ok || d.status !== 'ok') throw new Error(d.message || 'Löschen fehlgeschlagen');
                removePhoto(p);
            })
            .catch((err) => window.alert(err.message));
    }

    function removePhoto(p) {
        const card = els.feedBody.querySelector(`.tb2-photo[data-id="${p.id}"]`);
        if (card) card.remove();

        // Die Feed-Seiten zaehlen per Offset; ein geloeschtes Foto der gerade
        // nachladenden Reise wuerde sonst beim naechsten Nachladen eins
        // ueberspringen.
        if (p.galleryId === state.gallery && state.offset > 0) state.offset -= 1;

        const c = countries.find((x) => x.gallery_id === p.galleryId);
        if (c) {
            c.pic_count = Math.max(0, (c.pic_count || 1) - 1);
            if (els.feedTitle.textContent === c.name) els.feedSub.textContent = `${c.code} // ${c.pic_count} Fotos`;
            const num = root.querySelector(`.tb2-country[data-gallery="${c.gallery_id}"] .tb2-num`);
            if (num) num.textContent = c.pic_count;
        }

        const i = shots.findIndex((s) => s.id === p.id);
        if (i < 0) return;
        shots.splice(i, 1);

        if (shotOpen()) {
            if (!shots.length) { closeShot(); return; }
            if (shotIndex >= shots.length) shotIndex = shots.length - 1;
            else if (i < shotIndex) shotIndex -= 1;
            renderShot();
        }
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
        }, { root: els.feedBody, rootMargin: '1200px' });  // frueher nachladen

        observer.observe(s);
    }

    function escapeHtml(str) {
        return String(str == null ? '' : str).replace(/[&<>"']/g, (ch) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
        }[ch]));
    }
})();
