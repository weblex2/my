<?php

namespace App\Providers\Filament;

use Illuminate\Support\ServiceProvider;
use Filament\Tables\Columns\Column;

class FilamentMacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Column::macro('background', function (callable $callback) {
            /** @var \Filament\Tables\Columns\Column $this */
            return $this
                ->html()
                ->formatStateUsing(function ($state, $record) use ($callback) {
                    $classes = $callback($state, $record);
                    return '<div class="inline-block ' . $classes . '">' . e($state) . '</div>';
                });
        });
    }
}
