@extends('layouts.laravelMyAdmin')

@section('content')
    <div>
    <table class="tblLaravelMyAdmin">
        <thead >
            <tr class="header">
                <th>Table</th>
                <th colspan=6>Action</th>
                {{-- <th>Structure</th>
                <th>Search</th>
                <th>Add Row</th>
                <th>Truncate</th>
                <th>Delete</th> --}}
                <th>Rows</th>
                <th>Typ</th>
                <th>Collation</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($tables as $table)
            <tr>
            <td><a href="{{ route("laravelMyAdmin.showTable", ["db" => $db, 'table' => $table->TABLE_NAME]) }}">{{$table->TABLE_NAME}}</a></td>
            <td><a href="{{ route("laravelMyAdmin.showTableContent", ["db" => $db, 'table' => $table->TABLE_NAME]) }}"><i class="fa-solid fa-table action-icon"></i> Show</a></td>
            <td><i class="fa-solid fa-folder-tree action-icon"></i> Structure</td>
            <td><i class="fa-solid fa-magnifying-glass action-icon"></i> Search</td>
            <td><i class="fa-solid fa-file-circle-plus action-icon"></i> Add Row</td>
            <td><i class="fa-solid fa-eraser action-icon"></i> Truncate</td>
            <td><i class="fa-solid fa-trash-can delete-icon"></i> Delete</td>
            <td>{{$table->TABLE_ROWS}}
            <td>{{$table->ENGINE}}
            <td>{{$table->TABLE_COLLATION}}
            </tr>
        @endforeach
    </div>
@stop
