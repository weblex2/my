<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
//use Filament\Resources\Pages\view;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Resources\Pages\ViewRecord;

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
}
