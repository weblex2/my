<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Filament\Resources\ContactResource;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'Kontakte';
    protected static ?string $navigationGroup = 'Customers';

    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string  {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Kunde')
                    ->relationship(
                        'customer',
                        'name', // das brauchst du trotzdem für die Zuordnung, wird aber nicht angezeigt
                        fn ($query) => $query->orderBy('name') // optional: sortieren
                    )
                    ->default(fn () => request()->get('customer_id'))
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}, {$record->first_name}")
                    ->searchable() // optional: damit man auch im Dropdown suchen kann
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Typ')
                    ->options([
                        'tel' => 'Telefonat',
                        'email' => 'Email',
                        // Weitere Typen nach Bedarf
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contacted_at')
                    ->label('Kontaktzeitpunkt')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Typ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Nachname')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.first_name')
                    ->label('Vorname')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('details')
                    ->label('Details')
                    ->limit(50),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
            'view' => Pages\ViewContact::route('/{record}'),
        ];
    }



}
