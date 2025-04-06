<?php

namespace App\Livewire;

use Livewire\Component;
use Artisan;

class ClearCache extends Component
{
    public $message = null;

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            $this->message = "✅ Alle Caches wurden erfolgreich gelöscht!";
        } catch (\Exception $e) {
            $this->message = "❌ Fehler beim Löschen der Caches: " . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.clear-cache');
    }
}
