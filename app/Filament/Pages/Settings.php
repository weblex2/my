<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Cache;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class Settings extends Page
{
    use InteractsWithForms;


    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static string $view = 'filament.pages.settings';
    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $navigationGroup = 'Settings';

    public $settings = [];


    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('settings'); // Das ist wichtig!
    }

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
            Forms\Components\TextInput::make('imap_server')
                ->label('IMAP Server'),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        // Speichere die Einstellungen in der Datenbank (Tabelle general_settings)
        foreach ($data as $field => $value) {
            // Überprüfen, ob bereits ein Wert für dieses Feld existiert
            $setting = \App\Models\GeneralSetting::updateOrCreate(
                ['field' => $field],  // Bedingung: Suche nach 'field'
                ['value' => $value]    // Wenn nicht vorhanden, setze den Wert
            );
        }

        // Cache aktualisieren
        Cache::put('settings', $data);

        // Benachrichtigung senden
        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
        // Wenn du die Benachrichtigung in der Datenbank speichern willst:
        //auth()->user()->notify(new GeneralNotification('Settings saved successfully!'));
    }
}
