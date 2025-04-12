<select name="default">
    <option value=null>---select---</option>
    <option value="">empty string</option>
    <option value="defined">how defined</option>
    <option value="null">null</option>
    <option value="current_timestamp" {{$selected=="current_timestamp()" ? "selected" : ""}}>current_timestamp()</option>
</select>
