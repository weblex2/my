@extends('layouts.laravelMyAdmin')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
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

    <div>
    <table class="tblLaravelMyAdmin">
        <thead >
            <tr class="header">
                <th>&nbsp;</th>
                <th>Name</th>
                <th>Typ</th>
                <th>Collation</th>
                <th>Attribute</th>
                <th>Null</th>
                <th>Standard</th>
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
                <td>{{$field->COLUMN_TYPE}}</td>
                <td>{{$field->COLLATION_NAME}}</td>
                <td>{{$field->Datatype['signed'] }}</td>
                <td>{{$field->IS_NULLABLE}}</td>
                <td>{{$field->COLUMN_DEFAULT}}</td>
                <td>{{$field->COLUMN_COMMENT}}</td>
                <td>{{$field->EXTRA}}</td>
                <td><a>Edit</a> <a>Delete</a> <a>More</a></td>
            </tr>
        @endforeach
    </div>
@stop
