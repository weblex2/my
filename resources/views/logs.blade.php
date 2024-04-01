@php
    $logs = file_get_contents('../storage/logs/mylog.log');
@endphp

{!! $logs !!}