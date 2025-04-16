<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\CustomerResource\Pages;
use App\Http\Controllers\FilamentFieldsController;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Persons & Customers';

    protected static ?int $navigationSort = 2;



    public static function getNavigationBadge(): ?string  {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        $fc = new FilamentFieldsController('customer',1);
        $form_fields = $fc->getFields();

        return $form
            ->schema([

                /* $form_fields[0],
                $form_fields[1],
                $form_fields[2],
                $form_fields[3],
                $form_fields[4], */
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('external_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    '' => 'Alle',
                    'exacc' => 'Existing Account',
                    'deal' => 'Deal',
                    'lead' => 'Lead',
                    'contact' => 'Contact',
                ]),

                Forms\Components\Toggle::make('is_active')
                    ->required(),
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'company_name'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('comments')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        $fc = new FilamentFieldsController('customer',0);
        $form_fields = $fc->getFields();
        return $table
            ->columns(
                    //$form_fields,
                    [
               /*  Tables\Columns\TextColumn::make('actions')
                    ->label('Actions') // Kein Label, um Platz zu sparen
                    ->getStateUsing(fn () => '') // Kein Inhalt, nur Platzhalter
                    ->html()
                    ->formatStateUsing(fn ($record) => view('filament.tables.actions', [
                        'record' => $record,
                    ])->render())
                ->extraAttributes(['class' => 'w-16']),
                */
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.company_name'),

                Tables\Columns\TextColumn::make('name')
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record])), // Link zur View-Seite,
                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('preferredAddress.address')
                    ->label('Address')
                    ->searchable(),
                /* Tables\Columns\TextColumn::make('preferredAddress.address2')
                    ->label('Address 2')
                    ->searchable(), */
                Tables\Columns\TextColumn::make('preferredAddress.city')
                    ->label('City')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preferredAddress.state')
                    ->label('State')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preferredAddress.zip')
                    ->label('Zip')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->icon('heroicon-o-phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-o-envelope')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->icon('heroicon-o-link')
                    ->searchable()
                    ->url(fn ($record) => route('profile.show', $record->id))
                    ->extraAttributes([
                        'style' => 'max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;',
                    ]),
                Tables\Columns\TextColumn::make('external_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('status')
                ->options([
                    '' => 'Alle',
                    'exacc' => 'Existing Account',
                    'deal' => 'Deal',
                    'lead' => 'Lead',
                    'contact' => 'Contact',
                ]),
            ])
            ->recordAction(Tables\Actions\EditAction::class) // Keine Aktion bei einfachem Klick
            ->recordUrl(null)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ;

    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCustomers::route('/'),
            'view' => Pages\ViewCustomer::route('/{record}'), // Detailseite hinzufügen
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
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
