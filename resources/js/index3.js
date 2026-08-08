/* ==========================================================================
   index3.js — Zusatzinteraktion fuer /index3
   Uhr, Boot-Zeile und Partikel kommen weiterhin aus netnexus.js.
   Hier nur: Radar unten links, Karten-Neigung per Maus, Scroll-Markierung.
   ========================================================================== */

(function () {
    'use strict';

    const root = document.querySelector('.nn-root.i3');
    if (!root) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------------------------------------------------------------------
       Karten kippen der Maus hinterher
       --------------------------------------------------------------------- */
    const MAX_TILT = 9; // Grad

    root.querySelectorAll('.i3-tilt').forEach((wrap) => {
        const card = wrap.querySelector('.index-card');
        if (!card) return;

        // Glanzlicht-Ebene einhaengen, statt die gemeinsame Komponente zu aendern
        const sheen = document.createElement('span');
        sheen.className = 'i3-sheen';
        sheen.setAttribute('aria-hidden', 'true');
        card.appendChild(sheen);

        let frame = null;

        function apply(x, y) {
            const rect = card.getBoundingClientRect();
            const px = (x - rect.left) / rect.width;
            const py = (y - rect.top) / rect.height;

            card.style.setProperty('--i3-mx', (px * 100).toFixed(1) + '%');
            card.style.setProperty('--i3-my', (py * 100).toFixed(1) + '%');

            if (reduceMotion) return;

            const ry = (px - 0.5) * 2 * MAX_TILT;
            const rx = -(py - 0.5) * 2 * MAX_TILT;
            card.style.transform =
                `perspective(900px) rotateX(${rx.toFixed(2)}deg) ` +
                `rotateY(${ry.toFixed(2)}deg) translateZ(14px)`;
        }

        wrap.addEventListener('mousemove', (e) => {
            const { clientX, clientY } = e;
            if (frame) cancelAnimationFrame(frame);
            frame = requestAnimationFrame(() => apply(clientX, clientY));
        });

        wrap.addEventListener('mouseenter', () => {
            card.style.transition = '';
        });

        wrap.addEventListener('mouseleave', () => {
            if (frame) { cancelAnimationFrame(frame); frame = null; }
            card.style.transition = 'transform 0.5s cubic-bezier(0.2, 0.8, 0.3, 1)';
            card.style.transform = '';
            setTimeout(() => { card.style.transition = ''; }, 520);
        });
    });

    /* ---------------------------------------------------------------------
       Radar unten links
       --------------------------------------------------------------------- */
    const canvas = root.querySelector('[data-i3-radar]');

    if (canvas && !reduceMotion) {
        const ctx = canvas.getContext('2d');
        const SIZE = 132;
        const dpr = Math.min(window.devicePixelRatio || 1, 2);

        canvas.width = SIZE * dpr;
        canvas.height = SIZE * dpr;
        ctx.scale(dpr, dpr);

        const cx = SIZE / 2;
        const cy = SIZE / 2;
        const R = SIZE / 2 - 6;

        // Streuobjekte, die der Sweep aufleuchten laesst
        const blips = Array.from({ length: 7 }, () => ({
            ang: Math.random() * Math.PI * 2,
            dist: 0.25 + Math.random() * 0.68,
            life: 0,
        }));

        const countEl = root.querySelector('[data-i3-radar-count]');
        let sweep = 0;
        let raf = null;

        function draw() {
            ctx.clearRect(0, 0, SIZE, SIZE);

            // Ringe
            ctx.strokeStyle = 'rgba(0, 229, 255, 0.28)';
            ctx.lineWidth = 1;
            [0.34, 0.67, 1].forEach((f) => {
                ctx.beginPath();
                ctx.arc(cx, cy, R * f, 0, Math.PI * 2);
                ctx.stroke();
            });

            // Fadenkreuz
            ctx.strokeStyle = 'rgba(0, 229, 255, 0.16)';
            ctx.beginPath();
            ctx.moveTo(cx - R, cy); ctx.lineTo(cx + R, cy);
            ctx.moveTo(cx, cy - R); ctx.lineTo(cx, cy + R);
            ctx.stroke();

            // Sweep als nachlaufender Faecher
            const STEPS = 26;
            const step = 0.055;
            for (let i = 0; i < STEPS; i++) {
                ctx.beginPath();
                ctx.moveTo(cx, cy);
                ctx.arc(cx, cy, R, sweep - (i + 1) * step, sweep - i * step);
                ctx.closePath();
                ctx.fillStyle = `rgba(0, 229, 255, ${0.15 * (1 - i / STEPS)})`;
                ctx.fill();
            }

            // Vordere Kante des Sweeps
            ctx.strokeStyle = 'rgba(0, 229, 255, 0.85)';
            ctx.lineWidth = 1.4;
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.lineTo(cx + Math.cos(sweep) * R, cy + Math.sin(sweep) * R);
            ctx.stroke();

            // Blips
            let visible = 0;
            for (const b of blips) {
                const d = (((sweep - b.ang) % (Math.PI * 2)) + Math.PI * 2) % (Math.PI * 2);
                if (d < 0.09) b.life = 1;

                if (b.life > 0) {
                    b.life -= 0.006;
                    visible += 1;

                    const bx = cx + Math.cos(b.ang) * R * b.dist;
                    const by = cy + Math.sin(b.ang) * R * b.dist;
                    const a = Math.max(0, b.life);

                    ctx.beginPath();
                    ctx.arc(bx, by, 2.4, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255, 184, 77, ${a})`;
                    ctx.fill();

                    ctx.beginPath();
                    ctx.arc(bx, by, 5.5, 0, Math.PI * 2);
                    ctx.strokeStyle = `rgba(255, 184, 77, ${a * 0.35})`;
                    ctx.lineWidth = 1;
                    ctx.stroke();
                }
            }

            if (countEl) countEl.textContent = String(visible).padStart(2, '0');

            sweep += 0.016;
            if (sweep > Math.PI * 2) sweep -= Math.PI * 2;

            raf = requestAnimationFrame(draw);
        }

        draw();

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                if (raf) { cancelAnimationFrame(raf); raf = null; }
            } else if (!raf) {
                draw();
            }
        });
    }

    /* ---------------------------------------------------------------------
       Schiene links: aktiven Abschnitt markieren
       --------------------------------------------------------------------- */
    const marks = Array.from(root.querySelectorAll('.i3-rail-mark'));
    const sections = marks
        .map((m) => document.querySelector(m.getAttribute('href')))
        .filter(Boolean);

    if (sections.length && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const i = sections.indexOf(entry.target);
                marks.forEach((m, j) => m.classList.toggle('is-active', j === i));
            });
        }, { rootMargin: '-45% 0px -45% 0px' });

        sections.forEach((s) => io.observe(s));
    }
})();
