<x-noppal>
    @vite([
        'resources/css/netnexus.css',
        'resources/js/netnexus.js',
        'resources/css/travelblog2.css',
    ])

    <div class="nn-root tb2">
        <div class="nn-grid" aria-hidden="true"></div>
        <div class="nn-scanlines" aria-hidden="true"></div>
        <div class="nn-vignette" aria-hidden="true"></div>

        <div class="nn-shell">

            <header class="nn-header">
                <h1 class="nn-brand">
                    UPLOAD
                    <span class="nn-sub">// fotos einspeisen</span>
                </h1>

                <div class="nn-readouts">
                    <div class="nn-readout">
                        <b>modus</b>
                        <span class="nn-status">auto-zuordnung</span>
                    </div>
                </div>
            </header>

            <section class="nn-section">
                <h2 class="nn-section-title"><span class="nn-idx">01</span> Dateien wählen</h2>

                <div class="tb2-drop" id="tb2-drop">
                    <b>Fotos hierher ziehen</b>
                    <span>oder klicken zum Auswählen &nbsp;·&nbsp; JPG, PNG, HEIC, MP4, MOV</span>
                    <input type="file" id="tb2-input" multiple accept="image/*,video/*" hidden>
                </div>

                <div class="tb2-actions">
                    <button type="button" class="tb2-btn" id="tb2-start" disabled>Hochladen</button>
                    <button type="button" class="tb2-btn" id="tb2-clear" disabled>Liste leeren</button>
                    <a class="tb2-btn" href="{{ route('tb2.index') }}">Zur Karte</a>
                </div>

                <p class="nn-bootline" id="tb2-status" style="margin-top:1rem"></p>

                <div class="tb2-queue" id="tb2-queue"></div>
            </section>

            <footer class="nn-footer">
                <span>
                    Land und Ort werden aus den EXIF-Daten bestimmt.
                    Fotos ohne GPS oder Zeitstempel werden abgelehnt.
                </span>
            </footer>

        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        'use strict';

        const drop   = document.getElementById('tb2-drop');
        const input  = document.getElementById('tb2-input');
        const queue  = document.getElementById('tb2-queue');
        const start  = document.getElementById('tb2-start');
        const clear  = document.getElementById('tb2-clear');
        const status = document.getElementById('tb2-status');

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url   = @json(route('tb2.store'));

        let items = [];

        drop.addEventListener('click', () => input.click());

        ['dragenter', 'dragover'].forEach((ev) =>
            drop.addEventListener(ev, (e) => { e.preventDefault(); drop.classList.add('is-over'); }));

        ['dragleave', 'drop'].forEach((ev) =>
            drop.addEventListener(ev, (e) => { e.preventDefault(); drop.classList.remove('is-over'); }));

        drop.addEventListener('drop', (e) => add(e.dataTransfer.files));
        input.addEventListener('change', () => add(input.files));

        function add(fileList) {
            Array.from(fileList).forEach((file) => {
                const card = document.createElement('div');
                card.className = 'tb2-item';

                const img = document.createElement('img');
                if (file.type.startsWith('image/')) {
                    img.src = URL.createObjectURL(file);
                    img.onload = () => URL.revokeObjectURL(img.src);
                }
                card.appendChild(img);

                const bar = document.createElement('div');
                bar.className = 'tb2-bar';
                bar.innerHTML = '<i></i>';
                card.appendChild(bar);

                const state = document.createElement('div');
                state.className = 'tb2-item-state';
                state.textContent = file.name;
                card.appendChild(state);

                queue.appendChild(card);
                items.push({ file, card, state, bar: bar.querySelector('i'), done: false });
            });

            start.disabled = items.length === 0;
            clear.disabled = items.length === 0;
            status.textContent = `> ${items.length} Datei(en) bereit`;
        }

        clear.addEventListener('click', () => {
            items = [];
            queue.innerHTML = '';
            start.disabled = true;
            clear.disabled = true;
            status.textContent = '';
        });

        start.addEventListener('click', async () => {
            start.disabled = true;
            clear.disabled = true;

            let ok = 0, fail = 0;

            for (const item of items) {
                if (item.done) continue;
                status.textContent = `> lade ${item.file.name} …`;

                try {
                    const res = await upload(item);
                    item.card.classList.add('is-ok');
                    item.state.textContent = [res.country, res.gallery_name, res.mappoint_name]
                        .filter(Boolean).join(' · ') || 'OK';
                    ok += 1;
                } catch (msg) {
                    item.card.classList.add('is-error');
                    item.state.textContent = msg;
                    fail += 1;
                }

                item.done = true;

                // Nominatim erlaubt nur 1 Anfrage pro Sekunde
                await new Promise((r) => setTimeout(r, 1100));
            }

            status.textContent = `> fertig — ${ok} übernommen, ${fail} abgelehnt`;
            clear.disabled = false;
        });

        function upload(item) {
            return new Promise((resolve, reject) => {
                const fd = new FormData();
                fd.append('file', item.file);
                fd.append('auto_assign', '1');
                fd.append('_token', token);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', url);
                xhr.setRequestHeader('X-CSRF-TOKEN', token);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable) {
                        item.bar.style.width = Math.round((e.loaded / e.total) * 100) + '%';
                    }
                };

                xhr.onload = () => {
                    let data = {};
                    try { data = JSON.parse(xhr.responseText); } catch (e) {}

                    if (xhr.status === 200 && data.status === 'ok') {
                        item.bar.style.width = '100%';
                        resolve(data);
                    } else {
                        reject(data.message || `Fehler ${xhr.status}`);
                    }
                };

                xhr.onerror = () => reject('Netzwerkfehler');
                xhr.send(fd);
            });
        }
    })();
    </script>
    @endpush
</x-noppal>
