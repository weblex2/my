<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class S3Controller extends Controller
{
    // Dateien auflisten
    public function index()
    {
        $files = Storage::disk('s3')->files();
        return response()->json($files);
    }

    // Datei hochladen
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $path = $request->file('file')->store('uploads', 's3');
        return response()->json(['message' => 'File uploaded successfully', 'path' => $path]);
    }

    public function uploadtest()
    {
        if (!is_file('hallo S3.txt')){
            file_put_contents('hallo s3.txt', 'Hallo S3');
        }
        $path = 'hallo s3.txt';
        $res = Storage::disk('s3')->put($path, file_get_contents($path));

        dump($res);
    }

    // Einzelne Datei abrufen (Download-Link generieren)
    public function show($filename)
    {
        if (!Storage::disk('s3')->exists("$filename")) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $url = Storage::disk('s3')->url($filename);

        $content = Storage::disk('s3')->get($filename);
        echo $content;
        return response()->json(['url' => $url]);
    }

    // Datei aktualisieren (überschreiben)
    public function update(Request $request, $filename)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        Storage::disk('s3')->delete("uploads/$filename");
        $path = $request->file('file')->storeAs('uploads', $filename, 's3');

        return response()->json(['message' => 'File updated successfully', 'path' => $path]);
    }

    // Datei löschen
    public function destroy($filename)
    {
        if (!Storage::disk('s3')->exists("uploads/$filename")) {
            return response()->json(['error' => 'File not found'], 404);
        }

        Storage::disk('s3')->delete("uploads/$filename");
        return response()->json(['message' => 'File deleted successfully']);
    }
}
