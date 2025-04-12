<select name="attribute">
    <option value="">---select---</option>
    <option {{$selected == "binary"  ? "selected" : "" }} value="binary">Binary</option>
    <option {{$selected == "unsigned"  ? "selected" : "" }} value="unsigned">Unsigned</option>
    <option {{$selected == "zerofill"  ? "selected" : "" }} value="zerofill">Unsigned Zerofill</option>
    <option {{$selected == "current_timestamp"  ? "selected" : "" }} value="current_timestamp">OnUpdate Current Timestamp</option>
</select>
