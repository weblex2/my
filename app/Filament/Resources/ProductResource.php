<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\ProductTypeEnum;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Super Products';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        $x = 1;
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Group1')
                    ->schema([
                        Forms\Components\TextInput::make('name'),
                        Forms\Components\TextInput::make('slug'),
                    ])->columns(2), 
                    Forms\Components\Section::make('Pricing & Inventory')
                    ->schema([
                        Forms\Components\TextInput::make('sku'),
                        Forms\Components\TextInput::make('price'),
                        Forms\Components\TextInput::make('quantity'),
                        Forms\Components\Select::make('type')
                            ->options([
                                'downloadable' => ProductTypeEnum::DOWNLOADABLE->value ,
                                'deliverable' => ProductTypeEnum::DELIVERABLE->value,
                            ]),
                    ])->columns(2),
                    Forms\Components\Section::make('Description')
                        ->schema([
                            Forms\Components\MarkdownEditor::make('description')->columnSpan('full'),
                        ])
                    ]),
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_visible'),
                        Forms\Components\Toggle::make('is_featured'),
                        Forms\Components\DatePicker::make('published_at'),
                    ]),
                    Forms\Components\Section::make('Pricing & Inventory')
                        ->schema([
                            Forms\Components\FileUpload::make('image'),
                        ])->collapsible(),  
                    ]), 

                    Forms\Components\Section::make('Associations')
                        ->schema([
                            Forms\Components\Select::make('brand_id')
                                ->relationship('brand', 'name')
                    ]),  
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('brand.name'),
                Tables\Columns\IconColumn::make('is_visible')->boolean(),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('published_at'),
                Tables\Columns\TextColumn::make('type'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
