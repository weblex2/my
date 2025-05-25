<div>
    @if(session()->has('message'))
        <div class="mb-4 alert alert-success">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="submit">
        <label for="status" class="block mb-1 font-semibold">Kundenstatus</label>
        <select id="status" wire:model="status" class="w-full p-2 mb-4 border rounded">
            <option value="">Bitte wählen</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}">{{ $s }}</option>
            @endforeach
        </select>
        @error('status') <span class="text-red-600">{{ $message }}</span> @enderror

        <x-filament-forms::rich-editor wire:model.defer="content" label="Newsletter Inhalt" />

        @error('content') <span class="text-red-600">{{ $message }}</span> @enderror

        <div class="mt-6">
            <ul class="flex mb-4 border-b">
                <li class="mr-1 -mb-px">
                    <a
                        class="inline-block px-4 py-2 font-semibold bg-white border-t border-l border-r rounded-t cursor-pointer"
                        onclick="document.getElementById('editor-tab').classList.remove('hidden'); document.getElementById('preview-tab').classList.add('hidden'); this.classList.add('border-b-0'); document.getElementById('preview-tab-button').classList.remove('border-b-0');"
                    >Editor</a>
                </li>
                <li class="mr-1">
                    <a id="preview-tab-button"
                        class="inline-block px-4 py-2 font-semibold text-blue-500 bg-white border-b-2 border-blue-500 cursor-pointer"
                        onclick="document.getElementById('preview-tab').classList.remove('hidden'); document.getElementById('editor-tab').classList.add('hidden'); this.classList.add('border-b-0'); document.querySelector('a[onclick*=\"editor-tab\"]').classList.remove('border-b-0');"
                    >Vorschau</a>
                </li>
            </ul>

            <div id="editor-tab" class="">
                <!-- Editor ist oben -->
            </div>

            <div id="preview-tab" class="hidden p-4 prose border rounded max-w-none bg-gray-50">
                {!! $content !!}
            </div>
        </div>

        <button type="submit" class="inline-block px-6 py-2 mt-6 text-white transition bg-blue-600 rounded hover:bg-blue-700">
            Newsletter senden
        </button>
    </form>
</div>
