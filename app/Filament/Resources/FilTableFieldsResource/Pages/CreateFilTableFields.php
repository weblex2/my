<?php

namespace App\Filament\Resources\FilTableFieldsResource\Pages;

use App\Filament\Resources\FilTableFieldsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\FilTableFields;
use Filament\Notifications\Notification;

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

    /* protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = FilTableFields::where('form', $data['form'])
            ->where('table', $data['table'])
            ->where('field', $data['field'])
            ->exists();

        if ($exists) {
            $message = 'Ein Eintrag mit dieser Kombination aus Form, Tabelle und Feld existiert bereits.';

            throw ValidationException::withMessages([
                'form' => $message,
                'table' => $message,
                'field' => $message,
            ]);
        }

        return $data;
    } */
   protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = \App\Models\FilTableFields::where('form', $data['form'])
            ->where('table', $data['table'])
            ->where('field', $data['field'])
            ->exists();

        if ($exists) {
            Notification::make()
                ->title('Duplikat gefunden')
                ->body('Ein Eintrag mit diesen Werten existiert bereits.')
                ->danger()
                ->send();
            throw ValidationException::withMessages([
                'data.form' => 'Ein Eintrag mit diesen Werten existiert bereits.',
                'data.table' => 'Ein Eintrag mit diesen Werten existiert bereits.',
                'data.field' => 'Ein Eintrag mit diesen Werten existiert bereits.',
            ]);
        }

        return $data;
    }
}
