<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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

}
