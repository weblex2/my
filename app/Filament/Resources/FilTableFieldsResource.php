<?php

namespace App\Filament\Resources;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\FilTableFieldsResource\Pages;
use App\Filament\Resources\FilTableFieldsResource\RelationManagers;
use App\Models\FilTableFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FilTableFieldsResource extends Resource
{
    protected static ?string $model = FilTableFields::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Configure Fields';

    //protected static ?string $title = 'Meine benutzerdefinierte Seite';

    public static function getPluralLabel(): string
    {
        return 'Configure Fields'; // Neuer Titel
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                //Forms\Components\TextInput::make('user_id')->default(Auth::id()),

                Forms\Components\TextInput::make('table')->required()->disabled(),
                Forms\Components\TextInput::make('field')->required()->disabled(),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'text' => 'text',
                        'date' => 'date',
                        'dateTime' => 'dateTime',
                        'boolean' => 'boolean',
                        'select' => 'select',
                        'markup' => 'markdown',
                    ]),
                Forms\Components\TextInput::make('label')->required(),
                Forms\Components\Toggle::make('form')->required()->disabled(),
                Forms\Components\Toggle::make('sortable'),
                Forms\Components\Toggle::make('searchable'),
                Forms\Components\Toggle::make('disabled'),
                Forms\Components\Toggle::make('required'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\IconColumn::make('form')->boolean(),
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('table'),
                Tables\Columns\TextColumn::make('field'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\IconColumn::make('sortable')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('searchable')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('disabled')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('required')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                /* SelectFilter::make('user_id')
                    ->label('Benutzer')
                    ->options(User::pluck('name', 'id')->toArray()) // Alle Benutzer
                    ->default(Auth::id()), // Standardwert: aktueller Benutzer */
                TernaryFilter::make('form')
                    ->label('Formular / Übersicht') // Label für den Filter
                    ->trueLabel('Formular') // Was angezeigt wird, wenn true (1)
                    ->falseLabel('Übersicht'), // Was angezeigt wird, wenn false (0)
                SelectFilter::make('table')
                    ->options(function () {
                         // Hole alle distinct "table" Werte aus der Tabelle
                        $options = FilTableFields::select('table')
                            ->distinct()
                            ->pluck('table', 'table')
                            ->toArray();
                        // Füge die Option "Alle" hinzu
                        // Wir fügen den speziellen Wert für "Alle" zu den Optionen hinzu
                        return  $options;
                    })

                     ->query(function ($query, $state) {
                        // Wenn "Alle" ausgewählt wird (leerer Wert), keine Filterung anwenden
                        if ($state['value'] === '') {
                            return $query; // Gibt alle Datensätze zurück
                        }
                        // Andernfalls filtere nach dem ausgewählten "table" Wert
                        else{
                            $query->where('table', 'like' ,'%')->orderBy('table', 'DESC');
                        }
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function query(Builder $query): Builder
    {
        return $query->where('user_id', Auth::id());
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
            'index' => Pages\ListFilTableFields::route('/'),
            'create' => Pages\CreateFilTableFields::route('/create'),
            'edit' => Pages\EditFilTableFields::route('/{record}/edit'),
        ];
    }
}
