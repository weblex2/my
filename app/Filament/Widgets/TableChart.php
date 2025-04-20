<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\CustomerResource;

class TableChart extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(CustomerResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('id', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.company_name'),

                Tables\Columns\TextColumn::make('name')
                    ->url(fn ($record) => CustomerResource::getUrl('view', ['record' => $record]))
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('preferredAddress.address')
                    ->label('Address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preferredAddress.city')
                    ->label('City')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preferredAddress.state')
                    ->label('State')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preferredAddress.zip')
                    ->label('Zip')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->icon('heroicon-o-phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-o-envelope')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->icon('heroicon-o-link')
                    ->searchable()
                    //->url(fn ($record) => route('profile.show', $record->id)) //link zum user profile
                    ->url(fn ($record) => $record->website)
                    ->openUrlInNewTab()
                    ->extraAttributes([
                        'style' => 'max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;',
                    ]),
                Tables\Columns\TextColumn::make('external_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assd.type')
                    ->label('Type')
                    ->searchable()
                    ->visible(function ($livewire) {
                        $filters = $livewire->tableFilters;
                        $statusFilter = $filters['status']['value'] ?? null;
                        $isVisible = in_array($statusFilter, ['deal', 'exacc']);
                        return $isVisible;
                    }),
                Tables\Columns\TextColumn::make('assd.spread')
                    ->label('Spread')
                    ->searchable()
                    ->visible(function ($livewire) {
                        $filters = $livewire->tableFilters;
                        $statusFilter = $filters['status']['value'] ?? null;
                        $isVisible = in_array($statusFilter, ['deal', 'exacc']);
                        return $isVisible;
                    }),
                Tables\Columns\TextColumn::make('assd.bi')
                    ->label('BI')
                    ->searchable()
                    ->visible(function ($livewire) {
                        $filters = $livewire->tableFilters;
                        $statusFilter = $filters['status']['value'] ?? null;
                        $isVisible = in_array($statusFilter, ['deal', 'exacc']);
                        return $isVisible;
                    }),
                Tables\Columns\TextColumn::make('assd.solution')
                    ->label('Solution')
                    ->searchable()
                    ->visible(function ($livewire) {
                        $filters = $livewire->tableFilters;
                        $statusFilter = $filters['status']['value'] ?? null;
                        $isVisible = in_array($statusFilter, ['deal', 'exacc']);
                        return $isVisible;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
