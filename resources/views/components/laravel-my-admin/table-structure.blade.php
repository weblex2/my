<table class="tblLaravelMyAdmin">
    <thead >
        <tr class="header">
            @if ($edit==1)
            <th>&nbsp;</th>
            @endif
            <th>Name</th>
            <th>Typ</th>
            <th>Length</th>
            <th>Standard</th>
            <th>Attribute</th>
            <th>Collation</th>
            <th>Nullable</th>
            <th>Kommentare</th>
            <th>Extra</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($fields as $field)
        <tr id="{{$field->COLUMN_NAME}}">
            @if($edit==1)
                <td>&nbsp;</td>
            @endif
            <td>
                @if ($edit)
                    <x-laravel-my-admin.table-columns-dropdown id="column_name" name="column_name" selected="{{$field->COLUMN_NAME}}" />
                @else
                    {{$field->COLUMN_NAME}}
                @endif
                </td>
            <td>
                @if ($edit)
                  <x-laravel-my-admin.field-type-dropdown id="column_name" name="column_name" selected="{{$field->Datatype['type']}}" />
                @else
                    {{$field->Datatype['type']}}
                @endif
            </td>
            <td>
                @if ($edit)
                  <input type="text" name="length" value="{{$field->Datatype['length']}}" />
                @else
                    {{$field->Datatype['length']}}
                @endif
            </td>
            <td>@if ($edit)
                  <x-laravel-my-admin.default-dropdown id="default" name="default" selected="{{$field->COLUMN_DEFAULT}}" />
                @else
                    {{$field->COLUMN_DEFAULT}}
                @endif
            </td>
            <td>{{$field->Datatype['signed'] }}</td>
            <td>{{$field->COLLATION_NAME}}</td>
            <td>{{$field->IS_NULLABLE}}</td>
            <td>{{$field->COLUMN_COMMENT}}</td>
            <td>{{$field->EXTRA}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
