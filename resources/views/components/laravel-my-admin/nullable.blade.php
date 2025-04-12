@if ($edit)
    <input type="checkbox" name="nullable" {{$selected=="YES" ? "checked" : ""}}>
@else
    @if ($selected=="YES")
        "YES"
    @else
        "NO"
    @endif
@endif

