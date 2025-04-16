<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Redirect;
use Log;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    // Override saved method to handle redirection after saving
    protected function saved()
    {

        Log::info('Saved method called for customer', [
            'customer_id' => $this->record->id,
            'status' => $this->record->status
        ]);
        // Hole den Status des Kunden nach dem Speichern
        $status = $this->record->status ?: 'alle'; // Wenn kein Status gesetzt ist, 'alle' als Default

        // Weiterleitung zur Kundenübersicht mit dem Filter für den Status
        return $this->redirect(route('filament.resources.customers.index', [
            'tableFilters' => [
                'status' => ['value' => $status],
            ]
        ]));
    }

    protected function afterSave()
    {
        Log::info('afterSave method called for customer', [
            'customer_id' => $this->record->id,
            'status' => $this->record->status
        ]);

        // Hole den Status des Kunden nach dem Speichern
        $status = $this->record->status ?: 'alle'; // Wenn kein Status gesetzt ist, 'alle' als Default

        // Loggen, bevor die Weiterleitung durchgeführt wird
        Log::info('Redirecting to customer index with status filter', [
            'status' => $status
        ]);

        // Weiterleitung zur Kundenübersicht mit dem Filter für den Status
        return Redirect::route('filament.admin.resources.customers.index', [
            'tableFilters' => [
                'status' => ['value' => $status],
            ]
        ]);
    }
}
