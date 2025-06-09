<?php

namespace App\Filament\Resources\FilTableFieldsResource\Pages;

use App\Filament\Resources\FilTableFieldsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\Rule;

class CreateFilTableFields extends CreateRecord
{
    protected static string $resource = FilTableFieldsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormValidationRules(): array
    {
        return [
            'data.field' => [
                'required',
                Rule::unique('fil_table_fields')->where(function ($query) {
                    $form = $this->form->getState('form');
                    $table = $this->form->getState('table');
                    return $query->where('form', $form)
                                 ->where('table', $table);
                }),
            ],
            'data.table' => ['required'],
            'data.form' => ['required'],
            // ... andere Regeln falls nötig
        ];
    }

}
