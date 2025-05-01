<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterSave()
    {
        // Weiterleitung zur Übersicht nach dem Speichern
        return redirect()->route('filament.admin.resources.users.index');
    }
}
