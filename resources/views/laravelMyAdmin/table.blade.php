@extends('layouts.laravelMyAdmin')

@section('content')
    <div>
    <table class="tblLaravelMyAdmin">
        <thead >
            <tr class="header">
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
            <tr>
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
