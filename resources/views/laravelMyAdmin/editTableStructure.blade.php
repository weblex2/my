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
        <x-laravel-my-admin.table-structure :fields="$fields" :edit="1"/>
    </div>
    <input type="hidden" name="action" id="action" value="modify">
    <input type="hidden" name="table" id="table" value="{{session('table')}}">
    <button class="btn" id="btnModifyTable">Create Migration</button>
@stop
