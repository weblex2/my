<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WegDoc;
use App\Models\WegDocAttachment;

class WegController extends Controller
{
    public function index(Request $request)
    {
        $query = WegDoc::query();

        if ($search = $request->input('q')) {
            $query->whereRaw("MATCH(body, subject) AGAINST(? IN BOOLEAN MODE)", [$search]);
        }

        $docs = $query->orderBy('received', 'desc')->get();
        $docs->load('attachments');

        return view('weg.index', compact('docs'));
    }

    public function upload(Request $request){
        return view('weg.upload');
    }

    public function store(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'Keine Datei hochgeladen.'], 400);
        }

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('uploads', $filename, 'public');

        return response()->json(['message' => 'Datei erfolgreich hochgeladen.', 'path' => $path]);
    }

    public function preview($id)
    {
        $attachment = WegDocAttachment::findOrFail($id);

        $mime = $attachment->content_type;
        $base64 = $attachment->content;

        return response()->view('weg.attachment-preview', compact('mime', 'base64'));
    }

    public function getDocumentBody($id){
        $doc = WegDoc::findOrFail($id);
        $doc->load('attachments');
        return view('weg.doc-content', [
            'body' => nl2br(e($doc->body)),
            'attachments' => $doc->attachments,
            'docId' => $doc->id,
        ]);
    }
}
