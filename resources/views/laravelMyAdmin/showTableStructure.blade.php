@extends('layouts.laravelMyAdmin')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
     <div class="table-menu">
        <div><a href="{{route("laravelMyAdmin.editTableStructure")}}" class="btn">Edit Table</a></div>
    </div>

    <div>
        <x-laravel-my-admin.table-structure :fields="$fields" :edit="0"/>
    </div>
@stop
