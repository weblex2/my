<form wire:submit.prevent="save">
    {{ $this->form }}

    <x-filament::button type="submit" class="mt-4">
        Regel speichern
    </x-filament::button>

    @if (session()->has('success'))
        <p class="mt-2 text-green-600">{{ session('success') }}</p>
    @endif
</form>
