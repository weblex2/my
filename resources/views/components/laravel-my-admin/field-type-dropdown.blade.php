<select name="datatype">
    <optgroup label="Boolean Types">
        <option value="boolean" {{ strpos($selected, 'boolean') !== false ? 'selected' : '' }}>boolean</option>
    </optgroup>
    <optgroup label="String & Text Types">
        <option value="char" {{ strpos($selected, 'char') !== false ? 'selected' : '' }}>char</option>
        <option value="longtext" {{ strpos($selected, 'longtext') !== false ? 'selected' : '' }}>longText</option>
        <option value="mediumText" {{ strpos($selected, 'mediumText') !== false ? 'selected' : '' }}>mediumText</option>
        <option value="string" {{ in_array($selected, ['string','varchar','text']) !== false ? 'selected' : '' }}>string</option>
        <option value="tinyText" {{ strpos($selected, 'tinyText') !== false ? 'selected' : '' }}>tinyText</option>
    </optgroup>
    <optgroup label="Numeric Types">
        <option value="bigIncrements" {{ strpos($selected, 'bigIncrements') !== false ? 'selected' : '' }}>bigIncrements</option>
        <option value="bigint" {{ strpos($selected, 'bigint') !== false ? 'selected' : '' }}>bigInteger</option>
        <option value="decimal" {{ strpos($selected, 'decimal') !== false ? 'selected' : '' }}>decimal</option>
        <option value="double" {{ strpos($selected, 'double') !== false ? 'selected' : '' }}>double</option>
        <option value="float" {{ strpos($selected, 'float') !== false ? 'selected' : '' }}>float</option>
        <option value="id" {{ strpos($selected, 'id') !== false ? 'selected' : '' }}>id</option>
        <option value="increments" {{ strpos($selected, 'increments') !== false ? 'selected' : '' }}>increments</option>
        <option value="integer" {{ strpos($selected, 'integer') !== false ? 'selected' : '' }}>integer</option>
        <option value="mediumInteger" {{ strpos($selected, 'mediumInteger') !== false ? 'selected' : '' }}>mediumInteger</option>
        <option value="smallInteger" {{ strpos($selected, 'smallInteger') !== false ? 'selected' : '' }}>smallInteger</option>
        <option value="tinyInteger" {{ strpos($selected, 'tinyInteger') !== false ? 'selected' : '' }}>tinyInteger</option>
        <option value="unsignedBigInteger" {{ strpos($selected, 'unsignedBigInteger') !== false ? 'selected' : '' }}>unsignedBigInteger</option>
        <option value="unsignedInteger" {{ strpos($selected, 'unsignedInteger') !== false ? 'selected' : '' }}>unsignedInteger</option>
    </optgroup>
    <optgroup label="Date Types">
        <option value="date" {{ $selected == 'date' ? 'selected' : '' }}>date</option>
        <option value="datetime" {{ $selected == 'datetime' ? 'selected' : '' }}>datetime</option>
        <option value="timestamp" {{ $selected == 'timestamp' ? 'selected' : '' }}>datetime</option>
    </optgroup>
</select>
