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
                        'Telefonat' => 'Telefonat',
                        'Email' => 'Email',
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
                    //->url(fn ($record) => ContactResource::getUrl('view', ['record' => $record])),
                    ->url(fn ($record) => route('filament.admin.resources.contacts.view', ['record' => $record]))

                    //->openUrlInNewTab(), // optional,
                    ,
                Tables\Columns\TextColumn::make('contacted_at')->label('Kontaktzeitpunkt')->dateTime(),
                Tables\Columns\TextColumn::make('subject')->label('Subject')->limit(50),
                Tables\Columns\TextColumn::make('details')->label('Details')->limit(50),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('neuerKontakt')
                    ->label('Neuen Kontakt anlegen')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->label('Typ')
                            ->options([
                                'Telefonat' => 'Telefonat',
                                'Email' => 'Email',
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
