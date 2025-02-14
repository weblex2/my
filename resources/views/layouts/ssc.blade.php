<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SCC') }}</title>

        <!-- Fonts -->
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <!-- <script src="https://code.jquery.com/jquery-3.1.1.min.js"> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>    
        <script src="https://unpkg.com/prettier@3.5.1/standalone.js"></script>
        <script src="https://unpkg.com/prettier@3.5.1/plugins/graphql.js"></script>
        <!-- Scripts -->
        @vite(['resources/js/ssc.js', 'resources/css/ssc.css'])
 
        <!-- Styles -->
        @livewireStyles
        
    </head>
    <body>
        @livewireScripts
        @yield('content')
    </body>

    <script type="module">
          

    </script>
    
</html>
