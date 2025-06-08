<x-filament::card>
    <form wire:submit.prevent="createResource" class="space-y-4">
        {{ $this->form }}

        <x-filament::button type="submit">
            Ressource erstellen
        </x-filament::button>

        @if (session()->has('success'))
            <p class="font-medium text-success-600">
                {{ session('success') }}
            </p>
        @endif
    </form>
</x-filament::card>
