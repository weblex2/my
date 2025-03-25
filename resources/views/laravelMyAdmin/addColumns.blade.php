@extends('layouts.clean')

@section('content')
    <div class="container p-5 mx-auto ">
        <h1 class="mb-6 text-3xl font-bold text-gray-600">Bearbeiten der Tabelle: {{ $table }}</h1>
        <form action="{{ route('laravelMyAdmin.generateMigration') }}" method="POST" class="shadow-lg">
            @csrf
            <input type="hidden" name="table" value="{{ $table }}">
            <h3 class="mb-4 text-xl font-semibold">Add columns</h3>

            <!-- Tabelle für Spalten -->
            <table class="tblLaravelMyAdmin">
                <thead >
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Spaltenname</th>
                        <th>Typ</th>
                        <th>Kollation</th>
                        <th>Attribute</th>
                        <th>Null</th>
                        <th>Standard</th>
                        <th>Kommentare</th>
                        <th>Extra</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($columns as $i => $column)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="changes[selected][{{ $column->COLUMN_NAME }}]" value="1">
                            </td>
                            <td class="text-center">{{ $column->ORDINAL_POSITION }}</td>
                            <td>{{ $column->COLUMN_NAME }}
                                {!!$column->COLUMN_KEY =='PRI' ? '<i class="text-yellow-300 fa-solid fa-key"></i>' :"" !!}
                            </td>
                            <td>
                                <!-- Select für Datentyp -->
                                <x-laravel-my-admin.type-select selected="{{$column->Datatype['type']}}" />
                            </td>
                            <td>
                                <!-- Kollation -->
                                {{ $column->COLLATION_NAME }}
                            </td>

                            <td>
                                <!-- Attribute -->
                                {{ $column->Datatype['signed'] == "true" ? "signed" : "" }}
                            </td>

                            <td class="text-center">
                                <!-- Nullable -->
                                <input type="checkbox" name="changes[nullable][{{ $column->COLUMN_NAME }}]" value="1"  {{ $column->IS_NULLABLE === 'YES' ? 'checked' : '' }}>
                            </td>

                            <td class="text-center">
                                <!-- Default -->
                                {{ $column->COLUMN_DEFAULT }}
                            </td>

                             <td>
                                 {{ $column->COLUMN_COMMENT }}
                            </td>

                            <td>
                                <!-- Extra -->
                                {{ $column->EXTRA }}
                            </td>

                            <td>
                                <button class="btn btn-primary">Edit</button>
                                <button class="btn btn-delete">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
´


            <h3 class="mt-6 mb-4 text-xl font-semibold">Neue Spalte hinzufügen</h3>

            <div class="mb-4">
                <label for="column_name" class="block font-medium text-gray-700">Spaltenname</label>
                <input type="text" name="changes[add][column_name]" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="column_name" placeholder="Spaltenname">
            </div>

            <div class="mb-4">
                <label for="column_type" class="block font-medium text-gray-700">Typ der neuen Spalte</label>
                <select name="changes[add][column_type]" class="block w-full px-4 py-2 border border-gray-300 rounded-lg form-select">
                    <option value="string">string</option>
                    <option value="integer">integer</option>
                    <option value="text">text</option>
                    <option value="boolean">boolean</option>
                    <option value="date">date</option>
                    <option value="timestamp">timestamp</option>
                    <option value="float">float</option>
                    <option value="decimal">decimal</option>
                    <option value="json">json</option>
                    <option value="enum">enum</option>
                    <option value="bigInteger">bigInteger</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="nullable" class="block font-medium text-gray-700">Nullable</label>
                <input type="checkbox" name="changes[add][nullable]" class="w-5 h-5 text-blue-500 form-checkbox" id="nullable">
            </div>

            <button type="submit" class="px-6 py-2 font-semibold text-white bg-green-500 rounded-lg hover:bg-green-600">Migration generieren</button>
        </form>
    </div>
@stop
