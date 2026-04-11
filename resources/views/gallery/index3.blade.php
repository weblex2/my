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

    {{-- PHP-Daten für travelmap.js --}}
    <script id="map-config" type="application/json">
        {"visitedCodes": @json($galleries->pluck('code')->unique()->values())}
    </script>

    {{-- amCharts (lokal via Vite) --}}
    @vite(['resources/js/travelmap.js'])


</body>

</html>
