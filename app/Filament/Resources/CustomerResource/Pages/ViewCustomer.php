<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
//use Filament\Resources\Pages\view;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use App\Filament\Resources\ContactResource;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    public function getTitle(): string
    {
        return $this->record->name. ", " .$this->record->first_name; // Verwendet das name-Feld des Kunden
    }

    /* public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name') // Vereinfachter Aufruf
                    ->label('Name'),
                TextEntry::make('email')
                    ->label('E-Mail'),
                TextEntry::make('phone')
                    ->label('Telefon'),
            ])
            ->columns(2);
    } */

    public function getHeaderActions(): array
    {
        return [
            Action::make('createContact')
                ->label('Neuen Kontakt anlegen')
                ->icon('heroicon-o-plus')
                ->url(fn () => ContactResource::getUrl('create', ['customer_id' => $this->record->id]))
                ->color('primary'),
            Action::make('bearbeiteKunde')
                ->label('Kunde bearbeiten')
                ->icon('heroicon-o-pencil')
                ->url(fn () => CustomerResource::getUrl('edit', ['record' => $this->record->id]))
                ->color('primary')
                ->disabled(function ($record) {
                    return !auth()->user()->can('edit', $record);
                }),
        ];
    }
}
