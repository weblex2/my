<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Quote;

class EditQuote extends EditRecord
{
    protected static string $resource = QuoteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        // Aktualisiere den Gesamtbetrag bei Änderungen
        $this->record->refresh();
        $this->record->update(['total_amount' => $this->record->total_amount]);
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('send')
                ->label('Versenden')
                ->icon('heroicon-o-paper-airplane')
                ->action(function () {
                    $this->record->update(['status' => 'sent']);
                    $this->refreshFormData(['status']);
                    Notification::make()
                        ->title('Angebot versendet')
                        ->success()
                        ->send();
                })
                ->visible(fn (): bool => $this->record->status === 'draft'),
        ];
    }

    protected function getCancelAction(): Action
    {
        return Action::make('cancel')
            ->label('Abbrechen')
            ->action(function () {
                \Log::info('Cancel button clicked in EditQuote'); // Debugging
                $this->redirect($this->getResource()::getUrl('index'), navigate: true);
            })
            ->color('gray')
            ->extraAttributes([
                'wire:loading.attr' => 'disabled', // Deaktiviert Button während Livewire-Anfrage
            ]);
    }

    public function previewPdf(Quote $quote)
    {
        $x = 1;
        $quote = Quote::find($id);
        $quote->customer = Customer::find($data['customer_id']);
        $quote->quoteProducts = collect($data['quoteProducts'])->map(function ($item) {
            $item['product'] = Product::find($item['product_id']);
            return (object) $item;
        });

        $pdf = Pdf::loadView('filament.pdf.quote', ['quote' => $quote]);

        return Response::streamDownload(
            fn () => print($pdf->output()),
            'Angebot_Vorschau.pdf'
        );
    }

}
