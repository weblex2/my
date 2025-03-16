<x-noppal>
    <div class="w-screen h-screen text-black bg-white">
        <h1>Filament Manage Fields</h1>
        <div>
            <table  class="filament-field-config ">
            <tr>
                <th>Field{{$user_id}}</th>
                <th>Type</th>
                <th>Null</th>
                <th>Label</th>
                <th>Toggle</th>
                <th>Required</th>
                <th>Seachable</th>
                <th>Sortable</th>
            </tr>
            <tbody>
            @foreach($table as $i => $field)
                <tr>
                <td class="p-3">{{$field['Field']}}</td>
                <td class="p-3">{{$field['Type']}}</td>
                <td class="p-3">{{$field['Null']}}</td>
                <td class="p-3"><input type="text" name="label"></td>
                <td class="p-3"><input type="checkbox" name="boolean"></td>
                <td class="p-3"><input type="checkbox" name="required"></td>
                <td class="p-3"><input type="checkbox" name="seachable"></td>
                <td class="p-3"><input type="checkbox" name="sortable"></td>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</x-noppal>
