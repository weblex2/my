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
            <th>Null</th>
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
                    <input type="text" name="column_name" value="{{$field->COLUMN_NAME}}" />
                @else
                    {{$field->COLUMN_NAME}}
                @endif
            </td>
            <td>
                @if ($edit)
                  <x-laravel-my-admin.field-type-dropdown name="column_name" selected="{{$field->Datatype['type']}}" />
                @else
                    {{$field->Datatype['type']}}
                @endif
            </td>
            <td>
                @if ($edit)
                  <input type="text" name="length" value="{{$field->Datatype['length']}} {{$field->Datatype['scale'] ?? ",".$field->Datatype['scale']}}" />
                @else
                    {{$field->Datatype['length']}}{{isset($field->Datatype['scale']) ?  ",".$field->Datatype['scale'] : ""}}
                @endif
            </td>
            <td>
                @if ($edit)
                  <x-laravel-my-admin.default-dropdown name="default" selected="{{$field->COLUMN_DEFAULT}}" />
                @else
                    {{$field->COLUMN_DEFAULT}}
                @endif

            </td>
            <td>
                @if ($edit)
                  <x-laravel-my-admin.attribute-dropdown name="attribute" selected="{{$field->Datatype['signed'] }}" />
                @else
                    {{$field->COLUMN_DEFAULT}}
                @endif
            </td>
            <td>
                @if ($edit)
                  <x-laravel-my-admin.collation-dropdown name="collation" selected="{{$field->COLLATION_NAME}}" />
                @else
                    {{$field->COLLATION_NAME}}
                @endif
            </td>
            <td>
                @if ($edit)
                  <x-laravel-my-admin.nullable name="is_nullable" selected="{{$field->IS_NULLABLE}}" :edit="1"/>
                @else
                    {{$field->IS_NULLABLE}}
                @endif
            </td>
            <td>{{$field->COLUMN_COMMENT}}</td>
            <td>{{$field->EXTRA}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
