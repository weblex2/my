<div class="w-1/2 p-6 mx-auto bg-white rounded-lg shadow-lg">
    <h2 class="mb-4 text-xl font-bold">Frage an Gemini stellen</h2>

    <textarea wire:model="message" class="w-full p-2 border rounded" placeholder="Gib deine Frage hier ein..."></textarea>

    <button wire:click="sendMessage" class="px-4 py-2 mt-2 text-white bg-blue-500 rounded hover:bg-blue-600" {{ $loading ? 'disabled' : '' }}>
        Absenden
    </button>

    <!-- Ladeanimation -->
    <div wire:loading class="mt-4 text-blue-500">
        ⏳ Lade Antwort...
    </div>

    @if($responseText)
        <div class="p-4 mt-4 bg-gray-200 rounded">
            <h3 class="font-semibold">Antwort:</h3>
            <div class="overflow-auto max-h-52">{!! $responseText !!}</div>
        </div>
    @endif
</div>
