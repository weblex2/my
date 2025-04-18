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
                Tables\Columns\TextColumn::make('filename')
                    ->label('Filename')
                    ->formatStateUsing(function ($record) {
                        $icons = [
                            'image/' => 'image.svg',
                            'application/pdf' => 'pdf.svg',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word.svg',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel.svg',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'powerpoint.svg',
                        ];

                        $icon = 'default.svg';

                        foreach ($icons as $type => $filename) {
                            if (str_contains($record->mime_type, $type)) {
                                $icon = $filename;
                                break;
                            }
                        }

                        $url = route('documents.download', $record->id);
                        $iconTag = '<img src="' . asset('img/icons/' . $icon) . '" alt="Icon" class="inline w-5 h-5 mr-2 align-middle" />&nbsp;';

                        return $iconTag . "<a href='{$url}' target='_blank' class='ml-2 underline align-middle text-primary-600'>{$record->filename}</a>";
                    })
                    ->html()
                    ->searchable(),
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
