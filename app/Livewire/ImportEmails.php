<?php

namespace App\Livewire;

use Livewire\Component;
use App\Http\Controllers\ZimbraController;
use Illuminate\Support\Facades\Log;

class ImportEmails extends Component
{
    public $customerId;
    public $isOpen = true; // Modal direkt geöffnet
    public $result = null;
    public $loading = false;

    public function mount($customerId)
    {
        $this->customerId = $customerId;
        $this->isOpen = true;
        $this->result = null;
        $this->loading = false;
    }

    public function import()
    {
        $this->loading = true;

        try {
            $zimbraController = app(ZimbraController::class);
            $this->result = $zimbraController->importEmails($this->customerId);
            $this->loading = false;
        } catch (\Exception $e) {
            $this->result = [
                'status' => 'error',
                'emails_imported' => 0,
                'documents_imported' => 0,
                'errors' => ['Fehler beim Import: ' . $e->getMessage()],
            ];
            Log::error('Fehler beim E-Mail-Import in Livewire', ['error' => $e->getMessage()]);
            $this->loading = false;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->result = null;
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.import-emails');
    }
}

?>
