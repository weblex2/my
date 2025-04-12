<select name="default">
    <option value=null></option>
    <option value="">empty string</option>
    <option value="defined">how defined</option>
    <option value="null">null</option>
    <option value="current_timestamp" {{$selected=="current_timestamp()" ? "selected" : ""}}>current_timestamp()</option>
</select>
