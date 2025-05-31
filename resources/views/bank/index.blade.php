<x-noppal>
    <x-slot name="header">
         <h1>Bank</h1>
    </x-slot>
    <div>
        @livewire('bank-transaction-table')
        {{-- @livewire('simple-filter') --}}

        {{-- <table class="w-full border-red-700">
            <tr>
                <th class="p-2 text-xs border">Booking Date</th>
                <th class="p-2 text-xs border">Balue Date</th>
                <th class="p-2 text-xs border">Booking Text</th>
                <th class="p-2 text-xs border">Purpose</th>
                <th class="p-2 text-xs border">Counter Party</th>
                <th class="p-2 text-xs border">Amount</th>
                <th class="p-2 text-xs border">Info</th>
                <th class="p-2 text-xs border">Category</th>
                @foreach ($transactions as $i => $trans)

                    <tr class="">
                        <td class="p-2 text-xs border !border-zinc-700 whitespace-nowrap ">{{ substr($trans->booking_date,0,10) }}</td>
                        <td class="p-2 text-xs border !border-zinc-700 whitespace-nowrap ">{{ substr($trans->value_date,0,10) }}</td>
                        <td class="p-2 text-xs border !border-zinc-700 whitespace-nowrap ">{{ $trans->booking_text }}</td>
                        <td class="p-2 text-xs border !border-zinc-700 whitespace-nowrap ">{{ $trans->purpose }}</td>
                        <td class="p-2 text-xs border !border-zinc-700 whitespace-nowrap ">{{ $trans->counterparty }}</td>
                        <td class="p-2 text-xs border !border-zinc-700 whitespace-nowrap text-black text-right {{$trans->amount>0 ? 'bg-green-300' : 'bg-red-300'}}">{{ $trans->amount }}</td>
                        <td class="p-2 text-xs border !border-zinc-700 whitespace-nowrap ">{{ $trans->info }}</td>
                        <td class="p-2 text-xs border !border-zinc-700 whitespace-nowrap ">{{ $trans->category }}</td>
                    </tr>
                @endforeach
        </table> --}}
    </div>

</x-noppal>
