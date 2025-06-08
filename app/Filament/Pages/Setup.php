<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Setup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Configuration';

    protected static string $view = 'filament.pages.setup';
}
