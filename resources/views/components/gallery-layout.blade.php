<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php 
            $title = "Noppal";
        @endphp
        @if (request()->is('blog*'))
            @php
                $title = "Noppals Blog";     
            @endphp
        @else
            @php
                $title = "Noppals Laravel";     
            @endphp
        @endif
        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/gallery.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-zinc-800 text-white">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-zinc-800 border-b border-zinc-900">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-gray-200">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
