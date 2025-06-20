<?php

namespace App\Filament\Resources;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\FilTableFieldsResource\Pages;
use App\Filament\Resources\FilTableFieldsResource\RelationManagers;
use App\Models\FilTableFields;
use App\Models\Company;
use App\Models\Customer;
use Filament\Forms;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Validator;

class FilTableFieldsResource extends Resource
{
    protected static ?string $model = FilTableFields::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Configuration';

    protected static ?string $navigationLabel = 'Fields';

    protected static function getTitle(){
        return "Edit Fields";
    }

    //protected static ?string $title = 'Meine benutzerdefinierte Seite';

    public static function getPluralLabel(): string
    {
        return 'Configure Fields'; // Neuer Titel
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Base Settings')
                    ->schema([

                        //Forms\Components\TextInput::make('user_id')->default(Auth::id()),
                        Forms\Components\Toggle::make('form')
                            ->helperText('Form or Table?')
                            ->default(false)  // ← Standardwert setzen
                            ->live()
                            ->disabled(fn (string $context) => $context === 'edit' && request()->get('duplicate') !== '1')
                            ->afterStateUpdated(fn ($state) => info('form-Toggle: ' . var_export($state, true))),
                        Forms\Components\Toggle::make('required'),
                        Forms\Components\Toggle::make('is_badge')->label('Is Badge'),
                        Forms\Components\Toggle::make('is_toggable')->label('Toggable'),
                        Forms\Components\Select::make('table')
                            ->label('Tabelle')
                            ->options(self::getTableOptions())
                            ->required()
                            ->reactive() // wichtig für Reaktivität
                            ->disabled(fn (string $context) => $context === 'edit')
                            ->afterStateUpdated(fn (callable $set) => $set('field', null)),

                         Forms\Components\Select::make('field')
                            ->label('Feld')
                            ->required()
                            ->reactive() // ← das ist ENTSCHEIDEND!
                            ->options(fn (callable $get) => self::getFieldOptions($get('table')))
                            ->disabled(fn (callable $get) => ! $get('table')), // funktioniert jetzt


                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'text' => 'Text',
                                'date' => 'Date',
                                'datetime' => 'DateTime',
                                'number' => 'Number',
                                'toggle' => 'Boolean',
                                'select' => 'Select',
                                'markdown' => 'Markdown',
                                'relation' => 'Relation'
                            ]),
                        Forms\Components\TextInput::make('label')->required(),
                        Forms\Components\TextInput::make('order')->numeric(),
                        Forms\Components\TextInput::make('icon')->label('Icon'),
                        Forms\Components\TextInput::make('icon_color')->label('Icon Color'),
                        Forms\Components\TextInput::make('link')->helperText('Auch Funktion möglich'),
                        Forms\Components\Select::make('link_target')->label('Link Target')->options([''=>'','_blank'=>'New Tab']),
                        Forms\Components\TextInput::make('section')->numeric(),
                        Forms\Components\TextInput::make('select_options')->label('Dropdown Values'),
                    ])->columns(4)->collapsible(),

                    Forms\Components\Section::make('Advanced Settings')
                    ->schema([
                        Forms\Components\Select::make('color')->options([
                                'primary' => 'Primary',
                                'secondary' => 'Secondary',
                                'warning' => 'Warming',
                                'danger' => 'Danger',
                            ]),
                        Forms\Components\TextInput::make('relation_table')->label('Relation Table'),
                        Forms\Components\TextInput::make('relation_show_field')->label('Relation Field'),
                        Forms\Components\Toggle::make('sortable'),
                        Forms\Components\Toggle::make('searchable'),
                        Forms\Components\Toggle::make('disabled'),
                        Forms\Components\Toggle::make('is_toggable'),
                        Forms\Components\Textarea::make('format')->rows(5)->placeholder("z.B: return fn (string \state) => \App\Enums\CustomerStatusEnum::tryFrom(\$state)?->label() ?? \$state;")->columnSpanFull(),
                        Forms\Components\Textarea::make('extra_attributes')->rows(5)->placeholder("z.B: return fn (\$state) => ['style' => 'max-inline-size: 200px;];")->columnSpanFull(),
                    ])->columns(3)->collapsible()
            ])->columnSpan('full')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\IconColumn::make('form')->boolean(),
                //Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('table'),
                Tables\Columns\TextColumn::make('field'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('label')->searchable(),
                Tables\Columns\TextColumn::make('icon')->icon(fn ($record) => $record->icon),
                Tables\Columns\TextColumn::make('icon_color'),
                Tables\Columns\IconColumn::make('link')->boolean()->getStateUsing(fn ($record) => !empty($record->link)),
                Tables\Columns\TextColumn::make('link_target'),
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
                        // Wenn kein Wert oder "Alle" ausgewählt ist (leerer String), keine Filterung anwenden
                        if (empty($state['value'])) {
                            return $query;
                        }

                        // Filtere nach dem ausgewählten "table"-Wert
                        return $query->where('table', $state['value']);
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplizieren')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function ($record, $data, $livewire) {
                        $params = $record->toArray();
                        unset($params['id']);
                        $params['label'] .= ' (Kopie)';

                        // Umleiten auf Create-Seite mit den Daten als Query-Parameter
                        return redirect(route('filament.admin.resources.fil-table-fields.create', ['duplicate_data' => json_encode($params)]));
                    })
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

    public static function getTableOptions(): array
    {
        $resources = Filament::getResources();

        $tables = [];

        foreach ($resources as $resourceClass) {
            if (method_exists($resourceClass, 'getModel')) {
                $modelClass = $resourceClass::getModel();

                if (class_exists($modelClass)) {
                    $model = new $modelClass();
                    $table = $model->getTable();

                    $label = $resourceClass::getPluralLabel();

                    if (empty($label)) {
                        $label = class_basename($modelClass);
                        $label = Str::plural($label);
                    }

                    $tables[$table] = $label;
                }
            }
        }
        asort($tables);
        return $tables;
    }

    public static function getFieldOptions(?string $table): array
    {
        if (! $table || ! Schema::hasTable($table)) {
            return [];
        }

        return collect(Schema::getColumnListing($table))
            ->mapWithKeys(fn ($column) => [$column => $column])
            ->toArray();
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        $exists = FilTableFields::where('form', $data['form'])
            ->where('table', $data['table'])
            ->where('field', $data['field'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'field' => 'Ein Eintrag mit diesen Werten existiert bereits.',
            ]);
        }

        return $data;
    }


}
