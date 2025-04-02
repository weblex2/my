{{-- <select name="datatype" class="">
    <option value="" {{ strpos($selected, 'varchar') !== false ? 'selected' : '' }}>---select---</option>
    <option value="id" {{ strpos($selected, 'id') !== false ? 'selected' : '' }}>id</option>
    <option value="timestamps" {{ strpos($selected, 'timestamps') !== false ? 'selected' : '' }}>timestamps</option>
    <option value="string" {{ strpos($selected, 'varchar') !== false ? 'selected' : '' }}>string</option>
    <option value="integer" {{ strpos($selected, 'int') !== false ? 'selected' : '' }}>integer</option>
    <option value="text" {{ strpos($selected, 'text') !== false ? 'selected' : '' }}>text</option>
    <option value="boolean" {{ strpos($selected, 'tinyint') !== false ? 'selected' : '' }}>boolean</option>
    <option value="date" {{ strpos($selected, 'date') !== false ? 'selected' : '' }}>date</option>
    <option value="float" {{ strpos($selected, 'float') !== false ? 'selected' : '' }}>float</option>
    <option value="decimal" {{ strpos($selected, 'decimal') !== false ? 'selected' : '' }}>decimal</option>
    <option value="json" {{ strpos($selected, 'json') !== false ? 'selected' : '' }}>json</option>
    <option value="enum" {{ strpos($selected, 'enum') !== false ? 'selected' : '' }}>enum</option>
    <option value="bigInteger" {{ strpos($selected, 'bigint') !== false ? 'selected' : '' }}>bigInteger</option>
</select> --}}


<select name="datatype">
    <optgroup label="Boolean Types">
        <option value="boolean" {{ strpos($selected, 'boolean') !== false ? 'selected' : '' }}>boolean</option>
    </optgroup>
    <optgroup label="String & Text Types">
        <option value="char" {{ strpos($selected, 'char') !== false ? 'selected' : '' }}>char</option>
        <option value="longText" {{ strpos($selected, 'longText') !== false ? 'selected' : '' }}>longText</option>
        <option value="mediumText" {{ strpos($selected, 'mediumText') !== false ? 'selected' : '' }}>mediumText</option>
        <option value="string" {{ strpos($selected, 'string') !== false ? 'selected' : '' }}>string</option>
        <option value="text" {{ strpos($selected, 'text') !== false ? 'selected' : '' }}>text</option>
        <option value="tinyText" {{ strpos($selected, 'tinyText') !== false ? 'selected' : '' }}>tinyText</option>
    </optgroup>
    <optgroup label="Numeric Types">
        <option value="bigIncrements" {{ strpos($selected, 'bigIncrements') !== false ? 'selected' : '' }}>bigIncrements</option>
        <option value="bigInteger" {{ strpos($selected, 'bigInteger') !== false ? 'selected' : '' }}>bigInteger</option>
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
</select>
