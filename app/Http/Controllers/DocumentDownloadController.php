<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Quote;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentDownloadController extends Controller
{
    public function download(Document $document)
    {
        //$document = Document::find($document);
        return response($document->content)
            ->header('Content-Type', $document->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $document->filename . '"');
    }

    public function view($id)
    {
        $quote = Quote::findOrFail($id);
        $quote = Quote::with(['customer', 'quoteProducts.product'])->findOrFail($id);
        $pdf = Pdf::loadView('filament.pdf.quote', ['quote' => $quote]);
        // PDF im Browser öffnen
        return $pdf->stream("Angebot_{$quote->quote_number}.pdf");
    }
}
