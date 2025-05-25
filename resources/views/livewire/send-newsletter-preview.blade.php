@php use Illuminate\Support\Str; @endphp
<div class="prose max-w-none">
    {!! Str::markdown($content ?? "") !!} <!-- Sollte <h1>Test</h1> rendern -->
</div>
