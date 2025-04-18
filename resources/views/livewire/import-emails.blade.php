<?php
// resources/views/livewire/import-emails.blade.php
?>
<div>
    <div>
        <h2 class="mb-4 text-lg font-semibold">E-Mails importieren</h2>
        loading {{ $loading }}
        @if ($loading)
            <div class="flex justify-center mb-4">
                <svg class="w-5 h-5 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        @elseif ($result)
            @if ($result['status'] === 'success')
                <div class="mb-4 text-green-600">
                    <p>Erfolgreich {{ $result['emails_imported'] }} E-Mails und {{ $result['documents_imported'] }} Dokumente importiert.</p>
                </div>
            @else
                <div class="mb-4 text-red-600">
                    <p>Fehler beim Import:</p>
                    <ul class="pl-5 list-disc">
                        @foreach ($result['errors'] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @else
            <p class="mb-4">Möchten Sie E-Mails für diesen Kunden importieren?</p>
            <button wire:click="import" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                Import starten
            </button>
        @endif

        <div class="flex justify-end mt-4">
            <button wire:click="closeModal" class="px-4 py-2 text-gray-700 bg-gray-300 rounded btn-primary hover:bg-gray-400">
                Schließen
            </button>
        </div>
    </div>
</div>
