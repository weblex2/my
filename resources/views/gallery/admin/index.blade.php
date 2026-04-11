<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gallery Administration ✈</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }

        .modal {
            display: none;
        }

        .modal.active {
            display: flex;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="text-slate-200">

    <div class="min-h-screen p-6">
        {{-- Header --}}
        <div class="max-w-7xl mx-auto mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-orange-400 to-orange-600 bg-clip-text text-transparent">
                        <i class="fa-solid fa-images mr-2"></i>Gallery Administration
                    </h1>
                    <p class="text-slate-400 mt-1">Verwalte deine Reisen, Orte und Fotos</p>
                </div>
                <a href="{{ route('gallery.index3') }}"
                    class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition">
                    <i class="fa-solid fa-map mr-2"></i>Zur Map
                </a>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-400">Galerien</p>
                            <p class="text-2xl font-bold text-orange-400">{{ $stats['total_galleries'] }}</p>
                        </div>
                        <i class="fa-solid fa-folder-open text-3xl text-orange-400/30"></i>
                    </div>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-400">Orte</p>
                            <p class="text-2xl font-bold text-blue-400">{{ $stats['total_mappoints'] }}</p>
                        </div>
                        <i class="fa-solid fa-location-dot text-3xl text-blue-400/30"></i>
                    </div>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-400">Fotos</p>
                            <p class="text-2xl font-bold text-green-400">{{ $stats['total_pics'] }}</p>
                        </div>
                        <i class="fa-solid fa-camera text-3xl text-green-400/30"></i>
                    </div>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-400">Texte</p>
                            <p class="text-2xl font-bold text-purple-400">{{ $stats['total_text_entries'] }}</p>
                        </div>
                        <i class="fa-solid fa-file-lines text-3xl text-purple-400/30"></i>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3 mb-6">
                <button onclick="openModal('gallery-modal')"
                    class="px-4 py-2 bg-orange-500 hover:bg-orange-600 rounded-lg transition font-medium">
                    <i class="fa-solid fa-plus mr-2"></i>Neue Galerie
                </button>
                <button onclick="openModal('mappoint-modal')"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition font-medium">
                    <i class="fa-solid fa-location-dot mr-2"></i>Neuer Ort
                </button>
                <button onclick="switchTab('photos')"
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg transition font-medium">
                    <i class="fa-solid fa-images mr-2"></i>Fotos organisieren
                </button>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="max-w-7xl mx-auto">
            {{-- Tabs --}}
            <div class="bg-slate-800/50 backdrop-blur border border-slate-700 rounded-xl overflow-hidden">
                <div class="border-b border-slate-700">
                    <div class="flex">
                        <button onclick="switchTab('galleries')" id="tab-galleries"
                            class="tab-btn px-6 py-3 font-medium border-b-2 border-orange-500 text-orange-400">
                            <i class="fa-solid fa-folder mr-2"></i>Galerien
                        </button>
                        <button onclick="switchTab('mappoints')" id="tab-mappoints"
                            class="tab-btn px-6 py-3 font-medium border-b-2 border-transparent text-slate-400 hover:text-slate-200">
                            <i class="fa-solid fa-location-dot mr-2"></i>Orte
                        </button>
                        <button onclick="switchTab('photos')" id="tab-photos"
                            class="tab-btn px-6 py-3 font-medium border-b-2 border-transparent text-slate-400 hover:text-slate-200">
                            <i class="fa-solid fa-images mr-2"></i>Fotos
                        </button>
                    </div>
                </div>

                {{-- Galleries Tab --}}
                <div id="content-galleries" class="tab-content p-6">
                    <div id="gallery-list">
                        @forelse($galleries as $gallery)
                            <div
                                class="gallery-item bg-slate-700/50 border border-slate-600 rounded-lg p-4 mb-3 hover:border-orange-500/50 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-orange-400">{{ $gallery->name }}</h3>
                                        <p class="text-sm text-slate-400 mt-1">
                                            <span
                                                class="font-mono bg-slate-800 px-2 py-0.5 rounded">{{ $gallery->code }}</span>
                                        </p>
                                        <div class="flex gap-4 mt-2 text-sm">
                                            <span class="text-slate-500">
                                                <i
                                                    class="fa-solid fa-location-dot mr-1"></i>{{ $gallery->gallery_mappoint_count ?? 0 }}
                                                Orte
                                            </span>
                                            <span class="text-slate-500">
                                                <i
                                                    class="fa-solid fa-camera mr-1"></i>{{ $gallery->gallery_pics_count ?? 0 }}
                                                Fotos
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            onclick="editGallery({{ $gallery->id }}, '{{ addslashes($gallery->name) }}', '{{ addslashes($gallery->code) }}', '{{ addslashes($gallery->country_map_name ?? '') }}')"
                                            class="px-3 py-1.5 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded transition text-sm">
                                            <i class="fa-solid fa-edit mr-1"></i>Bearbeiten
                                        </button>
                                        <button
                                            onclick="viewGalleryMappoints({{ $gallery->id }}, '{{ addslashes($gallery->name) }}')"
                                            class="px-3 py-1.5 bg-green-500/20 hover:bg-green-500/30 text-green-400 rounded transition text-sm">
                                            <i class="fa-solid fa-eye mr-1"></i>Orte
                                        </button>
                                        <button
                                            onclick="deleteGallery({{ $gallery->id }}, '{{ addslashes($gallery->name) }}')"
                                            class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded transition text-sm">
                                            <i class="fa-solid fa-trash mr-1"></i>Löschen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 text-slate-500">
                                <i class="fa-solid fa-folder-open text-6xl mb-4 opacity-30"></i>
                                <p class="text-lg">Keine Galerien vorhanden</p>
                                <p class="text-sm mt-2">Erstelle deine erste Galerie mit dem Button oben</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Mappoints Tab --}}
                <div id="content-mappoints" class="tab-content p-6 hidden">
                    <div class="mb-4">
                        <select id="mappoint-gallery-select" onchange="loadMappoints(this.value)"
                            class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 w-full md:w-64">
                            <option value="">-- Galerie auswählen --</option>
                            @foreach ($galleries as $gallery)
                                <option value="{{ $gallery->id }}">{{ $gallery->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="mappoint-list"></div>
                </div>

                {{-- Photos Tab --}}
                <div id="content-photos" class="tab-content p-6 hidden">
                    <div class="flex flex-wrap gap-3 mb-4">
                        <select id="photo-gallery-select" onchange="loadPhotos()"
                            class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 flex-1 md:flex-none md:w-64">
                            <option value="">-- Alle Galerien --</option>
                            @foreach ($galleries as $gallery)
                                <option value="{{ $gallery->id }}">{{ $gallery->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" id="photo-search" placeholder="Suche..." onkeyup="loadPhotos()"
                            class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 flex-1">
                        <button onclick="loadPhotos()" class="px-4 py-2 bg-slate-600 hover:bg-slate-500 rounded-lg">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </div>
                    <div id="photo-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- MODALS --}}
    {{-- ═══════════════════════════════════════════════ --}}

    {{-- Gallery Modal --}}
    <div id="gallery-modal" class="modal fixed inset-0 bg-black/80 items-center justify-center z-50">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-orange-400" id="gallery-modal-title">Neue Galerie</h2>
                <button onclick="closeModal('gallery-modal')" class="text-slate-400 hover:text-slate-200">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <form id="gallery-form" onsubmit="saveGallery(event)">
                <input type="hidden" id="gallery-id" value="">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Name *</label>
                        <input type="text" id="gallery-name" required
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Code *</label>
                        <input type="text" id="gallery-code" required
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 font-mono">
                        <p class="text-xs text-slate-500 mt-1">z.B. EU2024, ASIEN2023</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Country Map Name</label>
                        <input type="text" id="gallery-country-map-name"
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-orange-500 hover:bg-orange-600 rounded-lg font-medium">
                        <i class="fa-solid fa-save mr-2"></i>Speichern
                    </button>
                    <button type="button" onclick="closeModal('gallery-modal')"
                        class="px-4 py-2 bg-slate-600 hover:bg-slate-500 rounded-lg">
                        Abbrechen
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Mappoint Modal --}}
    <div id="mappoint-modal" class="modal fixed inset-0 bg-black/80 items-center justify-center z-50">
        <div
            class="bg-slate-800 border border-slate-700 rounded-xl p-6 max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-blue-400">Neuer Ort</h2>
                <button onclick="closeModal('mappoint-modal')" class="text-slate-400 hover:text-slate-200">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <form id="mappoint-form" onsubmit="saveMappoint(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Galerie *</label>
                        <select id="mappoint-gallery-id" required
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2">
                            <option value="">-- Bitte wählen --</option>
                            @foreach ($galleries as $gallery)
                                <option value="{{ $gallery->id }}">{{ $gallery->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Ortsname *</label>
                        <input type="text" id="mappoint-name" required
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Latitude *</label>
                            <input type="number" id="mappoint-lat" step="any" required
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Longitude *</label>
                            <input type="number" id="mappoint-lon" step="any" required
                                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Beschreibung</label>
                        <textarea id="mappoint-description" rows="3"
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg font-medium">
                        <i class="fa-solid fa-save mr-2"></i>Speichern
                    </button>
                    <button type="button" onclick="closeModal('mappoint-modal')"
                        class="px-4 py-2 bg-slate-600 hover:bg-slate-500 rounded-lg">
                        Abbrechen
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Photo Assign Modal --}}
    <div id="photo-assign-modal" class="modal fixed inset-0 bg-black/80 items-center justify-center z-50">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-green-400">Foto(s) zuweisen</h2>
                <button onclick="closeModal('photo-assign-modal')" class="text-slate-400 hover:text-slate-200">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <form id="photo-assign-form" onsubmit="assignPhotosForm(event)">
                <input type="hidden" id="assign-photo-ids" value="">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Galerie *</label>
                        <select id="assign-gallery-id" required onchange="loadMappointsForAssign(this.value)"
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2">
                            <option value="">-- Bitte wählen --</option>
                            @foreach ($galleries as $gallery)
                                <option value="{{ $gallery->id }}">{{ $gallery->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Ort *</label>
                        <select id="assign-mappoint-id" required
                            class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2">
                            <option value="">-- Zuerst Galerie wählen --</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg font-medium">
                        <i class="fa-solid fa-check mr-2"></i>Zuweisen
                    </button>
                    <button type="button" onclick="closeModal('photo-assign-modal')"
                        class="px-4 py-2 bg-slate-600 hover:bg-slate-500 rounded-lg">
                        Abbrechen
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Toast Container --}}
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

    <script>
        // ═══════════════════════════════════════════════════
        // UTILITIES
        // ═══════════════════════════════════════════════════
        function getCSRF() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function showToast(message, type = 'success') {
            const colors = {
                success: 'bg-green-600',
                error: 'bg-red-600',
                info: 'bg-blue-600'
            };
            const toast = document.createElement('div');
            toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg`;
            toast.textContent = message;
            document.getElementById('toast-container').appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-orange-500', 'text-orange-400');
                el.classList.add('border-transparent', 'text-slate-400');
            });

            document.getElementById(`content-${tab}`).classList.remove('hidden');
            const tabBtn = document.getElementById(`tab-${tab}`);
            tabBtn.classList.add('border-orange-500', 'text-orange-400');
            tabBtn.classList.remove('border-transparent', 'text-slate-400');

            if (tab === 'photos') loadPhotos();
        }

        // ═══════════════════════════════════════════════════
        // GALLERY CRUD
        // ═══════════════════════════════════════════════════
        function saveGallery(e) {
            e.preventDefault();
            const id = document.getElementById('gallery-id').value;
            const data = {
                name: document.getElementById('gallery-name').value,
                code: document.getElementById('gallery-code').value,
                country_map_name: document.getElementById('gallery-country-map-name').value,
            };

            const url = id ? `/gallery-admin/gallery/${id}` : '/gallery-admin/gallery';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCSRF(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        closeModal('gallery-modal');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Fehler aufgetreten', 'error');
                    }
                })
                .catch(err => showToast(err.message, 'error'));
        }

        function editGallery(id, name, code, countryMapName) {
            document.getElementById('gallery-modal-title').textContent = 'Galerie bearbeiten';
            document.getElementById('gallery-id').value = id;
            document.getElementById('gallery-name').value = name;
            document.getElementById('gallery-code').value = code;
            document.getElementById('gallery-country-map-name').value = countryMapName;
            openModal('gallery-modal');
        }

        function deleteGallery(id, name) {
            if (!confirm(`Galerie "${name}" wirklich löschen?\nAlle Orte und Fotos werden ebenfalls gelöscht!`)) return;

            fetch(`/gallery-admin/gallery/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCSRF(),
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Fehler aufgetreten', 'error');
                    }
                });
        }

        // ═══════════════════════════════════════════════════
        // Mappoint CRUD
        // ═══════════════════════════════════════════════════
        function saveMappoint(e) {
            e.preventDefault();
            const data = {
                gallery_id: document.getElementById('mappoint-gallery-id').value,
                mappoint_name: document.getElementById('mappoint-name').value,
                lat: document.getElementById('mappoint-lat').value,
                lon: document.getElementById('mappoint-lon').value,
                description: document.getElementById('mappoint-description').value,
            };

            fetch('/gallery-admin/mappoint', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCSRF(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        closeModal('mappoint-modal');
                        document.getElementById('mappoint-form').reset();
                        if (data.mappoint) loadMappoints(data.mappoint.gallery_id);
                    } else {
                        showToast(data.message || 'Fehler aufgetreten', 'error');
                    }
                });
        }

        function loadMappoints(galleryId) {
            if (!galleryId) {
                document.getElementById('mappoint-list').innerHTML =
                    '<p class="text-slate-500 text-center py-8">Bitte Galerie auswählen</p>';
                return;
            }

            fetch(`/gallery-admin/mappoints/${galleryId}`)
                .then(r => r.json())
                .then(data => {
                    const container = document.getElementById('mappoint-list');
                    if (!data.mappoints.length) {
                        container.innerHTML = '<p class="text-slate-500 text-center py-8">Keine Orte vorhanden</p>';
                        return;
                    }

                    container.innerHTML = data.mappoints.map(mp => `
                <div class="bg-slate-700/50 border border-slate-600 rounded-lg p-4 mb-3">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-blue-400">${mp.mappoint_name}</h3>
                            <p class="text-sm text-slate-400 mt-1">
                                <i class="fa-solid fa-location-dot mr-1"></i>${mp.lat.toFixed(4)}, ${mp.lon.toFixed(4)}
                            </p>
                            <p class="text-sm text-slate-500 mt-1">
                                <i class="fa-solid fa-camera mr-1"></i>${mp.gallery_pics_count || 0} Fotos
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editMappoint(${mp.id}, '${mp.mappoint_name}', ${mp.lat}, ${mp.lon})"
                                    class="px-3 py-1.5 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded transition text-sm">
                                <i class="fa-solid fa-edit mr-1"></i>Bearbeiten
                            </button>
                            <button onclick="deleteMappoint(${mp.id}, '${mp.mappoint_name}')"
                                    class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded transition text-sm">
                                <i class="fa-solid fa-trash mr-1"></i>Löschen
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
                });
        }

        function deleteMappoint(id, name) {
            if (!confirm(`Ort "${name}" wirklich löschen?\nAlle Fotos an diesem Ort werden ebenfalls gelöscht!`)) return;

            fetch(`/gallery-admin/mappoint/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCSRF(),
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        const galleryId = document.getElementById('mappoint-gallery-select').value;
                        if (galleryId) loadMappoints(galleryId);
                    } else {
                        showToast(data.message || 'Fehler aufgetreten', 'error');
                    }
                });
        }

        // ═══════════════════════════════════════════════════
        // PHOTO MANAGEMENT
        // ═══════════════════════════════════════════════════
        function loadPhotos() {
            const galleryId = document.getElementById('photo-gallery-select').value;
            const search = document.getElementById('photo-search').value;

            let url = '/gallery-admin/photos?';
            if (galleryId) url += `gallery_id=${galleryId}&`;
            if (search) url += `search=${encodeURIComponent(search)}&`;

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    const container = document.getElementById('photo-grid');
                    if (!data.photos.data.length) {
                        container.innerHTML =
                            '<p class="text-slate-500 text-center py-8 col-span-full">Keine Fotos gefunden</p>';
                        return;
                    }

                    container.innerHTML = data.photos.data.map(photo => {
                        const thumbnail = photo.thumbnail ?
                            `<img src="${photo.thumbnail}" class="w-full h-40 object-cover rounded-t-lg">` :
                            '<div class="w-full h-40 bg-slate-700 flex items-center justify-center"><i class="fa-solid fa-image text-4xl text-slate-600"></i></div>';
                        const date = photo.taken_at ? new Date(photo.taken_at).toLocaleDateString('de-DE') :
                            'Kein Datum';
                        const location = photo.mappoint ? photo.mappoint.mappoint_name : 'Kein Ort';
                        const text = photo.gallery_text && photo.gallery_text.length > 0 ? photo.gallery_text[0]
                            .text : '';

                        return `
                    <div class="bg-slate-700/50 border border-slate-600 rounded-lg overflow-hidden hover:border-orange-500/50 transition group">
                        ${thumbnail}
                        <div class="p-3">
                            <p class="text-xs text-slate-500 mb-1"><i class="fa-solid fa-calendar mr-1"></i>${date}</p>
                            <p class="text-xs text-slate-500 mb-2"><i class="fa-solid fa-location-dot mr-1"></i>${location}</p>
                            ${text ? `<p class="text-xs text-slate-400 line-clamp-2 mb-2">${text.substring(0, 80)}...</p>` : ''}
                            <div class="flex gap-2">
                                <button onclick="openPhotoAssign([${photo.id}])"
                                        class="flex-1 px-2 py-1 bg-green-500/20 hover:bg-green-500/30 text-green-400 rounded text-xs">
                                    <i class="fa-solid fa-share mr-1"></i>Verschieben
                                </button>
                                <button onclick="deletePhoto(${photo.id})"
                                        class="flex-1 px-2 py-1 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded text-xs">
                                    <i class="fa-solid fa-trash mr-1"></i>Löschen
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                    }).join('');
                });
        }

        function deletePhoto(id) {
            if (!confirm('Foto wirklich löschen?')) return;

            fetch(`/gallery-admin/photo/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCSRF(),
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        loadPhotos();
                    } else {
                        showToast(data.message || 'Fehler aufgetreten', 'error');
                    }
                });
        }

        function openPhotoAssign(photoIds) {
            document.getElementById('assign-photo-ids').value = photoIds.join(',');
            openModal('photo-assign-modal');
        }

        function loadMappointsForAssign(galleryId) {
            fetch(`/gallery-admin/mappoints/${galleryId}`)
                .then(r => r.json())
                .then(data => {
                    const select = document.getElementById('assign-mappoint-id');
                    select.innerHTML = '<option value="">-- Bitte wählen --</option>' +
                        data.mappoints.map(mp => `<option value="${mp.id}">${mp.mappoint_name}</option>`).join('');
                });
        }

        function assignPhotosForm(e) {
            e.preventDefault();
            const data = {
                photo_ids: document.getElementById('assign-photo-ids').value.split(',').map(Number),
                gallery_id: document.getElementById('assign-gallery-id').value,
                mappoint_id: document.getElementById('assign-mappoint-id').value,
            };

            fetch('/gallery-admin/photos/assign', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCSRF(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        closeModal('photo-assign-modal');
                        loadPhotos();
                    } else {
                        showToast(data.message || 'Fehler aufgetreten', 'error');
                    }
                });
        }

        // Init
        document.addEventListener('DOMContentLoaded', function() {
            // Close modals on backdrop click
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) closeModal(this.id);
                });
            });
        });
    </script>

</body>

</html>
