<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
            <!-- Scripts -->
        @vite(['resources/js/laravelMyAdmin.js','resources/css/laravelMyAdmin.css'])

        <!-- Styles -->
        @livewireStyles
</head>
<body class="flex h-screen">

    <!-- Sidebar -->
    <aside class="fixed w-64 h-full p-4 overflow-auto text-black transition-transform duration-300 ease-in-out transform -translate-x-full md:relative md:translate-x-0" id="sidebar">
        <h2 class="mb-4 text-xl font-bold">Sidebar</h2>
        <nav>
            <x-laravelMyAdmin.sidebar />
        </nav>
    </aside>

    <!-- Hauptbereich -->
    <div id="main-wrapper">

        <!-- Menüleiste oben -->
        <header id="header">
            <button class="text-gray-700 md:hidden" id="menuButton">
                ☰
            </button>
            {{-- <h1 class="text-xl font-semibold">Mein Laravel Projekt</h1> --}}
            <x-laravel-my-admin.main-menu/>
        </header>

        <!-- Scrollbarer Content -->
        <main id="main">
            <div class="form">Hier kommt dein Inhalt...</div>
            <div id='content'>
                @yield('content')
            </div>
        </main>

    </div>

    <script>
        // Sidebar ein-/ausblenden für mobile Ansicht
        const sidebar = document.getElementById("sidebar");
        const menuButton = document.getElementById("menuButton");

        menuButton.addEventListener("click", () => {
            sidebar.classList.toggle("-translate-x-full");
        });
    </script>

</body>
</html>
