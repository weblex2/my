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
                <td><input type="text" name="column_name" value="{{$field->COLUMN_NAME}}" /></td>
                <td><x-laravel-my-admin.field-type-dropdown name="column_name" selected="{{$field->Datatype['type']}}" /></td>
                <td> <input type="text" name="length" value="{{$field->Datatype['length']}} {{isset($field->Datatype['scale']) ?  ",".$field->Datatype['scale'] : ""}}" /></td>
                <td><x-laravel-my-admin.default-dropdown name="default" selected="{{$field->COLUMN_DEFAULT}}" /></td>
                <td><x-laravel-my-admin.attribute-dropdown name="attribute" selected="{{$field->Datatype['signed'] }}" /></td>
                <td><x-laravel-my-admin.collation-dropdown name="collation" selected="{{$field->COLLATION_NAME}}" /></td>
                <td><x-laravel-my-admin.nullable name="is_nullable" selected="{{$field->IS_NULLABLE}}" :edit="1"/></td>
                <td>{{$field->COLUMN_COMMENT}}</td>
                <td>{{$field->EXTRA}}</td>
            @else
                <td>{{$field->COLUMN_NAME}}</td>
                <td>{{$field->Datatype['type']}}</td>
                <td>{{$field->Datatype['length']}}{{isset($field->Datatype['scale']) ?  ",".$field->Datatype['scale'] : ""}}</td>
                <td>{{$field->COLUMN_DEFAULT}}</td>
                <td>{{$field->COLUMN_DEFAULT}}</td>
                <td>{{$field->COLLATION_NAME}}</td>
                <td>{{$field->IS_NULLABLE}}</td>
                <td>{{$field->COLUMN_COMMENT}}</td>
                <td>{{$field->EXTRA}}</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
