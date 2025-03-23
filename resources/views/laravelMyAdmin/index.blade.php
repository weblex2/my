@extends('layouts.laravelMyAdmin')

@section('content')
    <div class="container p-5 mx-auto">
        <h1 class="mb-6 text-3xl font-bold">Tabellenübersicht</h1>

        @if(session('success'))
            <div class="p-4 mb-6 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <table class="min-w-full bg-white border-collapse rounded-lg shadow-lg table-auto">
            <thead class="text-white bg-blue-600">
                <tr>
                    <th class="px-6 py-3 text-left">Tabellen</th>
                </tr>
            </thead>
        </table>
    </div>
@stop

