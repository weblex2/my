<?php

namespace App\Filament\Helpers;

use Filament\Tables\Columns\Column;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Quote;

class FilamentHelper
{
    private $data;


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

    public static function renderPdf($id){
        $quote = Quote::with(['customer', 'quoteProducts.product'])->find($id);
        $quote->quoteProducts = $quote->quoteProducts->sortBy('reoccurance');
        $pdf['content'] = Pdf::loadView('filament.pdf.quote', ['quote' => $quote]);
        $pdf['quote_number'] = $quote->quote_number;
        return $pdf;
    }

    public static function generateFile($id)
    {
        $quote = Quote::with(['customer', 'quoteProducts.product'])->find($id);

        if (!$quote) {
            throw new \Exception("Quote mit ID $id nicht gefunden.");
        }

        $quote->quoteProducts = $quote->quoteProducts->sortBy('reoccurance');

        $pdf = Pdf::loadView('filament.pdf.quote', ['quote' => $quote]);

        $filename = 'Angebot_' . $quote->quote_number . '.pdf';
        $path = storage_path('app/public/quotes/' . $filename);

        // Verzeichnis sicherstellen
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }

        // PDF speichern
        $pdf->save($path);
        return $path;
    }
}
