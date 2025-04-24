<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // Berechne den endgültigen Gesamtbetrag
        $totalAmount = $this->record->quoteProducts->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $this->record->update([
            'total_amount' => $totalAmount,
        ]);

        // Aktualisiere die total_price Werte für jedes Produkt
        foreach ($this->record->quoteProducts as $product) {
            $product->update([
                'total_price' => $product->quantity * $product->unit_price
            ]);
        }
    }
}
