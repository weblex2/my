<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Resources;

class Setup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationGroup(): ?string
    {
        return \App\Models\Resources::where('resource', 'setup')->value('navigation_group') ?? null;
    }

    protected static string $view = 'filament.pages.setup';
}
