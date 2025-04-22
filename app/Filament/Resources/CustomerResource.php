<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use App\Models\CustomerAssd;
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
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Customers';

    protected static ?string $navigationLabel = 'All Customers';

    protected static ?int $navigationSort = 4;



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

                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->columnSpanFull(),
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

                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'company_name'),

                Forms\Components\TextInput::make('website')
                    ->maxLength(255),
                /* Forms\Components\TextInput::make('assd.bi')
                    ->maxLength(255)
                    ->relationship('assd', 'bi'), */
                Forms\Components\Select::make('assd.solution')
                    ->relationship('assd', 'solution')
                    ->options([
                        'pms2' => 'PMS 2',
                        'pms3' => 'PMS 3',
                    ])
                    ->saveRelationshipsUsing(function ($record, $state) {
                        if ($state) {
                            $record->assd()->updateOrCreate(
                                ['customer_id' => $record->id],
                                ['solution' => $state]
                            );
                        }
                    }),

                Forms\Components\Select::make('assd.bi')
                    ->label('BI')
                    ->relationship('assd', 'bi')
                    ->options([
                        'clickview' => 'CliqView',
                        'mspbi' => 'MS Power BI',
                    ])
                    ->saveRelationshipsUsing(function ($record, $state) {
                        if ($state) {
                            $record->assd()->updateOrCreate(
                                ['customer_id' => $record->id],
                                ['bi' => $state]
                            );
                        }
                    }),
                   /*  ->afterStateUpdated(function ($state, $record) {
                        if ($state) {
                            $record->assd()->updateOrCreate(
                                ['customer_id' => $record->id],
                                ['solution' => $state]
                            );
                        }
                    }), */

                Forms\Components\Textarea::make('comments')
                    ->columnSpanFull(),

            ])->columns(3);
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.company_name'),

                Tables\Columns\TextColumn::make('name')
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record])) // Link zur View-Seite,
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('preferredAddress.address')
                    ->label('Address')
                    ->searchable(),
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
                    ->formatStateUsing(fn ($state) => '+49 (0) ' . substr($state, 3, 3) . ' ' . substr($state, 6)) // oder deine Logik
                    ->url(fn ($record) => 'tel:' . preg_replace('/[^0-9+]/', '', $record->phone))
                    ->openUrlInNewTab(false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-o-envelope')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->icon('heroicon-o-link')
                    ->searchable()
                    //->url(fn ($record) => route('profile.show', $record->id)) //link zum user profile
                    ->url(fn ($record) => $record->website)
                    ->openUrlInNewTab()
                    ->extraAttributes([
                        'style' => 'max-inline-size: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;',
                    ]),
                Tables\Columns\TextColumn::make('external_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assd.type')
                    ->label('Type')
                    ->searchable()
                    ->visible(function ($livewire) {
                        $filters = $livewire->tableFilters;
                        $statusFilter = $filters['status']['value'] ?? null;
                        $isVisible = in_array($statusFilter, ['deal', 'exacc']);
                        return $isVisible;
                    }),
                Tables\Columns\TextColumn::make('assd.spread')
                    ->label('Spread')
                    ->searchable()
                    ->visible(function ($livewire) {
                        $filters = $livewire->tableFilters;
                        $statusFilter = $filters['status']['value'] ?? null;
                        $isVisible = in_array($statusFilter, ['deal', 'exacc']);
                        return $isVisible;
                    }),
                Tables\Columns\TextColumn::make('assd.bi')
                    ->label('BI')
                    ->searchable()
                    ->visible(function ($livewire) {
                        $filters = $livewire->tableFilters;
                        $statusFilter = $filters['status']['value'] ?? null;
                        $isVisible = in_array($statusFilter, ['deal', 'exacc']);
                        return $isVisible;
                    }),
                Tables\Columns\TextColumn::make('assd.solution')
                    ->label('Solution')
                    ->searchable()
                    ->visible(function ($livewire) {
                        $filters = $livewire->tableFilters;
                        $statusFilter = $filters['status']['value'] ?? null;
                        $isVisible = in_array($statusFilter, ['deal', 'exacc']);
                        return $isVisible;
                    }),
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

    /* public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('company.company_name')
                    ->label('Company'),
                TextEntry::make('name')
                    ->label('Lastname'),
                TextEntry::make('first_name')
                    ->label('First Name'),
                TextEntry::make('website')
                    ->label('Website')
                TextEntry::make('status')
                    ->label('Status')
            ])
            ->columns(3);
    } */

    public static function getRelations(): array
    {
        return [
            RelationManagers\ContactsRelationManager::class,
            RelationManagers\DocumentsRelationManager::class,
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
