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
                        $icons = [
                            'email' => 'email.svg',
                            'phone' => 'phone.svg',
                        ];

                        $icon = 'default.svg';

                         foreach ($icons as $type => $filename) {
                            if ($state == $type) {
                                $icon = $filename;
                                break;
                            }
                        }

                        $labels = [
                            'phone' => 'Telefonat',
                            'email' => 'E-Mail',
                        ];
                        $label = $labels[$state] ?? ucfirst($state);
                        return '<img src="' . asset('img/icons/' . $icon) . '" alt="Icon" class="inline w-5 h-5 mr-2 align-middle rounded-full" />&nbsp;' . $label;
                    })
                    ->html()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contacted_at')->label('Kontaktzeitpunkt')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('d.m.Y H:i')),
                Tables\Columns\TextColumn::make('subject')->label('Subject')
                    ->url(fn ($record) => route('filament.admin.resources.contacts.view', ['record' => $record]))
                    ->limit(50),
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
                    ->color('gray'),
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
