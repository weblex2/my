<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class FileUploadController extends Controller
{
    public function index()
    {
        return view('blog.upload');
    }
 
    /**
     * Uploads nur noch als Bild und unter zufaelligem Namen: bis 2026-11-15
     * landete jeder Dateityp mit Originalendung in public/ - also auch .php.
     */
    private const IMAGE_RULE = 'required|file|mimes:jpg,jpeg,png,gif,webp,heic|max:20480';

    public function uploadToServer(Request $request)
    {
        $request->validate(['file' => self::IMAGE_RULE]);

        $name = self::safeName($request->file('file'));
        $request->file('file')->move(public_path('uploads'), $name);

        return response()->json(['success' => 'Successfully uploaded.']);
    }

    public  function FileUpload(Request $request)
    {
        $request->validate(['file' => self::IMAGE_RULE]);

        $imageName = self::safeName($request->file('file'));
        $request->file('file')->move(public_path('images/tmp'), $imageName);

        return response()->json(['success' => $imageName]);
    }

    /** Zufaelliger Name mit Endung aus dem Dateiinhalt, nie aus dem Client-Namen */
    public static function safeName(\Illuminate\Http\UploadedFile $file): string
    {
        $ext = strtolower($file->guessExtension() ?: 'bin');
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic', 'heif'], true)) {
            $ext = 'bin';
        }
        return \Illuminate\Support\Str::random(24) . '.' . $ext;
    }
}
