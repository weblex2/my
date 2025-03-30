@extends('layouts.laravelMyAdmin')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    @csrf
    <!-- Tabelle für Spalten -->

    <h1><span class="float-left pl-5">Neue Tabelle:</span> <input type="text" name="tablename" value=""></h1>
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
        <table class="tblLaravelMyAdmin">
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
        <button type="button" onclick="showModal()" class="btn btn-primary">Migration generieren</button>
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


    </script>
@stop
