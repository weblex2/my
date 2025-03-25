<select>
    @foreach($fields as $field)
        <option value="{{$field}}">{{$field}}</option>
    @endforeach
</select>
