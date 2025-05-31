<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BankTransaction;

class BankTransactionTable extends Component
{
    public $bookingDate = '';
    public $valueDate = '';
    public $category = '';
    public $counterparty = '';
    public $categorySums = [];
    protected $listeners = ['filterByCategory'];

    public function mount()
    {
        $this->categorySums = \App\Models\BankTransaction::selectRaw('category, SUM(amount) as sum')
            ->groupBy('category')
            ->pluck('sum', 'category')
            ->toArray();
    }



    public function filterByCategory($payload)
    {
        $this->category = $payload['category'];
    }

    public function render()
    {
        $query = BankTransaction::query();

        if (!empty($this->bookingDate)) {
            $query->whereDate('booking_date', $this->bookingDate);
        }

        if (!empty($this->valueDate)) {
            $query->whereDate('value_date', $this->valueDate);
        }

        if (!empty($this->category)) {
            $query->where('category', 'like', '%' . $this->category . '%');
        }

        if (!empty($this->counterparty)) {
            $query->where('counterparty', 'like', '%' . $this->counterparty . '%');
        }

         // Neue Kategorie-Summen basierend auf gefiltertem Query
        $this->categorySums = (clone $query)
            ->selectRaw('category, SUM(amount) as sum')
            ->where('counterparty', '!=','ASSD GmbH')
            ->groupBy('category')
            ->pluck('sum', 'category')
            ->toArray();
        $this->dispatch('updateChart', categorySums: $this->categorySums);
        return view('livewire.bank-transaction-table', [
            'transactions' => $query->orderByDesc('value_date')->get(),
        ]);
    }
}
