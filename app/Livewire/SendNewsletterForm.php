<?php

namespace App\Livewire;

use App\Models\Customer;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class SendNewsletterForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $status = '';
    public $bcc = [];
    public $from = 'alex@noppenberger.org';
    public $to = 'alex@noppenberger.net';
    public $subject = '';
    public $content = '';

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Newsletter erstellen')
                ->schema([

                    TextInput::make('from')
                        ->label('Absender')
                        ->required()
                        ->email()
                        ->default('alex@noppenberger.org')
                        ->maxLength(255),
                    TextInput::make('to')
                        ->label('Empfänger')
                        ->required()
                        ->email()
                        ->default('alex@noppenberger.org')
                        ->maxLength(255),

                    Select::make('status')
                        ->label('Kundenstatus')
                        ->options([
                            'deal' => 'Deal',
                            'lead' => 'Lead',
                            'exacc' => 'Existing Account',
                            '' => 'All',
                        ])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('bcc', []);
                            if ($state) {
                                $emails = Customer::where('status', $state)
                                    ->pluck('email', 'email')
                                    ->toArray();
                                $set('bcc', array_keys($emails));
                            }
                        }),

                    Select::make('bcc')
                        ->label('BCC-Empfänger')
                        ->options(function (callable $get) {
                            $status = $get('status');
                            if (!$status) {
                                return [];
                            }
                            return Customer::where('status', $status)
                                ->pluck('email', 'email')
                                ->toArray();
                        })
                        ->multiple()
                        ->required()
                        ->reactive(),
                    TextInput::make('subject')
                        ->label('Betreff')
                        ->required()
                        ->maxLength(255),
                    Tabs::make('Inhalt')
                        ->tabs([
                            Tabs\Tab::make('Editor')
                                ->schema([
                                    MarkdownEditor::make('content')
                                        ->label('Inhalt')
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state) {
                                            $this->content = $state;
                                        }),
                                ]),
                            Tabs\Tab::make('Vorschau')
                                ->schema([
                                    \Filament\Forms\Components\View::make('livewire.send-newsletter-preview')
                                        ->viewData(['content' => $this->content]),
                                ]),
                        ]),
                ])
                ->extraAttributes(['class' => 'space-y-6'])
                ->footerActions([
                    \Filament\Forms\Components\Actions\Action::make('send')
                        ->label('Newsletter versenden')
                        ->action(function () {
                            Log::info('Action-Button geklickt');
                            $this->sendNewsletter();
                        }),
                ]),
        ];
    }

    public function sendNewsletter(): void
    {
        // Debugging: Prüfen, ob die Methode aufgerufen wird
        \Illuminate\Support\Facades\Log::info('sendNewsletter aufgerufen', $this->form->getState());

        $data = $this->form->getState();

        // Validierung: BCC nicht leer
        if (empty($data['bcc'])) {
            Notification::make()
                ->title('Fehler')
                ->body('Bitte wählen Sie mindestens einen BCC-Empfänger aus.')
                ->danger()
                ->send();
            return;
        }

        try {
            Mail::html(\Illuminate\Support\Str::markdown($data['content']), function ($message) use ($data) {
                $message->from($data['to'])
                        ->subject($data['subject'])
                        ->bcc($data['bcc']);
            });

            Notification::make()
                ->title('Newsletter versendet')
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mailversand fehlgeschlagen', ['error' => $e->getMessage()]);
            Notification::make()
                ->title('Fehler beim Versenden')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }

        $this->form->fill(['to' => $this->to]);
    }

    public function render()
    {
        return view('livewire.send-newsletter-form');
    }
}
