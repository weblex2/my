@extends('layouts.laravelMyAdmin')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <h1 class="mb-6 text-3xl font-bold">Table Content</h1>
    <div class="flex ">
        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @php
        $firstRow = (array) $content[0]; // Der erste Datensatz als Array
        $headers = array_keys($firstRow); // Extrahiere die Keys (Spaltennamen)
    @endphp

    <table class="tblLaravelMyAdmin">
        <tr class="header">
            @foreach ($headers as $i => $header)
                <th>{{$header}}</th>
            @endforeach
        </tr>
        @foreach ($content as $i => $row)
            <tr>
                @foreach ($row as $key => $value)
                    <td>{{$value}}</td>
                @endforeach
            </tr>
        @endforeach
    </table>
    <div class="flex flex-col w-full text-center">
        {{ $content->links() }}
    </div>
@stop
