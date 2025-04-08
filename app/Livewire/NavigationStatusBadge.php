<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;

class NavigationStatusBadge extends Component
{
    public $status;

    public function render()
    {
        // Berechne die Anzahl der Kunden mit dem angegebenen Status
        $count = Customer::where('status', $this->status)->count();

        // Übergabe der $count-Variable an die View
        return view('livewire.navigation-status-badge', ['count' => $count]);
    }
}
