<tr id="{{str_replace(["{","}"],'', $name)}}" changed="true">
    <td><i class="fa-trash" onclick="$(this).closest('tr').remove()"></i></td>
    <td><input type="text" name="column_name" value="{{$name}}"></td>
    <td><x-laravel-my-admin.field-type-dropdown selected={{$type}} /></td>
    <td><input type="text" name="length"></td>
    <td><x-laravel-my-admin.default-dropdown selected="" /></td>
    <td><x-laravel-my-admin.attribute-dropdown selected="" /></td>
    <td><x-laravel-my-admin.collation-dropdown selected="" /></td>
    <td><input type="checkbox" name="nullable" value="1"></td>
    <td>TBD</td>
    <td><input type="checkbox" name="auto_increment" value="1" {{$type=="id" ? "checked" : ""}}></td>
</tr>
