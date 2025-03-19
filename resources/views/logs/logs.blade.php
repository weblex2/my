<x-layouts.maintenance-layout>
    <x-slot name="header">
        <h1>Wartungsmodus</h1>
    </x-slot>

    <div class="container">
        <form method="POST" action="{{ route('maintainance.refreshLogs') }}" class="mb-4">
            @csrf
            @php
                if (!isset($type)) $type="";
                if (!isset($level)) $level="";
                if (!isset($from)) $from=date('Y-m-d');
                if (!isset($to)) $to=date('Y-m-d');
            @endphp
            <div class="flex justify-between gap-4">
                <div class="col-md-3">
                    <label>Von:</label>
                    <input type="date" name="from" value="{{$from}}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Bis:</label>
                    <input type="date" name="to" value="{{$to}}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Typ:</label>
                    <select  name="type">
                        <option value="">Alle</option>
                        @foreach($types as $index => $seltype)
                            <option {{$type==$seltype->type ? "selected": ""}} value="{{$seltype->type}}">{{$seltype->type}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Level:</label>
                    <select name="level" class="form-control bg-zinc-700 text-zinc-100">
                        <option value="">Alle</option>
                        <option value="INFO" {{ $level == 'INFO' ? 'selected' : '' }}>INFO</option>
                        <option value="ERROR" {{ $level == 'ERROR' ? 'selected' : '' }}>ERROR</option>
                    </select>
                </div>
                <div class="mt-4 col-md-3">
                    <button type="submit" class="btn btn-primary">Filtern</button>
                </div>
            </div>
        </form>

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
                        <i class="fas fa-question-circle" style="color: grey;"></i> {{$log->level}} ('N/A')
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
    </div>
</x-layouts.maintenance-layout>
