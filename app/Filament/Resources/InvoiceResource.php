<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions;
use App\Http\Controllers\FilamentFieldsController;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return \App\Models\Resources::where('resource', 'InvoiceResource')->value('navigation_group') ?? null;
    }

    public static function form(Form $form): Form
    {
        $fc = new FilamentFieldsController('invoices',1);
        $table_fields = $fc->getFields() ?? [];
        return $form
            ->schema($table_fields);
    }

    public static function table(Table $table): Table
    {
        $fc = new FilamentFieldsController('invoices',0);
        $table_fields = $fc->getFields() ?? [];
        return $table
            ->columns($table_fields)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Actions\Action::make('Exportieren')
                    ->label('Excel Export')
                    ->icon('heroicon-o-folder-arrow-down')
                    ->action(function (array $data, $livewire) {
                        $records = $livewire->getFilteredTableQuery()->get(); // Das funktioniert bei Table-Components
                        $records = FilamentHelper::excelExport($records);
                        // Anonyme Export-Klasse
                        return Excel::download($records, 'export.xlsx');
                    }),
                Actions\Action::make('addField')
                    ->label('Feld hinzufügen')
                    ->icon('heroicon-o-plus-circle')
                    ->modalContent(function ($record) {
                        return view('filament.actions.add-db-field-modal', [
                            'tableName' => 'invoices',
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),

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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
