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
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use App\Filament\Helpers\FilamentHelper;
use Livewire\Livewire;
use App\Http\Controllers\ZimbraController;



class QuoteResource extends Resource
{
    protected static ?string $model = \App\Models\Quote::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Angebote';
    protected static ?string $navigationGroup = 'Angebote';

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
                                ->searchable(['name', 'first_name'])
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

                            Select::make('status')
                                ->label('Status')
                                ->required()
                                ->options([
                                    'draft' => 'Draft',
                                    'sent' => 'Sent',
                                    'accepted' => 'Accepted',
                                    'rejected' => 'Rejected'
                                ]),

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
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->price);
                                                self::updateTotalPrice($get, $set);
                                                self::calculateTotalAmount($get, $set);
                                            }
                                        })
                                        ->afterStateHydrated(function ($state, Set $set, Get $get) {
                                            // Setze unit_price und total_price beim Laden des Formulars
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->price);
                                                self::updateTotalPrice($get, $set);
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
                                            self::calculateTotalAmount($get, $set);
                                        }),

                                    TextInput::make('unit_price')
                                        ->label('Einzelpreis')
                                        ->numeric()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function (Get $get, Set $set) {
                                            self::updateTotalPrice($get, $set);
                                            self::calculateTotalAmount($get, $set);
                                        }),

                                    Select::make('reoccurance')
                                        ->label('Reoccurance')
                                        ->required()
                                        ->default('once')
                                        ->options([
                                            'once' => 'einmalig',
                                            'year' => 'jährlich',
                                            'month' => 'monatlich',
                                        ]),

                                    TextInput::make('total_price')
                                        ->label('Gesamtpreis')
                                        ->numeric()
                                        ->readOnly(),
                                ])
                                ->columns(5)
                                ->itemLabel(fn (array $state): ?string => Product::find($state['product_id'])?->name ?? null)
                                ->addActionLabel('Produkt hinzufügen')
                                ->minItems(1)
                                ->live()
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::calculateTotalAmount($get, $set);
                                }),
                        ]),

                    Forms\Components\Wizard\Step::make('Zusammenfassung')
                        ->schema([


                            TextInput::make('total_amount')
                                ->label('Gesamtbetrag')
                                ->numeric()
                                ->readOnly()
                                ->afterStateHydrated(function (Get $get, Set $set) {
                                    self::calculateTotalAmount($get, $set);
                                }),
                            Forms\Components\MarkdownEditor::make('agb')
                                ->label('AGB')
                                ->columnSpanFull(),

                            Forms\Components\MarkdownEditor::make('notes')
                                ->label('Bemerkungen')
                                ->columnSpanFull(),

                        ]),

                ])
                ->columnSpanFull()
                ->persistStepInQueryString()
                ->submitAction(
                    Action::make('submit')
                        ->label('Angebot erstellen')
                        ->action(function (Form $form) {
                        // Den Datensatz speichern
                        $data = $form->getState();

                        // ID an die Methode übergeben, um das PDF zu erstellen
                        return $this->previewPdf($data);
                    }),
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
                    ->formatStateUsing(fn ($record) => "{$record->customer->name}, {$record->customer->first_name}")
                    ->url(fn ($record): string => CustomerResource::getUrl('edit', ['record' => $record->customer_id]))
                    ->icon('heroicon-o-user')
                    ->color('primary')
                    ->searchable(['customer.name', 'customer.first_name']),
                Tables\Columns\TextColumn::make('valid_until')
                    ->date('d.m.Y'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('EUR')
                    ->alignEnd(),
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
                    ->disabled(fn (Quote $record): bool => $record->status !== 'draft'),
                Tables\Actions\Action::make('download_pdf')
                    ->label('PDF herunterladen')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Quote $record) {
                        $pdf = FilamentHelper::renderPdf($record->id);
                        return response()->streamDownload(
                            fn () => print($pdf['content']->output()),
                            "Angebot_{$pdf['quote_number']}.pdf"
                        );
                    }),
                Tables\Actions\Action::make('view_pdf')
                    ->label('PDF anzeigen')
                    ->icon('heroicon-o-document')
                     ->url(function (Quote $record) {
                        // Generiere die URL für das PDF
                        return route('pdf.view', ['id' => $record->id]);
                    })
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('send_email')
                    ->label('Email senden')
                    //->icon('heroicon-o-mail')
                    ->modalHeading('E-Mail senden')
                    //->modalWidth('lg')
                    ->form([
                        Forms\Components\TextInput::make('email')
                            ->label('E-Mail-Adresse')
                            ->email()
                            ->default(function (Quote $record) {
                                $record->load('customer');  // Lade die `customer` Beziehung
                                $customer = $record->customer;

                                if ($customer) {
                                    \Log::info("Customer Email: " . $customer->email);
                                    return $customer->email;  // Setze die E-Mail-Adresse als Standardwert
                                } else {
                                    \Log::warning("Kein Kunde gefunden für Quote ID: {$record->id}");
                                    return '';  // Setze einen leeren Standardwert, wenn kein Kunde gefunden wird
                                }
                            })
                            ->required(),
                        Forms\Components\TextInput::make('cc')
                            ->label('CC')
                            ->email(),
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->required(),
                        Forms\Components\TextArea::make('body')
                            ->label('Nachricht')
                            ->required()
                            ->extraAttributes(['class' => 'h-24']),
                        // Vorschau des PDFs
                        Forms\Components\TextInput::make('pdf_link')
                            ->label('Angebots-PDF')
                            ->default(fn (Quote $record) => route('pdf.view', ['id' => $record->id]))
                            ->disabled()
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('open_pdf')
                                    ->label('Anzeigen')
                                    ->url(fn (Quote $record) => route('pdf.view', ['id' => $record->id]))
                                    ->openUrlInNewTab()
                            ),
                    ])
                    ->action(function (array $data, Quote $record) {
                        // Holen der E-Mail-Adresse des Kunden
                        \Log::info("Halllllooooo");
                        $record->load('customer');
                        $customer = $record->customer;
                        $email = $customer->email;
                        \Log::info("Quote Record: ", (array) $record);
                        \Log::info("Customer Email: " . ($email ?: 'Kein Kunde gefunden'));
                        // Generiere das PDF für das Angebot
                        $pdf = FilamentHelper::generateFile($record->id);

                        // Versenden der E-Mail mit dem PDF als Anhang
                        $mail = [
                            'to' => $email,
                            'cc' =>  $data['cc'],
                            'subject' =>  $data['subject'],
                            'body' =>  $data['body'],
                            'attachment' => [
                                0 => $pdf
                            ]
                        ];
                        $res = ZimbraController::sendMail($mail);


                        // Benachrichtigung anzeigen
                        Notification::make()
                            ->title('E-Mail wurde gesendet')
                            ->success()
                            ->send();
                    }),
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
        \Log::info($products);
        $total = 0;

        foreach ($products as $product) {
            $product['total_price'] = $product['quantity']*$product['unit_price'];
            if (isset($product['total_price']) && is_numeric($product['total_price'])) {
                $total += (float) $product['total_price'];
            } elseif (isset($product['quantity']) && isset($product['unit_price'])) {
                $total += (float) $product['quantity'] * (float) $product['unit_price'];
            }
        }

        $set('total_amount', number_format($total, 2, '.', ''));
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
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
