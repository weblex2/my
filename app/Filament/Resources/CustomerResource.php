<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CustomerAssd;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\CustomerResource\Pages;
use App\Http\Controllers\FilamentFieldsController;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Enums\CustomerStatusEnum;
use Filament\Tables\Actions;
use App\Filament\Helpers\FilamentHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\FilamentConfig;
use Filament\Notifications\Notification;
use App\Models\CustomerField;
use App\Livewire\AddFieldComponent;
use Filament\Forms\Components\Livewire as LivewireComponent;
use Filament\Widgets\ButtonWidget;
use Illuminate\Support\Facades\Session;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Customers';

    protected static ?string $navigationLabel = 'All Customers';

    protected static ?int $navigationSort = 4;

    protected static $form;

    public static function fillForm($res)
    {
        // Hier wird auf die statische Eigenschaft zugegriffen
        self::$form->fill([
            'operation_result' => $res
        ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        $fc = new FilamentFieldsController('customers', 1);
        $schema = $fc->getSchema() ?? [];
        return $form
            ->schema(
                $schema
               /*  Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Base Information')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('company_id')
                                    ->relationship('company', 'company_name')
                                    ->columns(3),
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
                                        ...FilamentConfig::getFiltersFor('customer', 'status'),
                                    ]),
                            ])->columns(3)
                            ->collapsible(),
                        Forms\Components\Section::make('Contact Information')
                            ->schema(array_merge([
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('website')
                                    ->maxLength(255)
                            ], $form_fields))
                            ->columns(3)
                            ->collapsible(),
                        Forms\Components\Section::make('Customer Information')
                          ->schema(array_merge([
                                Forms\Components\Select::make('spread')
                                    ->label('Spread')
                                    ->options([
                                        'nat' => 'national',
                                        'inter' => 'International',
                                    ]),
                                Forms\Components\Select::make('bi')
                                    ->label('BI')
                                    ->options([
                                        'clickview' => 'ClickView',
                                        'qlik' => 'Qlik',
                                        'powerbi' => 'Power BI',
                                        'tableau' => 'Tableau',
                                        'none' => 'Kein Tool',
                                    ]),
                                Forms\Components\Select::make('solution')
                                    ->label('Solution')
                                    ->options([
                                        'pms2' => 'PMS 2',
                                        'pms3' => 'PMS 3',
                                    ]),
                                Forms\Components\TextInput::make('sales_volume')
                                    ->numeric(),
                                Forms\Components\Select::make('type')
                                    ->label('Type')
                                    ->options([
                                        'acc' => 'Account',
                                        'key' => 'Key Account',
                                    ]),
                         ], $form_fields))
                        ->columns(3)
                        ->collapsible(),
                        Forms\Components\Section::make('Information')
                          ->schema(array_merge([
                                Forms\Components\Textarea::make('comments')->columnSpan('full'),
                        ], $form_fields))
                        ->columns(3)
                    ])
                    ->columnSpan('full'),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Group 2 - Section 1')
                            ->schema([
                                Forms\Components\Toggle::make('blubb')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                    ])
                */
            );
    }

    public static function table(Table $table): Table
    {
        $fc = new FilamentFieldsController('customers', 0);
        $table_fields = $fc->getTableFields() ?? [];
        $schema = $fc->getSchema() ?? [];
        return $table
            ->columns(array_merge([], $table_fields))

            ->headerActions([
                Actions\Action::make('Exportieren')
                    ->label('Excel Export')
                    ->icon('heroicon-o-folder-arrow-down')
                    ->action(function (array $data, $livewire) {
                        $records = $livewire->getFilteredTableQuery()->get();
                        $records = FilamentHelper::excelExport($records);
                        return Excel::download($records, 'export.xlsx');
                    }),
                Actions\Action::make('addField')
                    ->label('Feld hinzufügen')
                    ->icon('heroicon-o-plus-circle')
                    ->modalContent(function ($record) {
                        return view('filament.actions.add-db-field-modal', [
                            'tableName' => 'customers',
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options(function () {
                        return FilamentConfig::getFiltersFor('customer', 'status');
                    }),
            ])
            ->recordAction(Tables\Actions\EditAction::class)
            ->recordUrl(null)
            ->actions([
                Actions\ActionGroup::make([
                    Actions\DeleteAction::make()->visible(function ($record) {
                        return auth()->user()->can('delete', $record);
                    }),
                    Actions\ViewAction::make()->visible(function ($record) {
                        return auth()->user()->can('view', $record);
                    }),
                    Actions\EditAction::make()->visible(function ($record) {
                        return auth()->user()->can('update', $record);
                    }),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()->visible(function () {
                        return auth()->user()->can('deleteAny', Customer::class);
                    }),
                    Actions\ForceDeleteBulkAction::make()->visible(function () {
                        return auth()->user()->can('forceDeleteAny', Customer::class);
                    }),
                    Actions\RestoreBulkAction::make()->visible(function () {
                        return auth()->user()->can('restoreAny', Customer::class);
                    }),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
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
                    ->label('Website'),
                TextEntry::make('status')
                    ->label('Status'),
            ])
            ->columns(3);
    }

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
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getButtons(): array
    {
        return [
            // Auskommentierter Button bleibt auskommentiert
            /*
            ButtonWidget::make('Add Field')->modal(fn () => Modal::make()
                ->title('Feld hinzufügen 123')
                ->form([
                    Forms\Components\TextInput::make('field_name')
                        ->label('Feldname')
                        ->required(),
                    Forms\Components\TextInput::make('field_type')
                        ->label('Feldtyp')
                        ->required(),
                    Forms\Components\Button::make('submit')
                        ->label('Absenden')
                        ->color('primary')
                        ->submit('submitField'),
                ])
                ->size('lg')
            )
            */
        ];
    }
}
