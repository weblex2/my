<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Actions\Action;

class ViewContact extends ViewRecord
{
    protected static string $resource = ContactResource::class;

    public function getTitle(): string
    {
        return "Kontakt  ". ($this->record->customer->name ?? 'Unbekannter Kunde') . " /  " . $this->record->contacted_at . " (Customer ID ". $this->record->customer->id .")" ;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
               /*  TextEntry::make('customer_id')
                    ->label('Customer ID'), */
                TextEntry::make('type')
                    ->label('Kontaktart'),
                TextEntry::make('subject')
                    ->label('Subject'),
                TextEntry::make('details')
                    ->label('Content')
                    ->state(fn($record) => nl2br(e($record->details)))
                    ->html()
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('replyEmail')
                ->label('E-Mail beantworten')
                ->icon('heroicon-o-envelope')
                ->url(fn () => 'mailto:' . $this->record->from . '?subject=Antwort auf: ' . urlencode($this->record->subject))
                ->openUrlInNewTab(),
        ];
    }
}
