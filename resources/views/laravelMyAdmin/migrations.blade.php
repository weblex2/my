@extends('layouts.laravelMyAdmin')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <div class="flex ">
        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif
        <div class="migrations">
            <div class="">
                @foreach($migrations as $migration)
                    <div class="migation-file p-1 {{ in_array($migration, $new_migrations) ? "bg-green-200" : "" }}">
                        <input type="checkbox" value="{{$migration}}" {{ in_array($migration, $new_migrations) ? "checked" : "" }}>
                        <span onclick="showMigration('{{$migration}}')">{{$migration}}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="w-full bg-green-500 migration-content-wrapper">
            <textarea id="migration-content">abcdefg</textarea>
        </div>

    </div>

    <script>
        async function executeMigrations(direction) {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let checkboxes = $('.migrations input[type="checkbox"]:checked');
            $("#meinModal").find('.modal-body').html("");

            if (checkboxes.length==0){
                $("#meinModal").find('.modal-body').html("nothing selected!");
                $("#meinModal").modal("show");
                return false;
            }

            $("#meinModal").modal("show");
            for (let checkbox of checkboxes) {
                let migration = $(checkbox).val();
                try {
                    $("#meinModal").find('.modal-body').append("<div>executing migration: " + direction + "  " + migration + '<span class="loader"><img src="{{asset("img/loading6.gif")}}" class="w-5 h-5"></span></div>');
                    let response = await $.ajax({
                        url: '{{route("laravelMyAdmin.execMigration")}}',
                        type: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify({ _token: csrfToken, action: direction, migration: migration }),
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    });

                    console.log("response:", response);
                    //$("#meinModal").find('.loader').remove();
                    $("#meinModal").find('.modal-body').append(response.data + "<br><br>");


                } catch (error) {
                    alert('Fehler beim Ausführen der Migration: ' + error.responseText);
                }
            }
        }

        $('#migUp').click(function() {
            executeMigrations('up');
        });

        $('#migDown').click(function() {
            executeMigrations('down');
        });

        $('#migTest').click(function() {
            executeMigrations('updown');
        });

        function showMigration(migration) {
            alert("Migration File!!" + migration);
        }

    </script>

@stop
