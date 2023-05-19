
@extends('layouts.friese')
@section('content') 
    <div class="frise">
        <img class="rounded-full float-left" src="{{ asset("img/yeah_baby.jpg") }}" width="100px" height="100px"></img>
        <h1>Friseurinnen24.de</h1>
        <h2>Dein Portal für die angesagtesten Friesen!!</h2>
        <div class="grid grid-cols-2 gap-3 w-full bg-red-800">
            <div class="col-span-full bg-slate-500" id="reactFrise"></div>
        </div>    
    </div>
@stop

