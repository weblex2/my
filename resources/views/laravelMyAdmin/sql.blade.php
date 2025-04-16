@extends('layouts.laravelMyAdmin')

@section('content')
    <div>
        <div class="table-menu">
            Menu
        </div>
        <div>
            <form id="frmSxecSql" action="{{route("laravelMyAdmin.execSql")}}" method="post">
                @csrf
                <textarea id="sql" name="sql">update fil_customers set status='customer'</textarea>
                <button class="btn" id="submit">Submit</button>
            </form>
        </div>
    </div>
@stop
