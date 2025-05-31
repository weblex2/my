<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BankTransaction;

class SimpleFilter extends Component
{
    public $bookingDate = '';

    public function render()
    {
        $query = BankTransaction::query();

        if ($this->bookingDate) {
            $query->whereDate('booking_date', $this->bookingDate);
        }

        $transactions = $query->orderByDesc('booking_date')->get();

        return view('livewire.simple-filter', [
            'transactions' => $transactions,
        ]);
    }
}
