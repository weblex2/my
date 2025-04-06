<div>
    <h2>Cache leeren</h2>

    <button wire:click="clearCache" class="btn btn-danger">
        Cache, Konfiguration und Views löschen
    </button>

    @if ($message)
        <div class="mt-3">
            <p>{{ $message }}</p>
        </div>
    @endif
</div>

