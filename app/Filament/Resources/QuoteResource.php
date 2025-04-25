<?php
namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Filament\Resources\QuoteResource\Pages\CreateQuote;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class QuoteResource extends Resource
{
    protected static ?string $model = \App\Models\Quote::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Kundendaten')
                        ->schema([
                            Select::make('customer_id')
                                ->label('Kunde')
                                ->options(
                                    Customer::query()->get()->mapWithKeys(function ($customer) {
                                        return [$customer->id => "{$customer->name}, {$customer->first_name}"];
                                    })
                                )
                                ->searchable(['name', 'first_name']) // Suche nach name und first_name
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $set('quote_number', 'Q-' . Str::padLeft(Quote::withTrashed()->max('id') + 1, 6, '0'));
                                }),

                            TextInput::make('quote_number')
                                ->label('Angebotsnummer')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            DatePicker::make('valid_until')
                                ->label('Gültig bis')
                                ->required()
                                ->minDate(now()),
                        ]),

                    Forms\Components\Wizard\Step::make('Produkte')
                        ->schema([
                            Repeater::make('quoteProducts')
                                ->relationship()
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Produkt')
                                        ->options(Product::query()->pluck('name', 'id'))
                                        ->searchable()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, Set $set) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->price);
                                            }
                                        }),

                                    TextInput::make('quantity')
                                        ->label('Menge')
                                        ->numeric()
                                        ->default(1)
                                        ->minValue(1)
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            self::updateTotalPrice($get, $set);
                                        }),

                                    TextInput::make('unit_price')
                                        ->label('Einzelpreis')
                                        ->numeric()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            self::updateTotalPrice($get, $set);
                                        }),

                                    TextInput::make('total_price')
                                        ->label('Gesamtpreis')
                                        ->numeric()
                                        ->readOnly(),
                                ])
                                ->columns(3)
                                ->itemLabel(fn (array $state): ?string => Product::find($state['product_id'])?->name ?? null)
                                ->addActionLabel('Produkt hinzufügen')
                                ->minItems(1),
                        ]),

                    Forms\Components\Wizard\Step::make('Zusammenfassung')
                        ->schema([
                            Textarea::make('terms')
                                ->label('AGB')
                                ->columnSpanFull(),

                            Textarea::make('notes')
                                ->label('Bemerkungen')
                                ->columnSpanFull(),

                            TextInput::make('total_amount')
                                ->label('Gesamtbetrag')
                                ->numeric()
                                ->readOnly()
                                ->afterStateHydrated(function (Get $get, Set $set) {
                                    self::calculateTotalAmount($get, $set);
                                }),
                        ]),
                ])
                ->columnSpanFull()
                ->persistStepInQueryString()
                ->submitAction(
                    Action::make('submit')
                        ->label('Angebot erstellen')
                        ->action('create')
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quote_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('valid_until')
                    ->date(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('send')
                    ->label('Versenden')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(function (Quote $record) {
                        $record->update(['status' => 'sent']);
                        Notification::make()
                            ->title('Angebot versendet')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Quote $record): bool => $record->status === 'draft'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function updateTotalPrice(Get $get, Set $set): void
    {
        $quantity = (float) $get('quantity');
        $unitPrice = (float) $get('unit_price');
        $totalPrice = $quantity * $unitPrice;

        $set('total_price', number_format($totalPrice, 2, '.', ''));
    }

    protected static function calculateTotalAmount(Get $get, Set $set): void
    {
        $products = $get('quoteProducts') ?? [];
        $total = 0;

        foreach ($products as $product) {
            if (isset($product['total_price'])) {
                $total += (float) $product['total_price'];
            } else if (isset($product['quantity']) && isset($product['unit_price'])) {
                $total += (float) $product['quantity'] * (float) $product['unit_price'];
            }
        }

        $set('total_amount', number_format($total, 2, '.', ''));
    }

    public static function getRelations(): array
    {
        return [
            // RelationManager nicht mehr nötig, da Repeater verwendet wird
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
