<x-gallery-layout>
    <x-slot name="header">
        <h2 class="font-semibold leading-tight">
            <a href="/travel-blog/index3">Travel Map</a> / Fotos hochladen
        </h2>
    </x-slot>

    <style>
        /* ── Override layout background to match index3 ── */
        #main { overflow: hidden !important; }

        .up-shell {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #06060d;
            color: #e2e8f0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* ── Scrollable top area ── */
        .up-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 28px 32px 16px;
            scrollbar-width: thin;
            scrollbar-color: rgba(249,115,22,0.25) transparent;
            min-height: 0; /* critical for flex child scroll */
        }

        /* ── Page title ── */
        .up-title { font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 3px; }
        .up-sub   { font-size: 12px; color: #475569; margin-bottom: 24px; }

        /* ── Drop Zone ── */
        .drop-zone {
            border: 2px dashed rgba(249,115,22,0.3);
            border-radius: 14px;
            background: rgba(249,115,22,0.02);
            padding: 36px 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 16px;
        }
        .drop-zone:hover, .drop-zone.over {
            border-color: #f97316;
            background: rgba(249,115,22,0.05);
        }
        .drop-zone .dz-icon { font-size: 36px; margin-bottom: 10px; }
        .drop-zone .dz-text { font-size: 14px; color: #94a3b8; }
        .drop-zone .dz-text strong { color: #f97316; }
        .drop-zone .dz-hint { font-size: 11px; color: #334155; margin-top: 5px; }

        /* ── Manual fallback ── */
        .manual-toggle {
            display: flex; align-items: center; gap: 8px;
            font-size: 12px; color: #334155; cursor: pointer;
            user-select: none; margin-bottom: 10px;
            transition: color 0.15s;
        }
        .manual-toggle:hover { color: #64748b; }

        .manual-section {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 16px;
        }
        .manual-section.hidden { display: none; }

        .form-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .form-label { display: block; font-size: 11px; font-weight: 700;
                      color: #f97316; text-transform: uppercase;
                      letter-spacing: 0.5px; margin-bottom: 5px; }
        .form-sel {
            width: 100%; padding: 8px 12px;
            background: #0d1117; border: 1px solid rgba(255,255,255,0.07);
            border-radius: 8px; color: #e2e8f0; font-size: 13px; outline: none;
            transition: border-color 0.15s;
        }
        .form-sel:focus { border-color: rgba(249,115,22,0.4); }

        /* ── Queue ── */
        .queue { display: flex; flex-direction: column; gap: 8px; }

        .q-card {
            display: flex; align-items: center; gap: 12px;
            background: #0d1117;
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 11px;
            padding: 10px 14px;
            transition: border-color 0.2s;
        }
        .q-card.done  { border-color: rgba(34,197,94,0.25); }
        .q-card.error { border-color: rgba(239,68,68,0.3); }

        .q-thumb {
            width: 48px; height: 48px;
            border-radius: 8px; object-fit: cover;
            background: #111827; flex-shrink: 0;
        }

        .q-info { flex: 1; min-width: 0; }
        .q-name { font-size: 13px; font-weight: 600; color: #e2e8f0;
                  white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .q-size { font-size: 11px; color: #334155; margin-top: 1px; }

        .q-prog-wrap { height: 2px; background: #1e293b; border-radius: 1px; margin-top: 6px; }
        .q-prog-bar  { height: 2px; background: #f97316; border-radius: 1px;
                       width: 0%; transition: width 0.2s; }
        .q-prog-bar.done  { background: #22c55e; width: 100% !important; }
        .q-prog-bar.error { background: #ef4444; width: 100% !important; }

        .q-result { margin-top: 5px; font-size: 11px; display: flex; gap: 6px; flex-wrap: wrap; }
        .q-tag {
            background: rgba(249,115,22,0.08);
            border: 1px solid rgba(249,115,22,0.15);
            color: #f97316; border-radius: 4px; padding: 1px 7px;
        }
        .q-err { color: #ef4444; font-size: 11px; }

        .q-status {
            flex-shrink: 0; font-size: 12px; font-weight: 600;
            padding: 3px 10px; border-radius: 20px;
            white-space: nowrap;
        }
        .q-status.waiting  { color: #334155; background: rgba(51,65,85,0.15); }
        .q-status.loading  { color: #f97316; background: rgba(249,115,22,0.1); }
        .q-status.done     { color: #22c55e; background: rgba(34,197,94,0.1); }
        .q-status.error    { color: #ef4444; background: rgba(239,68,68,0.1); }
        .q-status.manual   { color: #f59e0b; background: rgba(245,158,11,0.1); }

        /* ── Inline manual assignment (needs_manual cards) ── */
        .q-card.needs-manual { border-color: rgba(245,158,11,0.35); }
        .q-assign {
            margin-top: 8px;
            display: flex; gap: 6px; flex-wrap: wrap; align-items: center;
        }
        .q-assign .form-sel {
            padding: 5px 8px; font-size: 12px; flex: 1; min-width: 120px;
        }
        .btn-assign {
            padding: 5px 12px;
            background: #f59e0b; color: #000;
            border: none; border-radius: 6px;
            font-size: 12px; font-weight: 700;
            cursor: pointer; white-space: nowrap;
            flex-shrink: 0;
        }
        .btn-assign:hover    { background: #d97706; }
        .btn-assign:disabled { background: #1e293b; color: #334155; cursor: not-allowed; }

        /* ── Sticky action bar (bottom) ── */
        .up-actions {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 32px;
            background: rgba(6,6,13,0.97);
            border-top: 1px solid rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
        }

        .btn-upload {
            padding: 10px 26px;
            background: #f97316; color: #fff;
            border: none; border-radius: 9px;
            font-size: 14px; font-weight: 700;
            cursor: pointer; transition: background 0.15s;
            display: flex; align-items: center; gap: 8px;
            white-space: nowrap;
        }
        .btn-upload:hover    { background: #ea6c0a; }
        .btn-upload:disabled { background: #1e293b; color: #334155; cursor: not-allowed; }

        .btn-clear {
            padding: 10px 18px;
            background: transparent; color: #475569;
            border: 1px solid rgba(255,255,255,0.07); border-radius: 9px;
            font-size: 13px; cursor: pointer; transition: all 0.15s;
            white-space: nowrap;
        }
        .btn-clear:hover { border-color: rgba(255,255,255,0.15); color: #94a3b8; }

        .up-summary { font-size: 12px; color: #334155; }
        .up-summary a { color: #f97316; text-decoration: none; font-weight: 600; }
        .up-summary a:hover { text-decoration: underline; }

        .up-count {
            margin-left: auto;
            font-size: 12px; color: #334155;
            white-space: nowrap;
        }

        /* Spinner */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner {
            width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,0.25);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            flex-shrink: 0;
        }
    </style>

    <div class="up-shell">

        {{-- Scrollable content area --}}
        <div class="up-scroll">

            <h1 class="up-title">Fotos & Videos hochladen</h1>
            <p class="up-sub">
                GPS-Daten und Zeitstempel werden automatisch ausgelesen.
                Reise und Ort werden anhand dieser Daten automatisch zugeordnet.
                Maximale Reisedauer: 6 Wochen.
            </p>

            {{-- Drop Zone --}}
            <div class="drop-zone" id="drop-zone"
                 onclick="document.getElementById('file-input').click()">
                <input type="file" id="file-input" multiple accept="image/*,video/*"
                       class="hidden" onchange="addFiles(this.files)">
                <div class="dz-icon">📸</div>
                <p class="dz-text">Hierher ziehen oder <strong>klicken zum Auswählen</strong></p>
                <p class="dz-hint">JPG · PNG · MOV · MP4 — mehrere Dateien gleichzeitig möglich</p>
            </div>

            {{-- Fallback: Manuell zuordnen --}}
            <div class="manual-toggle" onclick="toggleManual()">
                <span id="manual-arrow" style="font-size:10px">▶</span>
                Manuell zuordnen (z.B. wenn kein GPS im Foto vorhanden)
            </div>
            <div class="manual-section hidden" id="manual-section">
                <div class="form-row">
                    <div>
                        <label class="form-label">Reise / Gallery</label>
                        <select id="sel-gallery" class="form-sel"
                                onchange="loadMappoints(this.value)">
                            <option value="">-- Reise wählen --</option>
                            @foreach($galleries as $g)
                                <option value="{{ $g->id }}" data-code="{{ $g->code }}">
                                    {{ $g->name }} ({{ $g->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Ort / Mappoint</label>
                        <select id="sel-mappoint" class="form-sel">
                            <option value="">-- Ort wählen --</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Upload Queue --}}
            <div class="queue" id="queue"></div>

        </div>

        {{-- Sticky Action Bar --}}
        <div class="up-actions">
            <button class="btn-upload" id="btn-upload" onclick="startUpload()" disabled>
                <span id="btn-icon">⬆</span>
                <span id="btn-text">Alle hochladen</span>
            </button>
            <button class="btn-clear" id="btn-clear" onclick="clearQueue()"
                    style="display:none">
                Auswahl löschen
            </button>
            <span class="up-summary" id="summary"></span>
            <span class="up-count" id="count"></span>
        </div>

    </div>

    <script>
    var files     = [];
    var uploading = false;
    var manOpen   = false;
    var csrf      = document.querySelector('meta[name="csrf-token"]').content;

    // ── Drag & Drop ──
    var dz = document.getElementById('drop-zone');
    dz.addEventListener('dragover',  function(e) { e.preventDefault(); dz.classList.add('over'); });
    dz.addEventListener('dragleave', function()  { dz.classList.remove('over'); });
    dz.addEventListener('drop', function(e) {
        e.preventDefault(); dz.classList.remove('over');
        addFiles(e.dataTransfer.files);
    });

    function addFiles(fileList) {
        Array.from(fileList).forEach(function(f) { files.push(f); });
        renderQueue();
    }

    function renderQueue() {
        var q = document.getElementById('queue');
        q.innerHTML = '';
        files.forEach(function(f, i) {
            var size = (f.size / 1024 / 1024).toFixed(1) + ' MB';
            var card = document.createElement('div');
            card.className = 'q-card'; card.id = 'qc-' + i;
            card.innerHTML =
                '<img class="q-thumb" id="qt-' + i + '" src="">' +
                '<div class="q-info">' +
                  '<div class="q-name">' + esc(f.name) + '</div>' +
                  '<div class="q-size">' + size + '</div>' +
                  '<div class="q-prog-wrap"><div class="q-prog-bar" id="qp-' + i + '"></div></div>' +
                  '<div class="q-result" id="qr-' + i + '"></div>' +
                '</div>' +
                '<span class="q-status waiting" id="qs-' + i + '">Bereit</span>';
            q.appendChild(card);

            if (f.type.startsWith('image/')) {
                var rd = new FileReader();
                rd.onload = (function(idx) { return function(e) {
                    var el = document.getElementById('qt-' + idx);
                    if (el) el.src = e.target.result;
                }; })(i);
                rd.readAsDataURL(f);
            } else {
                document.getElementById('qt-' + i).src = '/img/card-reverb.png';
            }
        });

        var hasFiles = files.length > 0;
        document.getElementById('btn-upload').disabled = !hasFiles;
        document.getElementById('btn-clear').style.display = hasFiles ? 'inline-flex' : 'none';
        document.getElementById('count').textContent = hasFiles ? files.length + ' Datei(en)' : '';
        document.getElementById('summary').textContent = '';
    }

    function clearQueue() {
        files = [];
        document.getElementById('queue').innerHTML = '';
        document.getElementById('btn-upload').disabled = true;
        document.getElementById('btn-clear').style.display = 'none';
        document.getElementById('count').textContent = '';
        document.getElementById('summary').textContent = '';
        document.getElementById('file-input').value = '';
    }

    // ── Manual toggle ──
    function toggleManual() {
        manOpen = !manOpen;
        document.getElementById('manual-section').classList.toggle('hidden', !manOpen);
        document.getElementById('manual-arrow').textContent = manOpen ? '▼' : '▶';
    }

    function loadMappoints(galleryId) {
        if (!galleryId) return;
        fetch('/travel-blog/map-data/' + galleryId)
            .then(function(r) { return r.json(); })
            .then(function(d) {
                var sel = document.getElementById('sel-mappoint');
                sel.innerHTML = '<option value="">-- Ort wählen --</option>';
                (d.mappoints || []).forEach(function(mp) {
                    sel.innerHTML += '<option value="' + mp.id + '">' + esc(mp.name) + '</option>';
                });
            });
    }

    // ── Upload ──
    async function startUpload() {
        if (uploading || !files.length) return;
        uploading = true;

        var btn = document.getElementById('btn-upload');
        btn.disabled = true;
        btn.innerHTML = '<div class="spinner"></div><span>Lädt hoch...</span>';
        document.getElementById('btn-clear').style.display = 'none';

        var done = 0, errors = 0;

        for (var i = 0; i < files.length; i++) {
            setStatus(i, 'loading', '⬆ Lädt...');
            var res = await uploadOne(files[i], i);
            if (res.ok) {
                if (res.data.status === 'needs_manual') {
                    setStatus(i, 'manual', '⚠ Kein GPS');
                    setBar(i, 'done');
                    document.getElementById('qc-' + i).classList.add('needs-manual');
                    showAssignForm(i, res.data.pic_id);
                } else {
                    done++;
                    setStatus(i, 'done', '✓ OK');
                    setBar(i, 'done');
                    if (res.data.gallery_name) showTags(i, res.data);
                }
            } else {
                errors++;
                setStatus(i, 'error', '✗');
                setBar(i, 'error');
                document.getElementById('qr-' + i).innerHTML =
                    '<span class="q-err">' + esc(res.message) + '</span>';
            }
        }

        uploading = false;
        btn.disabled = false;
        btn.innerHTML = '<span>⬆</span><span>Nochmal hochladen</span>';

        var s = document.getElementById('summary');
        s.innerHTML = done + ' hochgeladen' +
            (errors ? ', <span style="color:#ef4444">' + errors + ' Fehler</span>' : '') +
            ' &nbsp;— <a href="/travel-blog/index3">Zur Karte ›</a>';
    }

    function uploadOne(file, idx) {
        return new Promise(function(resolve) {
            var useManual = manOpen
                && document.getElementById('sel-gallery').value
                && document.getElementById('sel-mappoint').value;

            var fd = new FormData();
            fd.append('_token', csrf);
            fd.append('file', file);
            fd.append('content', '');

            if (useManual) {
                var opt = document.getElementById('sel-gallery')
                              .options[document.getElementById('sel-gallery').selectedIndex];
                fd.append('country_code', opt.dataset.code || '');
                fd.append('mappoint_id',  document.getElementById('sel-mappoint').value);
                fd.append('auto_assign',  '0');
            } else {
                fd.append('auto_assign',  '1');
                fd.append('country_code', '');
                fd.append('mappoint_id',  '');
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/travel-blog/store-bulk');

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    var pct = Math.round(e.loaded / e.total * 95);
                    document.getElementById('qp-' + idx).style.width = pct + '%';
                }
            };

            xhr.onload = function() {
                try {
                    var data = JSON.parse(xhr.responseText);
                    resolve(xhr.status === 200
                        ? { ok: true, data: data }
                        : { ok: false, message: data.message || 'Server-Fehler (' + xhr.status + ')' }
                    );
                } catch(e) {
                    resolve({ ok: false, message: 'Ungültige Serverantwort' });
                }
            };
            xhr.onerror = function() { resolve({ ok: false, message: 'Verbindungsfehler' }); };
            xhr.send(fd);
        });
    }

    function setStatus(i, cls, text) {
        var el = document.getElementById('qs-' + i);
        el.className = 'q-status ' + cls;
        el.textContent = text;
    }
    function setBar(i, cls) {
        document.getElementById('qp-' + i).classList.add(cls);
        document.getElementById('qc-' + i).classList.add(cls);
    }
    function showTags(i, data) {
        var tags = '';
        if (data.country_code) tags += '<span class="q-tag">' + esc(data.country_code) + '</span>';
        if (data.gallery_name)  tags += '<span class="q-tag">' + esc(data.gallery_name)  + '</span>';
        if (data.mappoint_name) tags += '<span class="q-tag">📍 ' + esc(data.mappoint_name) + '</span>';
        document.getElementById('qr-' + i).innerHTML = tags;
    }
    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── Inline manual assignment for needs_manual cards ──
    var galleries = @json($galleries);

    function showAssignForm(idx, picId) {
        var rDiv = document.getElementById('qr-' + idx);
        var gOpts = '<option value="">-- Reise --</option>';
        galleries.forEach(function(g) {
            gOpts += '<option value="' + g.id + '" data-code="' + esc(g.code) + '">' + esc(g.name) + ' (' + esc(g.code) + ')</option>';
        });
        rDiv.innerHTML =
            '<div class="q-assign" id="qa-' + idx + '">' +
              '<select class="form-sel" id="qa-gal-' + idx + '" onchange="loadQMappoints(' + idx + ',this.value)">' + gOpts + '</select>' +
              '<select class="form-sel" id="qa-mp-' + idx + '"><option value="">-- Ort --</option></select>' +
              '<button class="btn-assign" onclick="assignPic(' + idx + ',' + picId + ')">Zuordnen</button>' +
            '</div>';
    }

    function loadQMappoints(idx, galleryId) {
        var sel = document.getElementById('qa-mp-' + idx);
        sel.innerHTML = '<option value="">-- Ort --</option>';
        if (!galleryId) return;
        fetch('/travel-blog/map-data/' + galleryId)
            .then(function(r) { return r.json(); })
            .then(function(d) {
                (d.mappoints || []).forEach(function(mp) {
                    sel.innerHTML += '<option value="' + mp.id + '">' + esc(mp.name) + '</option>';
                });
            });
    }

    function assignPic(idx, picId) {
        var galSel = document.getElementById('qa-gal-' + idx);
        var mpSel  = document.getElementById('qa-mp-' + idx);
        var galId  = galSel.value;
        var mpId   = mpSel.value;
        if (!galId || !mpId) { alert('Bitte Reise und Ort auswählen.'); return; }

        var btn = document.querySelector('#qa-' + idx + ' .btn-assign');
        btn.disabled = true; btn.textContent = '...';

        var fd = new FormData();
        fd.append('_token', csrf);
        fd.append('pic_id', picId);
        fd.append('gallery_id', galId);
        fd.append('mappoint_id', mpId);

        fetch('/travel-blog/assign-pic', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'ok') {
                    setStatus(idx, 'done', '✓ OK');
                    document.getElementById('qc-' + idx).classList.remove('needs-manual');
                    document.getElementById('qc-' + idx).classList.add('done');
                    var galName = galSel.options[galSel.selectedIndex].text;
                    var mpName  = mpSel.options[mpSel.selectedIndex].text;
                    document.getElementById('qr-' + idx).innerHTML =
                        '<span class="q-tag">' + esc(galSel.options[galSel.selectedIndex].dataset.code || '') + '</span>' +
                        '<span class="q-tag">' + esc(galName) + '</span>' +
                        '<span class="q-tag">📍 ' + esc(mpName) + '</span>';
                } else {
                    btn.disabled = false; btn.textContent = 'Zuordnen';
                    alert(data.message || 'Fehler beim Zuordnen.');
                }
            })
            .catch(function() { btn.disabled = false; btn.textContent = 'Zuordnen'; });
    }
    </script>
</x-gallery-layout>
