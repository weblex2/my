@extends('layouts.laravelMyAdmin')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <h1 class="mb-6 text-3xl font-bold">Migrations</h1>
    <div class="flex ">


        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif
        <div class="w-[400px] p-3 h-[900px] overflow-auto">
            <div class="migrations">
                @foreach($migrations as $migration)
                    <div class="{{ in_array($migration, $new_migrations) ? "bg-green-200" : "" }}">
                        <input type="checkbox" value="{{$migration}}" {{ in_array($migration, $new_migrations) ? "checked" : "" }}> {{$migration}}
                    </div>
                @endforeach
            </div>
        </div>

        <div class="migration-actions">
            <button class="btn" id="migUp">Migration Up</button>
            <button class="btn" id="migDown">Migration Down</button>
            <button class="btn" id="migTest">Migration Test</button>
        </div>
    </div>

    <script>
        async function executeMigrations(direction) {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let checkboxes = $('.migrations input[type="checkbox"]:checked');
            $("#meinModal").find('.modal-body').html("");
            for (let checkbox of checkboxes) {
                let migration = $(checkbox).val();

                try {
                    let response = await $.ajax({
                        url: '{{route("laravelMyAdmin.execMigration")}}',
                        type: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify({ _token: csrfToken, action: direction, migration: migration }),
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    });

                    console.log("response:", response);
                    $("#meinModal").find('.modal-body').append("executing migration: " + direction + "  " + migration +"<br>");
                    $("#meinModal").find('.modal-body').append(response.data + "<br><br>");
                    $("#meinModal").modal("show");

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

    </script>

@stop
