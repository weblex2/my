@extends('layouts.laravelMyAdmin')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <h1 class="mb-6 text-3xl font-bold">Migrations</h1>
    <div class="flex ">
        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <table class="tblLaravelMyAdmin">

    @foreach ($content as $i => $row)
        <tr>
            @foreach ($row as $key => $value)
                <td>{{$value}}</td>
            @endforeach
            {{-- <td>{{ $row }}</td>
            <td></td>
            <td></td> --}}
        </tr>
    @endforeach
    </table>
    <div class="flex flex-col text-center">
    {{ $content->links() }}
    </div>
@stop
