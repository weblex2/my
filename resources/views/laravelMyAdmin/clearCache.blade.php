@extends('layouts.laravelMyAdmin')

@section('content')

        <h1 class="mb-6 text-3xl font-bold">Clear Cache</h1>

        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        @livewire('clear-cache')
@stop


