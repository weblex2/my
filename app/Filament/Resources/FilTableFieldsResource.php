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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class FilTableFieldsResource extends Resource
{
    protected static ?string $model = FilTableFields::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Configuration';

    protected static ?string $navigationLabel = 'Fields';

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
                            ->required()
                            ->helperText('Form or Table?')
                            ->disabled(fn (string $context) => $context === 'edit'),
                        Forms\Components\Toggle::make('required')->columnSpan(3),
                        Forms\Components\Select::make('table')
                            ->required()
                            ->disabled(fn (string $context) => $context === 'edit')
                            ->options(function () {
                                $resourcePath = app_path('Filament/Resources');
                                if (!File::exists($resourcePath)) {
                                    return [];
                                }
                                return collect(File::files($resourcePath))
                                    ->map(fn ($file) => $file->getFilenameWithoutExtension())
                                    ->filter(fn ($name) => str_ends_with($name, 'Resource'))
                                    ->mapWithKeys(function ($name) {
                                        $label = str_replace('Resource', '', $name);
                                        return [$label => $label];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->dehydrateStateUsing(fn ($state) => strtolower($state))
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Optional: reset field if table changes
                                $set('field', null);
                            }),
                        Forms\Components\Select::make('field')
                            ->required()
                            ->disabled(fn (string $context) => $context === 'edit')
                            ->options(function (callable $get) {
                                $resourceName = $get('table');
                                if (!$resourceName) {
                                    return [];
                                }

                                // Resource-Klasse zusammenbauen
                                $resourceClass = "App\\Filament\\Resources\\" . ucfirst($resourceName) . 'Resource';

                                if (!class_exists($resourceClass)) {
                                    return [];
                                }

                                // Modell ermitteln
                                $modelClass = $resourceClass::getModel();

                                // Tabellenname holen
                                $tableName = (new $modelClass)->getTable();

                                // Spalten aus der DB holen
                                if (!Schema::hasTable($tableName)) {
                                    return [];
                                }

                                return collect(Schema::getColumnListing($tableName))
                                    ->mapWithKeys(fn ($column) => [$column => $column])
                                    ->toArray();
                            })
                            ->reactive() // reagiert auf Änderung von 'table'
                            ->searchable(),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'text' => 'Text',
                                'date' => 'Date',
                                'datetime' => 'DateTime',
                                'toggle' => 'Boolean',
                                'select' => 'Select',
                                'markdown' => 'Markdown',
                                'bagde' => 'Badge',
                                'relation' => 'Relation'
                            ]),
                        Forms\Components\TextInput::make('label')->required(),
                        Forms\Components\TextInput::make('order')->numeric(),
                        Forms\Components\TextInput::make('icon')->label('Icon'),
                        Forms\Components\TextInput::make('icon_color')->label('Icon Color'),
                        Forms\Components\TextInput::make('link')->helperText('Auch Funktion möglich'),
                        Forms\Components\Select::make('link_target')->label('Link Target')->options([''=>'','_blank'=>'New Tab']),

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
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('table'),
                Tables\Columns\TextColumn::make('field'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('icon'),
                Tables\Columns\TextColumn::make('icon_color'),
                Tables\Columns\TextColumn::make('link'),
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
