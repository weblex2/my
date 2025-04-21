<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Document;
use App\Filament\Resources\ContactResource;
use App\Filament\Resources\CustomerResource;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('filename')
                    ->label('Filename')
                    ->icon(function ($record) {
                        // Definiere eine Zuordnung von MIME-Typen zu Heroicon-Namen
                        $icons = [
                            'image/' => 'heroicon-o-camera', // Kamera für Bilder
                            'application/pdf' => 'heroicon-o-document', // Dokument-Icon für PDF
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'heroicon-o-document-text', // Word-Dokument-Icon
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'heroicon-o-document-text', // Excel-Icon (Datenbank als Platzhalter)
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'heroicon-o-presentation-chart-bar', // Powerpoint-Icon
                        ];

                        // Fallback-Icon, falls der MIME-Typ nicht gefunden wird
                        $icon = 'heroicon-o-document';

                        // Suche nach dem passenden Icon basierend auf dem MIME-Typ
                        foreach ($icons as $type => $heroicon) {
                            if (str_contains($record->mime_type, $type)) {
                                $icon = $heroicon;
                                break;
                            }
                        }

                        return $icon;
                    })
                    ->iconColor('primary')
                    ->url(function ($record) {
                        // URL für den Download-Link der Datei
                        return route('documents.download', $record->id);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact.subject')
                    ->label('Betreff')
                    ->url(fn ($record) => route('filament.admin.resources.contacts.view', ['record' => $record]))
                    ->color('primary')
                    ->limit(50)->searchable(),
                 Tables\Columns\TextColumn::make('created_at')->label('Datum')->dateTime('M d, Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('size')->label('Größe')
                    ->formatStateUsing(function ($state) {
                        $bytes = (int) $state;

                        if ($bytes >= 1073741824) {
                            return number_format($bytes / 1073741824, 2) . ' GB';
                        } elseif ($bytes >= 1048576) {
                            return number_format($bytes / 1048576, 2) . ' MB';
                        } elseif ($bytes >= 1024) {
                            return number_format($bytes / 1024, 2) . ' KB';
                        }

                        return $bytes . ' B';
                    })
                    ->alignment('right'),
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
