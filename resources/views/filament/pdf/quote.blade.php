<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Angebot {{ $quote->quote_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
        .text-right{
            text-align: right !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Angebot {{ $quote->quote_number }}</h1>
        <p>Gültig bis: {{ \Carbon\Carbon::parse($quote->valid_until)->format('d.m.Y') }}</p>
    </div>

    <h3>Kundendaten</h3>
    <p>{{ $quote->customer->name }}, {{ $quote->customer->first_name }}</p>

    <h3>Produkte</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Produkt</th>
                <th class="text-right">Menge</th>
                <th class="text-right">Einzelpreis</th>
                <th class="text-right">Gesamtpreis</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->quoteProducts as $product)
                <tr>
                    <td>{{ $product->product->name }}</td>
                    <td class="text-right">{{ $product->quantity }}</td>
                    <td class="text-right">{{ number_format($product->unit_price, 2, ',', '.') }} €</td>
                    <td class="text-right">{{ number_format($product->unit_price * $product->quantity, 2, ',', '.') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Gesamtbetrag: {{ number_format($quote->total_amount, 2, ',', '.') }} €</p>

    @if($quote->terms)
        <h3>AGB</h3>
        <p>{{ $quote->terms }}</p>
    @endif

    @if($quote->notes)
        <h3>Bemerkungen</h3>
        <p>{{ $quote->notes }}</p>
    @endif
</body>
</html>
