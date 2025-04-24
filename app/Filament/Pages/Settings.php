<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Cache;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static string $view = 'filament.pages.settings';
    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $navigationGroup = 'Pages';

    public $settings = [];

    public function mount(): void
    {
        $this->settings = Cache::get('settings', [
            'site_name' => config('app.name'),
            'email_notifications' => true,
        ]);

        $this->form->fill($this->settings);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('site_name')
                ->label('Site Name')
                ->required(),
            Forms\Components\Toggle::make('email_notifications')
                ->label('Enable Email Notifications'),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        Cache::put('settings', $data);
        $this->notify('success', 'Settings saved successfully!');
    }
}
