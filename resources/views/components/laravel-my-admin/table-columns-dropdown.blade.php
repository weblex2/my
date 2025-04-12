<select id="{{$id}}" class="table-columns-dropdown">
    @foreach ($columns as $column)
    <option value="{{$column->COLUMN_NAME}}" {{$selected==$column->COLUMN_NAME ? "selected" : ""}}>{{$column->COLUMN_NAME}}</option>
    @endforeach
</select>
