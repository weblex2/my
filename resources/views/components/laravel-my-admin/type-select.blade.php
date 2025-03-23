<select name="datatype" class="">
    <option value="string" {{ strpos($selected, 'varchar') !== false ? 'selected' : '' }}>string</option>
    <option value="integer" {{ strpos($selected, 'int') !== false ? 'selected' : '' }}>integer</option>
    <option value="text" {{ strpos($selected, 'text') !== false ? 'selected' : '' }}>text</option>
    <option value="boolean" {{ strpos($selected, 'tinyint') !== false ? 'selected' : '' }}>boolean</option>
    <option value="date" {{ strpos($selected, 'date') !== false ? 'selected' : '' }}>date</option>
    <option value="timestamp" {{ strpos($selected, 'timestamp') !== false ? 'selected' : '' }}>timestamp</option>
    <option value="float" {{ strpos($selected, 'float') !== false ? 'selected' : '' }}>float</option>
    <option value="decimal" {{ strpos($selected, 'decimal') !== false ? 'selected' : '' }}>decimal</option>
    <option value="json" {{ strpos($selected, 'json') !== false ? 'selected' : '' }}>json</option>
    <option value="enum" {{ strpos($selected, 'enum') !== false ? 'selected' : '' }}>enum</option>
    <option value="bigInteger" {{ strpos($selected, 'bigint') !== false ? 'selected' : '' }}>bigInteger</option>
</select>
