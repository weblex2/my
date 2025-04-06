@extends('layouts.laravelMyAdmin')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
     <div class="table-menu">

        <div class="float-left mr-5">Add Columns (Amount): </div>
        <div class="float-left mr-5"><input class="w-10 text-right" type="number" id="amount" name="amount" value="3"></div>
        <div class="float-left mr-5">After:</div>
        <div class="float-left mr-5">
            <x-laravel-my-admin.table-columns-dropdown id="after" />
        </div>
        <div><button class="btn" onclick="addRowsToNewTable()">Add</button></div>
    </div>

    <div>
    <table class="tblLaravelMyAdmin">
        <thead >
            <tr class="header">
                <th>&nbsp;</th>
                <th>Name</th>
                <th>Typ</th>
                <th>Length</th>
                <th>Standard</th>
                <th>Attribute</th>
                <th>Collation</th>
                <th>Null</th>
                <th>Kommentare</th>
                <th>Extra</th>
                <th>Aktion</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($fields as $field)
            <tr id="{{$field->COLUMN_NAME}}">
                <td>&nbsp;</td>
                <td>{{$field->COLUMN_NAME}}</td>
                <td>{{$field->Datatype['type']}}</td>
                <td>{{$field->Datatype['length']}}</td>
                <td>{{$field->COLUMN_DEFAULT}}</td>
                <td>{{$field->Datatype['signed'] }}</td>
                <td>{{$field->COLLATION_NAME}}</td>
                <td>{{$field->IS_NULLABLE}}</td>
                <td>{{$field->COLUMN_COMMENT}}</td>
                <td>{{$field->EXTRA}}</td>
                <td><a>Edit</a> <a>Delete</a> <a>More</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    <input type="hidden" name="action" id="action" value="modify">
    <input type="hidden" name="table" id="table" value="{{session('table')}}">
    <button class="btn" id="btnModifyTable">Create Migration</button>
@stop
