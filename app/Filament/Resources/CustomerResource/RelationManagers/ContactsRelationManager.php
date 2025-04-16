<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form; // Korrektes Import-Statement
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\ContactResource;


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
                Tables\Columns\TextColumn::make('details')->label('Details')->limit(50),
                Tables\Columns\TextColumn::make('contacted_at')->label('Kontaktzeitpunkt')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
