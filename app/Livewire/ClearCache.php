<?php

namespace App\Livewire;

use Livewire\Component;
use Artisan;

class ClearCache extends Component
{
    public $message = '';
    public $isProcessing = false;

    // Funktion zum Leeren des Caches
    public function clearCache()
    {
        $this->message = 'Leere Cache...<br>';
        Artisan::call('cache:clear');
        $this->message .= 'Cache wurde gelöscht.<br>';
    }

    // Funktion zum Leeren der Konfiguration
    public function clearConfig()
    {
        $this->message = 'Leere Config...<br>';
        Artisan::call('config:clear');
        $this->message .= 'Config wurde gelöscht.<br>';
    }

    // Funktion zum Leeren der Views
    public function clearViews()
    {
        $this->message = 'Leere Views...<br>';
        Artisan::call('view:clear');
        $this->message .= 'Views wurden gelöscht.<br>';
    }

    // Funktion zum Leeren von allen Caches
    public function clearAll()
    {
        $this->message = 'Leere Cache...<br>';
        Artisan::call('cache:clear');
        $this->message .= 'Cache wurde gelöscht.<br>';

        $this->message .= 'Leere Config...<br>';
        Artisan::call('config:clear');
        $this->message .= 'Config wurde gelöscht.<br>';

        $this->message .= 'Leere Views...<br>';
        Artisan::call('view:clear');
        $this->message .= 'Views wurden gelöscht.<br>';
    }

    public function render()
    {
        return view('livewire.clear-cache');
    }
}
