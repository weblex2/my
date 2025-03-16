<?php

namespace App\Filament\Resources\FilTableFieldsResource\Pages;

use App\Filament\Resources\FilTableFieldsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFilTableFields extends ListRecords
{
    protected static string $resource = FilTableFieldsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
