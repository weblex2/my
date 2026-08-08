/* ==========================================================================
   netnexus.js — Steuerung fuer die HUD-Seite /2 (view: index2)
   Eigenstaendig geschrieben, keine Fremdbibliotheken. Laeuft nur, wenn
   .nn-root im DOM ist, damit andere Seiten unberuehrt bleiben.
   ========================================================================== */

(function () {
    'use strict';

    const root = document.querySelector('.nn-root');
    if (!root) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------------------------------------------------------------------
       Uhr
       --------------------------------------------------------------------- */
    const clockEl = root.querySelector('[data-nn-clock]');

    function tickClock() {
        if (!clockEl) return;
        const d = new Date();
        const p = (n) => String(n).padStart(2, '0');
        clockEl.textContent = `${p(d.getHours())}:${p(d.getMinutes())}:${p(d.getSeconds())}`;
    }

    tickClock();
    setInterval(tickClock, 1000);

    /* ---------------------------------------------------------------------
       Boot-Zeile: tippt eine Meldung, wartet, nimmt die naechste
       --------------------------------------------------------------------- */
    const bootEl = root.querySelector('[data-nn-boot]');

    if (bootEl) {
        const messages = [
            'link etabliert // handshake ok',
            'speichermodule geladen // integritaet 100%',
            'sektor 07 gescannt // keine anomalien',
            'bereit fuer eingabe',
        ];

        const caret = document.createElement('span');
        caret.className = 'nn-caret';
        caret.textContent = ' ';

        const textNode = document.createTextNode('');
        bootEl.textContent = '';
        bootEl.append(textNode, caret);

        if (reduceMotion) {
            textNode.nodeValue = '> ' + messages[messages.length - 1];
        } else {
            let msgIndex = 0;
            let charIndex = 0;
            let holding = 0;

            const step = () => {
                const current = messages[msgIndex];

                if (holding > 0) {
                    holding -= 1;
                } else if (charIndex <= current.length) {
                    textNode.nodeValue = '> ' + current.slice(0, charIndex);
                    charIndex += 1;
                    if (charIndex > current.length) holding = 34;
                } else {
                    msgIndex = (msgIndex + 1) % messages.length;
                    charIndex = 0;
                }

                setTimeout(step, 55);
            };

            step();
        }
    }

    /* ---------------------------------------------------------------------
       Karussell
       --------------------------------------------------------------------- */
    const cards = Array.from(root.querySelectorAll('.memory-card'));
    const prevBtn = root.querySelector('#prev-btn');
    const nextBtn = root.querySelector('#next-btn');
    const counterEl = root.querySelector('[data-nn-counter]');
    const dotsWrap = root.querySelector('[data-nn-dots]');

    let index = 0;

    if (cards.length) {
        // Punkte zum Direktsprung
        if (dotsWrap) {
            cards.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'nn-dot';
                dot.setAttribute('aria-label', `Karte ${i + 1} anzeigen`);
                dot.addEventListener('click', () => goTo(i));
                dotsWrap.appendChild(dot);
            });
        }

        // Klick dreht die aktive Karte um
        cards.forEach((card, i) => {
            card.addEventListener('click', () => {
                if (i === index) {
                    card.classList.toggle('nn-flipped');
                } else {
                    goTo(i);
                }
            });

            const hint = document.createElement('span');
            hint.className = 'nn-flip-hint';
            hint.textContent = '[ klicken zum umdrehen ]';
            const front = card.querySelector('.card-front');
            if (front) front.appendChild(hint);
        });

        render();
    }

    function render() {
        const total = cards.length;

        cards.forEach((card, i) => {
            // kuerzester Abstand auf dem Ring
            let offset = i - index;
            if (offset > total / 2) offset -= total;
            if (offset < -total / 2) offset += total;

            const abs = Math.abs(offset);
            const visible = abs <= 2;

            card.classList.toggle('nn-hidden', !visible);
            card.classList.toggle('nn-active', offset === 0);
            if (offset !== 0) card.classList.remove('nn-flipped');

            card.style.transform =
                `translateX(${offset * 210}px) ` +
                `translateZ(${-abs * 190}px) ` +
                `rotateY(${offset * -22}deg) ` +
                `scale(${1 - abs * 0.08})`;
            card.style.zIndex = String(100 - abs);
            card.style.opacity = visible ? String(1 - abs * 0.28) : '0';
            card.setAttribute('aria-hidden', offset === 0 ? 'false' : 'true');
        });

        if (counterEl) {
            counterEl.innerHTML =
                `<b>${String(index + 1).padStart(2, '0')}</b> / ${String(total).padStart(2, '0')}`;
        }

        if (dotsWrap) {
            Array.from(dotsWrap.children).forEach((dot, i) => {
                dot.classList.toggle('is-active', i === index);
            });
        }
    }

    function goTo(i) {
        const total = cards.length;
        if (!total) return;
        index = ((i % total) + total) % total;
        render();
    }

    function next() { goTo(index + 1); }
    function prev() { goTo(index - 1); }

    if (nextBtn) nextBtn.addEventListener('click', next);
    if (prevBtn) prevBtn.addEventListener('click', prev);

    // Tastatur
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') { next(); }
        else if (e.key === 'ArrowLeft') { prev(); }
        else if (e.key === ' ' && cards[index]) {
            e.preventDefault();
            cards[index].classList.toggle('nn-flipped');
        }
    });

    // Wischen auf Touch
    let touchX = null;
    root.addEventListener('touchstart', (e) => {
        touchX = e.changedTouches[0].clientX;
    }, { passive: true });

    root.addEventListener('touchend', (e) => {
        if (touchX === null) return;
        const delta = e.changedTouches[0].clientX - touchX;
        if (Math.abs(delta) > 45) { delta < 0 ? next() : prev(); }
        touchX = null;
    }, { passive: true });

    /* ---------------------------------------------------------------------
       Partikel im Hintergrund
       --------------------------------------------------------------------- */
    const canvas = root.querySelector('[data-nn-particles]');

    if (canvas && !reduceMotion) {
        const ctx = canvas.getContext('2d');
        let particles = [];
        let raf = null;

        function resize() {
            const dpr = Math.min(window.devicePixelRatio || 1, 2);
            canvas.width = window.innerWidth * dpr;
            canvas.height = window.innerHeight * dpr;
            canvas.style.width = window.innerWidth + 'px';
            canvas.style.height = window.innerHeight + 'px';
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

            const count = Math.min(90, Math.round(window.innerWidth / 16));
            particles = Array.from({ length: count }, () => ({
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight,
                r: Math.random() * 1.6 + 0.4,
                vx: (Math.random() - 0.5) * 0.16,
                vy: (Math.random() - 0.5) * 0.16,
                a: Math.random() * 0.5 + 0.15,
            }));
        }

        function draw() {
            ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);

            for (const p of particles) {
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0) p.x = window.innerWidth;
                if (p.x > window.innerWidth) p.x = 0;
                if (p.y < 0) p.y = window.innerHeight;
                if (p.y > window.innerHeight) p.y = 0;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(0, 229, 255, ${p.a})`;
                ctx.fill();
            }

            raf = requestAnimationFrame(draw);
        }

        resize();
        draw();
        window.addEventListener('resize', resize);

        // bei verstecktem Tab nicht weiterrechnen
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                if (raf) { cancelAnimationFrame(raf); raf = null; }
            } else if (!raf) {
                draw();
            }
        });
    }
})();
