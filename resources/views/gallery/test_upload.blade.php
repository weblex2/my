<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Upload</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white p-8">

<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Gallery Upload Test</h1>
    
    <div class="bg-gray-800 p-6 rounded-lg mb-6">
        <h2 class="text-xl font-semibold mb-4">1. Erst eine Galerie erstellen</h2>
        <form id="create-gallery" class="space-y-4">
            <input type="text" name="name" placeholder="Name (z.B. Test Reise)" class="w-full p-2 bg-gray-700 rounded" required>
            <input type="text" name="code" placeholder="Code (z.B. TEST2024)" class="w-full p-2 bg-gray-700 rounded" required>
            <input type="text" name="country_map_name" placeholder="Country Map Name (optional)" class="w-full p-2 bg-gray-700 rounded">
            <button type="submit" class="bg-orange-500 px-4 py-2 rounded">Galerie erstellen</button>
        </form>
        <div id="gallery-result" class="mt-4"></div>
    </div>

    <div class="bg-gray-800 p-6 rounded-lg mb-6">
        <h2 class="text-xl font-semibold mb-4">2. Einen Ort erstellen</h2>
        <form id="create-mappoint" class="space-y-4">
            <select id="gallery-select" class="w-full p-2 bg-gray-700 rounded" required>
                <option value="">-- Galerie wählen --</option>
            </select>
            <input type="text" name="mappoint_name" placeholder="Ortsname (z.B. Berlin)" class="w-full p-2 bg-gray-700 rounded" required>
            <div class="grid grid-cols-2 gap-4">
                <input type="number" name="lat" step="any" placeholder="Latitude (52.52)" class="p-2 bg-gray-700 rounded" required>
                <input type="number" name="lon" step="any" placeholder="Longitude (13.40)" class="p-2 bg-gray-700 rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 px-4 py-2 rounded">Ort erstellen</button>
        </form>
        <div id="mappoint-result" class="mt-4"></div>
    </div>

    <div class="bg-gray-800 p-6 rounded-lg">
        <h2 class="text-xl font-semibold mb-4">3. Foto hochladen (MIT GPS!)</h2>
        <div id="upload-status" class="mb-4"></div>
        <form id="upload-form" class="space-y-4">
            <select id="upload-gallery" class="w-full p-2 bg-gray-700 rounded" required onchange="loadMappoints(this.value)">
                <option value="">-- Galerie wählen --</option>
            </select>
            <select id="upload-mappoint" class="w-full p-2 bg-gray-700 rounded" required>
                <option value="">-- Ort wählen --</option>
            </select>
            <input type="file" id="file-input" accept="image/*" class="w-full p-2 bg-gray-700 rounded" required>
            <label class="flex items-center gap-2">
                <input type="checkbox" id="has-gps">
                <span>Bild hat GPS-Daten (EXIF)</span>
            </label>
            <button type="submit" class="bg-green-500 px-4 py-2 rounded">Upload starten</button>
        </form>
        <div id="upload-result" class="mt-4"></div>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// Gallery erstellen
document.getElementById('create-gallery').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
        name: form.name.value,
        code: form.code.value,
        country_map_name: form.country_map_name.value
    };
    
    const res = await fetch('/gallery-admin/gallery', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf},
        body: JSON.stringify(data)
    });
    const json = await res.json();
    document.getElementById('gallery-result').innerHTML = `<pre class="bg-gray-900 p-4 rounded">${JSON.stringify(json, null, 2)}</pre>`;
    loadGalleries();
});

// Mappoint erstellen
document.getElementById('create-mappoint').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const data = {
        gallery_id: document.getElementById('gallery-select').value,
        mappoint_name: form.mappoint_name.value,
        lat: parseFloat(form.lat.value),
        lon: parseFloat(form.lon.value)
    };
    
    const res = await fetch('/gallery-admin/mappoint', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf},
        body: JSON.stringify(data)
    });
    const json = await res.json();
    document.getElementById('mappoint-result').innerHTML = `<pre class="bg-gray-900 p-4 rounded">${JSON.stringify(json, null, 2)}</pre>`;
});

// Upload
document.getElementById('upload-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const fileInput = document.getElementById('file-input');
    const file = fileInput.files[0];
    if (!file) return alert('Bitte Datei wählen!');
    
    const fd = new FormData();
    fd.append('_token', csrf);
    fd.append('file', file);
    fd.append('content', '');
    fd.append('country_code', '');
    fd.append('mappoint_id', document.getElementById('upload-mappoint').value);
    fd.append('auto_assign', '0');
    
    document.getElementById('upload-status').innerHTML = '<div class="text-yellow-400">⏳ Upload läuft...</div>';
    
    try {
        const res = await fetch('/travel-blog/store-bulk', {
            method: 'POST',
            body: fd
        });
        const json = await res.json();
        
        if (res.ok) {
            document.getElementById('upload-result').innerHTML = `<pre class="bg-gray-900 p-4 rounded text-green-400">${JSON.stringify(json, null, 2)}</pre>`;
            document.getElementById('upload-status').innerHTML = '<div class="text-green-400">✅ Upload erfolgreich!</div>';
        } else {
            document.getElementById('upload-result').innerHTML = `<pre class="bg-gray-900 p-4 rounded text-red-400">${JSON.stringify(json, null, 2)}</pre>`;
            document.getElementById('upload-status').innerHTML = '<div class="text-red-400">❌ Upload fehlgeschlagen!</div>';
        }
    } catch (err) {
        document.getElementById('upload-result').innerHTML = `<pre class="bg-gray-900 p-4 rounded text-red-400">${err.message}</pre>`;
        document.getElementById('upload-status').innerHTML = '<div class="text-red-400">❌ Fehler!</div>';
    }
});

function loadGalleries() {
    fetch('/gallery-admin')
        .then(r => r.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            // Extract gallery options if needed
        });
    
    // Einfach alle Galerien laden
    const sel1 = document.getElementById('gallery-select');
    const sel2 = document.getElementById('upload-gallery');
    
    // Wir müssen die Galerien von der Server-Seite holen
}

function loadMappoints(galleryId) {
    if (!galleryId) return;
    fetch(`/gallery-admin/mappoints/${galleryId}`)
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('upload-mappoint');
            sel.innerHTML = '<option value="">-- Ort wählen --</option>';
            data.mappoints.forEach(mp => {
                sel.innerHTML += `<option value="${mp.id}">${mp.mappoint_name}</option>`;
            });
        });
}
</script>

</body>
</html>
