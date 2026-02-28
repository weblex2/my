@extends('layouts.futter')
@section('header')
    <h2 class="mt-5 text-xl font-semibold leading-tight text-center text-gray-100">
        {{ __('Na Mäusschen, was essen wir heute?') }}
    </h2>
@stop
@section('content')
<div class="container flex-auto pt-20 mx-auto">
@php
    $today = date('Y-m-d');
@endphp
<div class="hidden md:block">
    <x-futter.calendar date={{$today}} :ft="$ft" :datesdb=$datesdb :dates=$dates />
</div>
<div class="block md:hidden">
    <x-futter.calendar-mobile date={{$today}} :ft="$ft" :datesdb=$datesdb :dates=$dates />
</div>
<div class="container futter">
    <div class="container flex-auto pt-20 mx-auto text-center items-justify">
        <!-- Unified Responsive Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center justify-center pt-10">
            
            <!-- Left Item (Food 0) -->
            <div class="flex justify-center">
                <x-futter.food :food="$futter[0]" />
            </div>

            <!-- Middle item (Food 1) -->
            <div class="flex justify-center">
                <x-futter.food :food="$futter[1]" />
            </div>

            <!-- Right Item (Food 2) -->
            <div class="flex justify-center">
                <x-futter.food :food="$futter[2]" />
            </div>

            <!-- Buttons Grid (Span 3 columns on desktop, 1 on mobile) -->
            <div class="col-span-1 md:col-span-3 pb-10 flex flex-col items-center space-y-6 pt-8">
                <a href="{{ url('futter') }}" class="w-full md:w-auto transform hover:scale-105 transition-all duration-300">
                    <button class="w-full md:w-80 text-lg md:text-xl font-bold py-4 px-8 rounded-2xl shadow-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white flex items-center justify-center space-x-3">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                        <span>Nööö, gib mir mehr Vorschläge...</span>
                    </button>
                </a>
                
                <a href="{{ url('futter/all') }}" class="w-full md:w-auto transform hover:scale-105 transition-all duration-300">
                    <button class="w-full md:w-80 text-lg md:text-xl font-bold py-4 px-8 rounded-2xl shadow-xl bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white flex items-center justify-center space-x-3">
                        <i class="fa-solid fa-globe"></i>
                        <span>Ach was solls.., alles sehen!!</span>
                    </button>
                </a>
                
                <a href="{{ url('futter/new') }}" class="w-full md:w-auto transform hover:scale-105 transition-all duration-300">
                    <button class="w-full md:w-80 text-lg md:text-xl font-bold py-4 px-8 rounded-2xl shadow-xl bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-400 hover:to-red-500 text-white flex items-center justify-center space-x-3">
                        <i class="fa-solid fa-plus"></i>
                        <span>Yeah - Hab was Neues!!!</span>
                    </button>
                </a>
            </div>
            
        </div>

</div>
</div>
<script type="text/javascript">
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
