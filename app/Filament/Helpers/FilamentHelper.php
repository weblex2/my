<?php

namespace App\Filament;

use Filament\Tables\Columns\Column;

class FilamentHelper
{
    /**
     * Fügt einer Column dynamische Hintergrundfarbe + Textfarbe hinzu.
     */
    public static function withBackground(Column $column, callable $callback): Column
    {
        return $column
            ->html()
            ->formatStateUsing(function ($state, $record) use ($callback) {
                $classes = $callback($state, $record);
                return '<div class="px-2 py-1 rounded ' . $classes . '">' . e($state) . '</div>';
            });
    }

    // Weitere Helfer wie withIcon, withTooltip etc. kannst du hier ergänzen.
}
