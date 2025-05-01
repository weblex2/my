<div>
    <h2>Cache leeren</h2>

    <!-- Button zum Leeren des Caches -->
    <button wire:click="clearCache"
            class="btn"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
         <i class="fa-solid fa-broom"></i> Cache löschen
    </button>

    <!-- Button zum Leeren der Konfiguration -->
    <button wire:click="clearConfig"
            class="btn"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        <i class="fa-solid fa-gear"></i> Config löschen
    </button>

    <!-- Button zum Leeren der Views -->
    <button wire:click="clearViews"
            class="btn"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        <i class="fa-solid fa-street-view"></i> Views löschen
    </button>

    <!-- Button zum Leeren von allem -->
    <button wire:click="clearAll"
            class="btn"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        <i class="fa-solid fa-globe"></i> Alle löschen
    </button>

    <h2 class="mt-6">Git Befehle</h2>

    <!-- Button für git pull -->
    <button wire:click="gitPull"
            class="btn"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
       <i class="fa-solid fa-code-pull-request"></i> Git Pull
    </button>

    <!-- Button für git stash -->
    <button wire:click="gitStash"
            class="btn"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        <i class="fa-solid fa-code-branch"></i> Git Stash
    </button>

    <!-- Button für git push -->
    <button wire:click="gitPush"
            class="btn"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        <i class="fa-solid fa-code-fork"></i> Git Push
    </button>

    <!-- Swagger refresh -->
     <button wire:click="refreshSwagger"
            class="btn"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        <i class="fa-solid fa-globe"></i> Swagger refresh
    </button>

    <h2 class="mt-6">NPM Befehle</h2>

    <!-- Button für npm run dev -->
    {{-- <button wire:click="npmRunDev"
            class="px-4 py-2 mt-2 text-white bg-indigo-500 rounded hover:bg-indigo-700"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        npm run dev
    </button> --}}

    <!-- Button für npm run build -->
    <button wire:click="npmRunBuild"
            class="btn"
            wire:loading.attr="disabled" wire:loading.class="opacity-50">
        <i class="fa-brands fa-npm"></i> npm run build
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
