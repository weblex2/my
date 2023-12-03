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
        @elseif (request()->is('travel-blog*'))
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
        <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
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
                <header class="bg-zinc-800 border-b border-zinc-900" id="galheader">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-orange-500">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main id="main" class="overflow-hidden">
                <div id="busy" class="invisible modal">
                    <img src="{{ asset('img/gallery_processing.gif') }}" class="w-24">
                </div>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
    <script>
        $('#main').css('height', $(window).height() - ($('nav').outerHeight() + $('header').outerHeight() +1));
    </script>    
</html>
