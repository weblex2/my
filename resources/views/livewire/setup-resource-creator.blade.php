@vite('resources/css/crm.css')
<div>
    <x-filament::card>
        <form wire:submit.prevent="createResource" class="space-y-4">
            @php
                $this->showResourceNameInput = true;
            @endphp

            {{ $this->form }}

            <x-filament::button type="submit">
                Ressource erstellen
            </x-filament::button>

            @if (session()->has('success'))
                <p class="font-medium text-success-600">
                    {!! session('success') !!}
                </p>
            @endif
        </form>
    </x-filament::card>


    <div class="resource-wrapper">
    @if ($resources && count($resources) > 0)
        <h2 class="mt-8 text-xl font-semibold">Existierende Resources:</h2>

        <div class="resource-grid">
            @foreach ($resources as $index => $resource)
                <div>
                    <x-filament::card>
                        <h3 class="mb-2 font-bold">{{ $resource['name'] }}</h3>

                        <form wire:submit.prevent="saveResource" class="space-y-4">
                            @php
                                $this->showResourceNameInput = false;
                            @endphp

                            {{ $this->form }}

                            <x-filament::button type="submit">Save</x-filament::button>
                        </form>
                    </x-filament::card>
                </div>
            @endforeach
        </div>
    @else
        <p class="mt-4 text-gray-500">Keine Resources gefunden.</p>
    @endif
    </div>

</div>
