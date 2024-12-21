<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>


        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <!--script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script-->
        <script src="https://code.jquery.com/jquery-3.1.1.min.js">
        <script src="https://cdn.jsdelivr.net/npm/@floating-ui/core@1.6.4"></script>
        <script src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.6.7"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>

        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- Scripts -->
        @vite(['resources/js/noppal.js','resources/css/noppal.css'])
 
        <!-- Styles -->
        @livewireStyles
    </head>
    <body>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif


        <div class="font-sans antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
    <script>
        $(function () {
            $('[data-toggle="popover"]').popover();
        });

        $( ".foodimg" ).draggable({ 
            revert: true, 
            helper: "clone"
        });
        $('.day1').droppable({
            hoverClass: "futterHoverClass",
            drop: function( event, ui ) { 
            var draggable = ui.draggable;           
            var dragged = draggable.clone(); 
            var futterId = ui.draggable.attr("foodid");
            console.log( "futter_id = " + futterId) ;
            var img = $('#food_'+futterId).find('img').attr('src');
            $(this).html('<img src="'+img +'" class="rounded-full w-full h-full">');
            var dat = $(this).attr("date");
            //$(this).text( dat+ ": "+ futterId );
            var data = {};
            data['day'] = dat;
            data['futter_id'] = futterId;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },        
                type: 'POST',
                url: '{{ url("futter/saveFutterPerDay") }}',
                data: data,
                success: function (data){
                     console.log(data);
                },
                error: function( data) {
                    console.log(data);
                }
            });

        }
        });

    </script>
</html>
