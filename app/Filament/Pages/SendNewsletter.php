<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SendNewsletter extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.pages.send-newsletter';

    protected static ?string $navigationLabel = 'Newsletter versenden';

    protected static ?string $title = 'Newsletter versenden';

    protected static ?string $slug = 'send-newsletter';

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.pages.send-newsletter-header');
    }
}
