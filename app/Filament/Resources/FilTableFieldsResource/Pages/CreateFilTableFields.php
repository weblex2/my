<?php

namespace App\Filament\Resources\FilTableFieldsResource\Pages;

use App\Filament\Resources\FilTableFieldsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\Rule;

class CreateFilTableFields extends CreateRecord
{
    protected static string $resource = FilTableFieldsResource::class;

    public ?array $data = [];
    public function mount(): void
    {
        // Wenn duplicate_data vorhanden sind, fülle die Form mit diesen Daten
        if ($duplicateData = request()->query('duplicate_data')) {
            $this->form->fill(json_decode($duplicateData, true));
        } else {
            // Fülle die Form mit den Standardwerten aus dem Formularschema
            $this->form->fill();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /* protected function getFormValidationRules(): array
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
    } */

    protected function getFormData(): array
    {
        $duplicateData = request()->query('duplicate_data');
        if ($duplicateData) {
            return json_decode($duplicateData, true);
        }
        // Hole die Standardwerte aus dem Formularschema
        $form = $this->getForm();
        $schema = $form->getSchema();
        $defaultData = [];

        foreach ($schema->getComponents() as $component) {
            // Rekursiv durch Gruppen und Sektionen iterieren
            if ($component instanceof \Filament\Forms\Components\Group || $component instanceof \Filament\Forms\Components\Section) {
                foreach ($component->getChildComponents() as $childComponent) {
                    $defaultData[$childComponent->getName()] = $childComponent->getDefaultState();
                }
            } else {
                $defaultData[$component->getName()] = $component->getDefaultState();
            }
        }

        return $defaultData;
    }
}
