
<div class="relative items-center font-black bg-white">
    <div class="fixed inset-0 z-50 flex items-center justify-center hidden w-screen h-screen bg-black bg-opacity-25" wire:loading.class.remove="hidden" wire:loading.class="flex">
        <div class="p-4 text-lg text-gray-700 bg-white border rounded shadow">
            Daten werden geladen...
        </div>
    </div>

    <div class="w-[25%] inset-0 z-40 flex items-center justify-center bg-white">
        <div class="w-full h-full p-4">
            <canvas id="myPieChart" class="w-full h-full"></canvas>
        </div>
    </div>
    <div class="flex justify-center w-full gap-4 mb-4">

        <div>
            Booking Date From
            <input type="date" wire:model.live="bookingDateFrom" class="p-1 border" placeholder="Booking Date From">
            Booking Date To
            <input type="date" wire:model.live="bookingDateTo" class="p-1 border" placeholder="Booking Date To">
        </div>
        <div>
            Value Date From
            <input type="date" wire:model.live="valueDateFrom" class="p-1 border" placeholder="Value Date From">
            Value Date To
            <input type="date" wire:model.live="valueDateTo" class="p-1 border" placeholder="Value Date To">
        </div>
        <div>
            Category
            <input type="text" wire:model.change="category" wire:keydown.enter="$refresh" class="p-1 border" placeholder="Category">
        </div>
        <div>
            Counterparty
            <input type="text" wire:model.change="counterparty" wire:keydown.enter="$refresh" class="p-1 border" placeholder="Counterparty">
        </div>
    </div>
    <div class="flex justify-center w-full">
    <table class="striped-table">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-2 border">Booking Date</th>
                <th class="p-2 border">Value Date</th>
                <th class="p-2 border">Auftragskonto</th>
                <th class="p-2 border">Buchungstext</th>
                <th class="p-2 border">Verwendungszweck</th>
                <th class="p-2 border">Category</th>
                <th class="p-2 border">Counterparty</th>
                <th class="p-2 border">Amount</th>
                {{-- "Beguenstigter/Zahlungspflichtiger";"Kontonummer/IBAN";"BIC (SWIFT-Code)";"Betrag";"Waehrung";"Info";"Kategorie" --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $tx)
                <tr>
                    <td class="p-2 border">{{ $tx->booking_date->format('d.m.Y') }}</td>
                    <td class="p-2 border">{{ $tx->value_date->format('d.m.Y') }}</td>
                    <td class="p-2 border">{{ $tx->account_number }}</td>
                    <td class="p-2 border">{{ $tx->booking_text }}</td>
                    <td class="p-2 border">{{ $tx->purpose }}</td>
                    <td class="p-2 border">{{ $tx->category }}</td>
                    <td class="p-2 border">{{ $tx->counterparty }}</td>
                    <td class="p-2 text-right border">{{ number_format($tx->amount, 2) }} {{ $tx->currency }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-2 text-center">No transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    {{-- <div class="mt-4">
        {{ $transactions->links() }}
    </div> --}}
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const data = @json($categorySums);
        const ctx = document.getElementById('myPieChart').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    label: 'Umsätze nach Kategorie',
                    data: Object.values(data),
                    backgroundColor: [
                        '#ff6384', '#36a2eb', '#ffce56', '#4caf50', '#8e44ad',
                        '#e67e22', '#2ecc71', '#f1c40f', '#3498db', '#e74c3c',
                        '#1abc9c', '#9b59b6', '#34495e', '#fd79a8', '#e17055',
                        '#00cec9', '#6c5ce7', '#fab1a0', '#81ecec', '#d63031'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                onClick: function (evt, activeEls) {
                    const chart = this;
                    if (activeEls.length > 0) {
                        const index = activeEls[0].index;
                        const category = chart.data.labels[index];
                        Livewire.dispatch('filterByCategory', { category });
                    }
                }
            }
        });
    });
</script>
@endpush

