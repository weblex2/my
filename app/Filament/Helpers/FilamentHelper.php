<?php

namespace App\Filament\Helpers;

use Filament\Tables\Columns\Column;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Quote;
use Illuminate\Support\Facades\Log;
use App\Exports\CustomExport;
use App\Models\GeneralSetting;

class FilamentHelper
{
    private $data;

    public static function readSetting($field){
        $setting = GeneralSetting::where('field', $field)->first();
        // Wenn der Datensatz existiert, gibt den Wert zurück, sonst null
        return $setting ? $setting->value : false;
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

    public static function excelExport($data){
        $exportData = [];
        $header = array_keys($data[0]->getAttributes());
        $exportData[] = $header;
        foreach ($data as $item) {
            $row = [];
            foreach ($item->getAttributes() as $key => $value) {
                $row[ucwords(str_replace('_', ' ', $key))] = $value;
            }
            $exportData[] = $row;
        }
        return new CustomExport($exportData);
    }
}
