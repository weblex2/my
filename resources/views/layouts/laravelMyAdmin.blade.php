<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Layout</title>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    @vite(['resources/js/laravelMyAdmin.js','resources/css/laravelMyAdmin.css'])

    <!-- Styles -->
    @livewireStyles
</head>
<body class="flex h-screen">

    <div id="loader"><img src="{{asset("img/loading2.png")}}" class="w-24 h-24"></div>
    <!-- Modal -->
    <div class="modal fade" id="meinModal" tabindex="-1" aria-labelledby="meinModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="meinModalLabel">Mein Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    Hier steht dein Inhalt.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                </div>
            </div>
        </div>
    </div>

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
            {{-- <div class="form">Hier kommt dein Inhalt...</div> --}}
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

        function showModal(){
            console.log("Show MOdal");
            $("#meinModal").modal("show");
        }

    </script>

</body>
</html>
