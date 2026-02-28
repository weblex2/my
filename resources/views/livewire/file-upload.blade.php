<div x-data x-on:dragover.prevent
    x-on:drop.prevent="$refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
    class="p-10 text-center text-gray-600 border-4 border-gray-300 border-dashed rounded-lg" style="cursor: pointer"
    x-on:click="$refs.fileInput.click()">
    <p>Ziehe Dateien hierher oder klicke zum Hochladen</p>

    <input type="file" multiple wire:model="files" x-ref="fileInput" class="hidden" />

    @error('files.*')
        <span class="text-red-500">{{ $message }}</span>
    @enderror

    <div wire:loading wire:target="files" class="mt-4 text-blue-500">
        <div class="text-center">
            <svg class="w-10 h-10 mx-auto mb-4 text-gray-800 animate-spin" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <p class="text-gray-800">Datei wird hochgeladen...</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mt-4 text-green-600">{{ session('success') }}</div>
    @endif
</div>
