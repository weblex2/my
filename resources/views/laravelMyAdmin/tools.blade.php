@extends('layouts.laravelMyAdmin')

@section('content')
    <div class="container">
        <h1>Git Pull Ergebnis 123</h1>
        <pre>{{date('H:i:s')}} {{ $output }}</pre>
        <a href="{{ route('git.pull') }}" class="btn btn-primary">Erneut ausführen</a>
    </div>
@endsection
