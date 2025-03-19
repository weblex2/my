<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    
    @vite(['resources/css/noppal.css'])
</head>
<body class="bg-gray-100">

    <!-- Obere Navigation (Jetstream-Menü) -->
    <header class="bg-white shadow-md">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @livewire('navigation-menu')
        </div>
    </header>

    <div class="flex h-screen">
        <!-- Linkes Menü -->
        <aside class="flex flex-col w-64 text-white bg-gray-900">
            <div class="p-4 text-lg font-semibold">Menü</div>
            <nav class="flex-1">
                <ul>
                    <li class="px-4 py-2 hover:bg-gray-700"><a href="#">Dashboard</a></li>
                    <li class="px-4 py-2 hover:bg-gray-700"><a href="#">Einstellungen</a></li>
                    <li class="px-4 py-2 hover:bg-gray-700"><a href="#">Berichte</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Hauptinhalt mit automatischem Scrollen -->
        <main class="flex-1 p-6 overflow-auto">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
