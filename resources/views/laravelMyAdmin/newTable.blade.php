@extends('layouts.laravelMyAdmin')

@section('content')
    <div>


        <div>

            <!-- Tabelle für Spalten -->
            <form action="{{ route('laravelMyAdmin.createTable') }}" method="POST" class="shadow-lg">
                <div class="form">
                @csrf
                <h1>Neue Tabelle: </h1>

                <div>Table Name: <input type="text" name="tablename" value=""></div>
                </div>

                <table class="tblLaravelMyAdmin">
                    <thead >
                        <tr class="header">
                            <th>Name</th>
                            <th>Typ</th>
                            <th>Length/Values</th>
                            <th>Standard</th>
                            <th>Collation</th>
                            <th>Attributes</th>
                            <th>Null</th>
                            <th>Index</th>
                            <th>AI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <x-laravel-my-admin.new-table-row name="{id}" type="id" />
                        <x-laravel-my-admin.new-table-row name="{timestamps}" type="timestamps" />
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Migration generieren</button>
            </form>

            {{-- <h3 class="mt-6 mb-4 text-xl font-semibold">Neue Spalte hinzufügen</h3>
            <div class="flex w-full bg-red-200">
                <div class="mb-4">
                    <label for="column_name" class="block font-medium text-gray-700">Spaltenname</label>
                    <input type="text" name="changes[add][column_name]" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="column_name" placeholder="Spaltenname">
                </div>

            <div>Spalte(n) einfügen nach</div>

            <div class="mb-6">
                <label for="nullable" class="block font-medium text-gray-700">Nullable</label>
                <input type="checkbox" name="changes[add][nullable]" class="w-5 h-5 text-blue-500 form-checkbox" id="nullable">
            </div>
 --}}

        </div>
    </div>
@stop
