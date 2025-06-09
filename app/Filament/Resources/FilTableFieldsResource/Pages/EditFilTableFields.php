<?php

namespace App\Filament\Resources\FilTableFieldsResource\Pages;

use App\Filament\Resources\FilTableFieldsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFilTableFields extends EditRecord
{
    protected static string $resource = FilTableFieldsResource::class;

    public function getTitle(): string
    {
        return "Edit Customer Field";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
