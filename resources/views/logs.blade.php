@php
    $logs = nl2br(file_get_contents('../storage/logs/mylog.log'));
@endphp

{!! $logs !!}