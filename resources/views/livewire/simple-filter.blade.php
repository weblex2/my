<div style="max-width: 600px; margin: 2rem auto; font-family: sans-serif;">
    <label for="bookingDate">Booking Date Filter:</label>
    <input type="date" id="bookingDate" wire:model.live="bookingDate" style="margin-bottom:1rem; padding:0.5rem; width: 100%;">

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Booking Date</th>
                <th>Category</th>
                <th>Counterparty</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
                <tr>
                    <td>{{ $tx->booking_date }}</td>
                    <td>{{ $tx->category }}</td>
                    <td>{{ $tx->counterparty }}</td>
                    <td style="text-align: right;">{{ number_format($tx->amount, 2) }} {{ $tx->currency }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No transactions found for this date.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
