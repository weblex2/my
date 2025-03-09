<x-noppal>
    <table id="tbl-logs" class="w-full">
    <thead>
    <tr>
        <th>ID</th>
        <th>Level</th>
        <th>Type</th>
        <th>Context</th>
        <th>Message</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($logs as $log)
        <tr>
        <td>{{$log->id}}</td>
        <td class="whitespace-nowrap">
            @switch($log->level)
                @case('INFO')
                    <i class="fas fa-info-circle" style="color: blue;"></i> {{$log->level}}
                    @break
                @case('ERROR')
                    <i class="fas fa-exclamation-circle" style="color: red;"></i> {{$log->level}}
                    @break
                @case('WARNING')
                    <i class="fas fa-exclamation-triangle" style="color: orange;"></i> {{$log->level}}
                    @break
                @default
                    <i class="fas fa-question-circle" style="color: grey;"></i> N/A
            @endswitch

        </td>
        <td>{{$log->type}}</td>
        <td>{{$log->context}}</td>
        <td>{{$log->message}}</td>
        <td>{{$log->created_at}}</td>
    </tr>
    @endforeach
    </tbody>
    </table>
</x-noppal>
