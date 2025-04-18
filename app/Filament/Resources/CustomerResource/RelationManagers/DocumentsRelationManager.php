<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Document;
use App\Filament\Resources\CustomerResource;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mime_type')
                    ->label('Typ')
                    ->getStateUsing(fn ($record) => $record->mime_type)
                    ->icon(fn ($state) => match (true) {
                        str_contains($state, 'image/') => 'heroicon-o-photo',
                        str_contains($state, 'application/pdf') => 'heroicon-o-document-text',
                        str_contains($state, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') => 'heroicon-o-document',
                        str_contains($state, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') => 'heroicon-o-table-cells',
                        str_contains($state, 'application/vnd.openxmlformats-officedocument.presentationml.presentation') => 'heroicon-o-presentation-chart-bar',
                        default => 'heroicon-o-document',
                    })
                    ->color('cyan') // Setzt die Farbe auf Cyan (anpassbar)
                    ->extraAttributes(['class' => 'w-5 h-5 mr-2 text-cyan-500'])
                    ->alignment('left')
                    ->grow(false),
                Tables\Columns\TextColumn::make('filename')
                ->label('Filename')
                ->formatStateUsing(function ($record) {
                    $url = route('documents.download', $record->id);
                    return "<a href='{$url}' target='_blank' class='underline text-primary-600'>$record->filename</a>";
                })
                ->html(),
                Tables\Columns\TextColumn::make('created_at')->label('Datum')->dateTime('M d, Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('contact.subject')->label('Betreff')->limit(50)->searchable(),
                Tables\Columns\TextColumn::make('size')->label('Größe')->limit(50),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Neues Dokument anlegen'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
