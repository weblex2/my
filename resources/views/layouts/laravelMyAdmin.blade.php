<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>LaravelMyAdmin</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.1.1.min.js">
        @vite(['resources/js/laravelMyAdmin.js', 'resources/js/app.js',  'resources/css/laravelMyAdmin.css'])

        <!-- Styles -->
        @livewireStyles
    </head>

    <body class="font-sans antialiased">

    <x-navigation-clean />


    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 h-screen max-h-screen text-white bg-gray-800">
            <div class="h-screen max-h-screen pt-20 overflow-auto">
                <h2 class="text-xl font-semibold">Sidebar</h2>
                hiho123
                <x-laravelMyAdmin.sidebar />
            </div>
        </div>

        <!-- Main Content -->
        <div class="relative flex-1 overflow-auto" id="maincontent">
            <div class="absolute flex w-full bg-green-200 laravelMainHeader">
                <div>Databases</div>
            </div>
            @yield('content')
        </div>
    </div>




    @stack('modals')
    @livewireScripts
    </body>

    <script>
        $(function () {
            $('.db').click(function(){
                //$('.tables').hide();
                //$('.fields').hide();
                let db = $(this).attr('db_name');
                $('.' + db + '-tables').toggle(200);
            });

            $('.table').click(function(){
                let tablename = $(this).attr('table_name');
                $('.' + tablename + '-fields').toggle(200);
            });


            $('.newtable').click(function(){
                let db = $(this).attr('db_name');
                $.ajax({
                    type: 'POST',
                    url: '{{route("laravelMyAdmin.createTable")}}',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: { db: db } }
                    )
                .done(function(resp) { alert("success: " + resp.data)  })
                .fail(function() { alert("error"); })
                //.always(function() { alert("complete");
            });
        });
    </script>

</html>
