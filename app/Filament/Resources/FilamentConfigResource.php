<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FilamentConfigResource\Pages;
use App\Filament\Resources\FilamentConfigResource\RelationManagers;
use App\Models\FilamentConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class FilamentConfigResource extends Resource
{
    protected static ?string $model = FilamentConfig::class;

    protected static ?string $navigationLabel = 'Config';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('resource')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('field')
                    ->maxLength(191),
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('resource')
                    ->searchable(),
                Tables\Columns\TextColumn::make('field')
                    ->searchable(),
                Tables\Columns\TextColumn::make('key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                 SelectFilter::make('type')
                        ->options([
                        '' => 'Alle',
                        'navlink' => 'Navlink',
                        'option' => 'Filter Option',

                ]),
                SelectFilter::make('resource')
                        ->options([
                        '' => 'Alle',
                        'customer' => 'Customer',
                        'contact' => 'Contact',
                        'doc' => 'Document',
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListFilamentConfigs::route('/'),
            'create' => Pages\CreateFilamentConfig::route('/create'),
            'edit' => Pages\EditFilamentConfig::route('/{record}/edit'),
        ];
    }
}
