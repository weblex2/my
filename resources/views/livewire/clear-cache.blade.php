<div>
    <h2>Cache leeren</h2>

    <!-- Button zum Leeren des Caches -->
    <button wire:click="clearCache"
            class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        Cache, Konfiguration und Views löschen
    </button>

    <!-- Anzeige von Verarbeitungsstatus -->
    @if ($isProcessing)
        <div class="mt-3 text-yellow-500">Verarbeitung läuft...</div>
    @endif

    <!-- Dynamische Nachrichtenanzeige -->
    <div class="mt-3" wire:poll>
        {!! $message !!}
    </div>
</div>
