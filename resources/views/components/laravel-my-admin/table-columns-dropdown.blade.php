<select id="{{$id}}">
    @foreach ($columns as $column)
    <option value="{{$column->COLUMN_NAME}}">{{$column->COLUMN_NAME}}</option>
    @endforeach
</select>
