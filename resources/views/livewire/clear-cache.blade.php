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

    <h2 class="mt-6">Git Befehle</h2>

    <!-- Button für git pull -->
    <button wire:click="gitPull"
            class="px-4 py-2 mt-2 text-white bg-gray-500 rounded hover:bg-gray-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        Git Pull
    </button>

    <!-- Button für git stash -->
    <button wire:click="gitStash"
            class="px-4 py-2 mt-2 text-white bg-purple-500 rounded hover:bg-purple-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        Git Stash
    </button>

    <!-- Button für git push -->
    <button wire:click="gitPush"
            class="px-4 py-2 mt-2 text-white bg-teal-500 rounded hover:bg-teal-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        Git Push
    </button>

    <h2 class="mt-6">NPM Befehle</h2>

    <!-- Button für npm run dev -->
    <button wire:click="npmRunDev"
            class="px-4 py-2 mt-2 text-white bg-indigo-500 rounded hover:bg-indigo-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        npm run dev
    </button>

    <!-- Button für npm run build -->
    <button wire:click="npmRunBuild"
            class="px-4 py-2 mt-2 text-white bg-blue-500 rounded hover:bg-blue-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        npm run build
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
