<?php

namespace App\Livewire;

use Livewire\Component;
use Artisan;

class ClearCache extends Component
{
    public $message = '';
    public $isProcessing = false;

    // Die Funktion zum Leeren des Caches
    public function clearCache()
    {
        // Verhindern von mehrfachen Aufrufen während der Verarbeitung
        if ($this->isProcessing) {
            return;
        }

        $this->message = ''; // Clear previous messages
        $this->isProcessing = true; // Setze Verarbeitung auf "in Bearbeitung"

        // Schritt 1: Cache leeren
        $this->message .= 'Leere Cache...<br>';
        Artisan::call('cache:clear');
        $this->message .= 'Cache wurde gelöscht.<br>';

        // Schritt 2: Config leeren
        $this->message .= 'Leere Config...<br>';
        Artisan::call('config:clear');
        $this->message .= 'Config wurde gelöscht.<br>';

        // Schritt 3: Views leeren
        $this->message .= 'Leere Views...<br>';
        Artisan::call('view:clear');
        $this->message .= 'Views wurden gelöscht.<br>';

        $this->isProcessing = false; // Verarbeitung abgeschlossen
    }

    public function render()
    {
        return view('livewire.clear-cache');
    }
}
