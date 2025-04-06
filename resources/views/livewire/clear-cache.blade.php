<div>
    <h2>Cache leeren</h2>

    <!-- Button zum Leeren des Caches -->
    <button wire:click="clearCache"
            class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        Cache löschen
    </button>

    <!-- Button zum Leeren der Konfiguration -->
    <button wire:click="clearConfig"
            class="px-4 py-2 mt-2 text-white bg-yellow-500 rounded hover:bg-yellow-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        Config löschen
    </button>

    <!-- Button zum Leeren der Views -->
    <button wire:click="clearViews"
            class="px-4 py-2 mt-2 text-white bg-green-500 rounded hover:bg-green-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        Views löschen
    </button>

    <!-- Button zum Leeren von allem -->
    <button wire:click="clearAll"
            class="px-4 py-2 mt-2 text-white bg-blue-500 rounded hover:bg-blue-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        Alle löschen
    </button>

    <!-- Anzeige von Verarbeitungsstatus -->
    @if ($isProcessing)
        <div class="mt-3 text-yellow-500">Verarbeitung läuft...</div>
    @endif

    <!-- Dynamische Nachrichtenanzeige -->
    <div class="mt-3">
        {!! $message !!}
    </div>
</div>
