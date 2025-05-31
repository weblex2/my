<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BankTransaction;

class BankTransactionTable extends Component
{
    public $bookingDateFrom = '';
    public $bookingDateTo = '';
    public $valueDateFrom = '';
    public $valueDateTo = '';
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



    public function filterByCategory($category)
    {
        $this->category = $category;
    }

    public function render()
    {
        $query = BankTransaction::query();

        if (!empty($this->bookingDateFrom) && !empty($this->bookingDateTo)) {
            $query->whereBetween('booking_date', [$this->bookingDateFrom, $this->bookingDateTo]);
        } elseif (!empty($this->bookingDateFrom)) {
            $query->whereDate('booking_date', '>=', $this->bookingDateFrom);
        } elseif (!empty($this->bookingDateTo)) {
            $query->whereDate('booking_date', '<=', $this->bookingDateTo);
        }

        if (!empty($this->valueDateFrom) && !empty($this->valueDateTo)) {
            $query->whereBetween('value_date', [$this->valueDateFrom, $this->valueDateTo]);
        } elseif (!empty($this->valueDateFrom)) {
            $query->whereDate('value_date', '>=', $this->valueDateFrom);
        } elseif (!empty($this->valueDateTo)) {
            $query->whereDate('value_date', '<=', $this->valueDateTo);
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
