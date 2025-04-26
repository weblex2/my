<div>
    <!-- Modal Content -->
    <div class="space-y-4">
        <!-- Empfänger E-Mail -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Empfänger E-Mail</label>
            <x-filament::input id="email" wire:model.defer="recipient_email" type="email" />
            @error('recipient_email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Betreff -->
        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700">Betreff</label>
            <x-filament::input id="subject" wire:model.defer="subject" type="text" class="!border !border-white" />
            @error('subject') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Nachricht -->
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700">Nachricht</label>
            <!-- Statt x-filament::textarea ein normales textarea -->
            <textarea id="message" wire:model.defer="message" rows="4" class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
            @error('message') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end space-x-2">
            <x-filament::button type="button" wire:click="$dispatch('close-modal', { id: 'send_email' })" wire:loading.attr="disabled" color="secondary">
                Abbrechen
            </x-filament::button>
            <x-filament::button type="button" wire:click="sendEmail" wire:loading.attr="disabled" color="primary">
                Senden
            </x-filament::button>
        </div>
    </div>
</div>
