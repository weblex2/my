<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form; // Korrektes Import-Statement
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\ContactResource;
use App\Filament\Resources\CustomerResource;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    protected static ?string $recordTitleAttribute = 'type';

    public function form(Form $form): Form // Typisierung auf Filament\Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Typ')
                    ->options([
                        'phone' => 'Telefonat',
                        'email' => 'Email',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('details')
                    ->label('Details')
                    ->rows(4),
                Forms\Components\DateTimePicker::make('contacted_at')
                    ->label('Kontaktzeitpunkt')
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Typ')
                    ->formatStateUsing(function ($state) {
                        $labels = [
                            'phone' => 'Telefonat',
                            'email' => 'E-Mail',
                        ];
                        $label = $labels[$state] ?? ucfirst($state);
                        return $label;
                    })
                    ->icon(function ($record) {
                        $userEmail = Auth::user()?->email;

                        if ($record->type === 'email') {
                            // Eingehend, wenn User Empfänger ist
                            if (in_array($userEmail, explode(',',$record->to))) {
                                return 'heroicon-o-arrow-down-tray'; // Beispiel: eingehend
                            }

                            // Ausgehend, wenn User Absender ist
                            if ($record->from === $userEmail) {
                                return 'heroicon-o-arrow-up-tray'; // Beispiel: ausgehend
                            }

                            // Fallback
                            return 'heroicon-o-envelope';
                        }

                        // Für andere Typen z. B. phone
                        $icons = [
                            'phone' => 'heroicon-o-phone',
                        ];

                        return $icons[$record->type] ?? 'heroicon-o-question-mark-circle';
                    })
                    ->iconColor(function ($record) {
                        $userEmail = Auth::user()?->email;

                        if ($record->type === 'email') {
                            if (in_array($userEmail, explode(',', $record->to))) {
                                return 'success'; // Eingehend – grün
                            }

                            if ($record->from === $userEmail) {
                                return 'warning'; // Ausgehend – orange
                            }

                            return 'gray'; // Neutral
                        }

                        // Für andere Typen wie "phone"
                        if ($record->type === 'phone') {
                            return 'info'; // z. B. blau
                        }

                        return 'secondary'; // Fallback
                    })
                    //->html()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')->label('Subject')
                    ->url(fn ($record) => route('filament.admin.resources.contacts.view', ['record' => $record]))
                    ->color('primary')
                    ->limit(50),
                Tables\Columns\TextColumn::make('contacted_at')->label('Kontaktzeitpunkt')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('d.m.Y H:i')),
                Tables\Columns\TextColumn::make('from')->label('From'),
                Tables\Columns\TextColumn::make('to')->label('To'),


                Tables\Columns\TextColumn::make('details')->label('Details')->limit(50),

            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Von'),
                        Forms\Components\DatePicker::make('until')->label('Bis'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('neuerKontakt')
                    ->label('Neuen Kontakt anlegen')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->label('Typ')
                            ->options([
                                'phone' => 'Telefonat',
                                'email' => 'Email',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('details')
                            ->label('Details')
                            ->rows(4),
                        Forms\Components\DateTimePicker::make('contacted_at')
                            ->label('Kontaktzeitpunkt')
                            ->default(now()),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        $livewire->getRelationship()->create(array_merge($data, [
                            'customer_id' => $livewire->ownerRecord->id,
                        ]));
                         // Toast-Nachricht anzeigen
                        Notification::make()
                            ->title('Kontakt gespeichert')
                            ->body('Der Kommentar wurde erfolgreich gespeichert.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('bearbeiteKunde')
                    ->label('Kunde bearbeiten')
                    ->icon('heroicon-o-pencil')
                    ->url(fn () => CustomerResource::getUrl('edit', ['record' => $this->ownerRecord]))
                    ->color('primary')
                    ->disabled(function ($record) {
                        return !auth()->user()->can('edit', $this->ownerRecord);
                    }),
                Tables\Actions\Action::make('importEmails')
                    ->label('E-Mails importieren')
                    ->icon('heroicon-o-inbox')
                    ->color('primary')
                    ->modalHeading('E-Mails importieren')
                    ->modalContent(fn () => view('filament.actions.import-emails-modal', [
                        'customerId' => $this->ownerRecord->id,
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

}
