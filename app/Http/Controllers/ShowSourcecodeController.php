<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Models\Ssc;

class ShowSourcecodeController extends Controller
{
    public function index($page_id){

        if ($page_id==null){
            return "no page_id given";
        }
        $startFile = "";
        $page = Ssc::where('page_id',"=", $page_id)->orderBy('type')->get();
        foreach ($page as $entry){
            $path = str_replace("\\", "/",base_path()."/".$entry->path);

            if ($entry->start_file!=null) {
                $startFileContent = file_get_contents(base_path()."/".$entry->path);
            }
            // Hauptverzeichnis setzen (hier z. B. das aktuelle Verzeichnis)
            $type = $entry->type;
            $res =  $this->listDirectoryRecursive($path);
            if (isset($structure[$type])){
                $structure[$type][] = $res[0];
            }
            else{
                $structure[$type] = $res;
            }
        }
        return view('showSourceCode.index', compact('structure','startFile','startFileContent'));
    }

    function listDirectoryRecursive($directory, $depth = 0) {
        if (!is_dir($directory)) {
            $result[] = [
                    'file' => basename($directory),
                    'path' => $directory,
                    'depth' => 0,
                    'type' => 'file' // Typ: Ordner
                ];
            return $result;
        }

        $result = [];

        // Überprüfen, ob das Verzeichnis existiert und lesbar ist
        if (!is_dir($directory)) {
            return $result; // Leeres Array zurückgeben, falls das Verzeichnis nicht existiert
        }

        // Öffnen des Verzeichnisses
        if ($handle = opendir($directory)) {
            $folders = [];
            $files = [];

            // Alle Einträge im Verzeichnis sammeln
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $path = $directory . '/' . $entry;
                    if (is_dir($path)) {
                        $folders[] = $entry; // Ordner speichern
                    } else {
                        $files[] = $entry; // Dateien speichern
                    }
                }
            }
            closedir($handle);

            // Ordner und Dateien alphabetisch sortieren
            sort($folders);
            sort($files);

            // Zuerst die Ordner durchlaufen
            foreach ($folders as $folder) {
                $path = $directory . '/' . $folder;
                $result[] = [
                    'file' => $folder,
                    'path' => $path,
                    'depth' => $depth,
                    'type' => 'folder' // Typ: Ordner
                ];

                // Rekursiver Aufruf für Unterverzeichnisse
                $subResult = $this->listDirectoryRecursive($path, $depth + 1);
                $result = array_merge($result, $subResult);
            }

            // Dann die Dateien durchlaufen
            foreach ($files as $file) {
                $path = $directory . '/' . $file;
                $result[] = [
                    'file' => $file,
                    'path' => $path,
                    'depth' => $depth,
                    'type' => 'file' // Typ: Datei
                ];
            }
        }

        return $result;
    }

    public function getCode(Request $request){
        // Bis 2026-11-15 las das jede beliebige Datei (auch .env, /etc/passwd).
        // Jetzt nur noch Quellcode innerhalb des Projekts.
        $path = $this->allowedSourcePath((string) $request['path']);
        if ($path === null) {
            return Response::json(['content' => 'Datei nicht freigegeben.', 'linecount' => 1], 403);
        }

        $content = file_get_contents($path);

        return Response::json([
            'content' => $content,
            'linecount' => substr_count($content, "\n") + 1,
        ], 200);
    }

    /**
     * Realer Pfad, wenn die Datei im Projekt liegt, kein Geheimnis enthaelt
     * und eine Quellcode-Endung hat - sonst null.
     */
    private function allowedSourcePath(string $path): ?string
    {
        $base = realpath(base_path());
        $real = realpath($path);
        if ($real === false || !is_file($real) || !str_starts_with($real, $base . DIRECTORY_SEPARATOR)) {
            return null;
        }

        $rel = str_replace('\\', '/', substr($real, strlen($base) + 1));
        $blocked = '#(^|/)\.|^(storage|vendor|node_modules|bootstrap/cache|cert|config)/#';
        if (preg_match($blocked, $rel)) {
            return null;
        }

        $ext = strtolower(pathinfo($real, PATHINFO_EXTENSION));
        $allowed = ['php', 'js', 'jsx', 'ts', 'tsx', 'vue', 'css', 'scss', 'html', 'md', 'json', 'blade', 'txt', 'mjs'];
        return in_array($ext, $allowed, true) ? $real : null;
    }


    public function getTree($path)
        {
            $tree = [];

            $branch = [
            'dir' => basename($path)
            ];

            foreach (File::files($path) as $file) {
                $branch['file'][] = $file;
            }

            foreach (File::directories($path) as $directory) {
            $branch['file'][] = $this->getTree($directory);
            }

            $finalStruct =  array_merge($tree, $branch);
            return $finalStruct;
        }





}
