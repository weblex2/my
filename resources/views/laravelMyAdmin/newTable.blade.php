@extends('layouts.laravelMyAdmin')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    @csrf
    <!-- Tabelle für Spalten -->

    <h1><span class="float-left pl-5">Neue Tabelle:</span> <input type="text" id="tablename" name="tablename" value="tests"></h1>
    <div class="flex items-center">

        <div class="float-left mr-5">Add Columns (Amount): </div>
        <div class="float-left mr-5"><input type="text" id="amount" name="amount" value="3"></div>
        <div class="float-left mr-5">After:</div>
        <div>
            <select id="after" name="after" class="w-12">
                <option value="id" selected>id</option>
            </select>
        </div>
        <div><button class="btn" onclick="addRowsToNewTable()">Add</button></div>
    </div>

    <div class="shadow-lg">
        <table id="create-table" class="tblLaravelMyAdmin">
            <thead >
                <tr class="header">
                    <th>&nbsp;</th>
                    <th>Name</th>
                    <th>Typ</th>
                    <th>Length/Values</th>
                    <th>Standard</th>
                    <th>Collation</th>
                    <th>Attributes</th>
                    <th>Null</th>
                    <th>Index</th>
                    <th>AI</th>
                </tr>
            </thead>
            <tbody>
                <x-laravel-my-admin.new-table-row name="{id}" type="id" />
                <x-laravel-my-admin.new-table-row name="{timestamps}" type="timestamps" />
            </tbody>
        </table>
        <button type="button" id="submit-button" class="btn btn-primary">Migration generieren</button>
    </div>

    <script>
        function addRowsToNewTable(url, data, successCallback, errorCallback) {
            let amount = $('#amount').val();
            let after  = $('#after').val();
            //alert("amount" + amount + " after" + after);
            $('#loader').css('visibility','visible');
            $.ajax({
                url: "{{route("laravelMyAdmin.addRowsToTable")}}",
                type: "POST",
                data: data,
                //dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") // Falls Laravel CSRF-Schutz aktiv ist
                },
                success: function (response) {
                    let afterTr = $('#'+after);
                    afterTr.after(response.data);
                    $('#loader').css('visibility','hidden');
                },
                error: function (xhr, status, error) {
                    alert("no");
                    //if (errorCallback) errorCallback(xhr, status, error);
                    $('#loader').css('visibility','hidden');
                }
            });
        }


        $('#submit-button').click(function() {
        let inputData = [];

        $('#create-table tbody tr').each(function() {
            let rowData = {};
            $(this).find('input, select').each(function() {
                let name = $(this).attr('name');
                let value;

                if ($(this).is(':checkbox')) {
                    value = $(this).is(':checked') ? $(this).val() : null;
                } else {
                    value = $(this).val();
                }

                rowData[name] = value;

            });

            inputData.push(rowData);
        });

        let tableName = $('#tablename').val();
        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '{{ route("laravelMyAdmin.generateMigration" )}}',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ _token: csrfToken, data: { action : "create-table", tableName: tableName, rows: inputData } }),
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log(response);
                alert('Migration erfolgreich gesendet!');
            },
            error: function(xhr) {
                alert('Fehler beim Senden der Migration: ' + xhr.responseText);
            }
        });
    });

    </script>
@stop
