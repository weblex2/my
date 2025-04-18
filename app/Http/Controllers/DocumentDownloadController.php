<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Response;

class DocumentDownloadController extends Controller
{
    public function download(Document $document)
    {
        //$document = Document::find($document);
        return response($document->content)
            ->header('Content-Type', $document->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $document->filename . '"');
    }
}
