<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionResource\Pages;
use App\Filament\Resources\SectionResource\RelationManagers;
use App\Models\Section;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions;
use App\Http\Controllers\FilamentFieldsController;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return \App\Models\Resource::where('resource', 'SectionResource')->value('navigation_group') ?? null;
    }

    public static function form(Form $form): Form
    {
        $fc = new FilamentFieldsController('sections',1);
        $schema = $fc->getSchema() ?? [];
        return $form
            ->schema($schema);
    }

    public static function table(Table $table): Table
    {
        $fc = new FilamentFieldsController('sections',0);
        $table_fields = $fc->getTableFields() ?? [];
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
                            'tableName' => 'sections',
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
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }
}
