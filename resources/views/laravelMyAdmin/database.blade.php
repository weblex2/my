@extends('layouts.laravelMyAdmin')

@section('content')
    <div class="filter">
        Filter: <input type="text" name="table_filter">
    </div>

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
            <td>{{$table->TABLE_NAME}}
            <td><i class="text-blue-900 fa-solid fa-table"></i> Show</td>
            <td><i class="fa-solid fa-folder-tree"></i> Structure</td>
            <td><i class="fa-solid fa-magnifying-glass"></i> Search</td>
            <td><i class="fa-solid fa-file-circle-plus"></i> Add Row</td>
            <td><i class="fa-solid fa-eraser"></i> Truncate</td>
            <td><i class="fa-solid fa-trash-can"></i> Delete</td>
            <td>{{$table->TABLE_ROWS}}
            <td>{{$table->ENGINE}}
            <td>{{$table->TABLE_COLLATION}}
            </tr>
        @endforeach
    </div>
@stop
