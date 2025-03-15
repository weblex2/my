@extends('layouts.futter')
@section('header')
    <h2 class="text-xl font-semibold leading-tight text-center text-gray-100">
        <a href="{{route('futter.index')}}">Home</a>
        &nbsp;
        {{ __('Na Mäusschen, was essen wir heute?') }}
    </h2>
@stop
@section('content')
<div class="container flex-auto pt-20 mx-auto">
    <div class="container futter">
        <div class="container flex-auto mx-auto text-center items-justify">
            @php
                $today = date('Y-m-d');
            @endphp
            <div class="hidden md:inline-grid">
                <x-futter.calendar date={{$today}} :ft="$ft" :datesdb=$datesdb :dates=$dates />
            </div>
            <div class="inline-grid md:hidden">
                <x-futter.calendar-mobile date={{$today}} :ft="$ft" :datesdb=$datesdb :dates=$dates />
            </div>
            <div class="grid hidden w-full grid-cols-6 mb-4 text-center md:inline-grid nobr items-justify">
                @foreach ($futter as $f)
                     <x-futter.food :food="$f"/>
                @endforeach
            </div>
            <div class="inline-grid w-full mb-4 md:hidden">
                @foreach ($futter as $f)
                     <div class="w-full">
                     <x-futter.food-mobile :food="$f"/>
                     </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // Send ntfy message
    $('.check').click(function(){
        var foodname = $(this).closest('.food').text();
        var url = "{{ route('ntfy.index', ":foodname") }}";
        url = url.replace(':foodname', foodname);
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data){
                console.log("message sent.");
            },
            error: function( data) {
                console.log(data);
            }
        });
    });
</script>
@stop
