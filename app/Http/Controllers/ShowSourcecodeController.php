<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

class ShowSourcecodeController extends Controller
{
    public function index(String $mypath=""){
        $path= base_path()."/resources/js/components/ReactTutorial";


        // 🌍 Hauptverzeichnis setzen (hier z. B. das aktuelle Verzeichnis)
        $structure = $this->listDirectoryRecursive($path);
       
        #foreach ($structure as $item) {
        #    echo str_repeat('&nbsp;', $item['depth']*4) . $item['file'] . " Path: ". $item['path'] . "<br>";
        #}
        #dump($structure);
        return view('showSourceCode.index', compact('structure'));
    }

    function listDirectoryRecursive($directory, $depth = 0) {
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
        $path = $request['path'];
        $content = file_get_contents($path);
        return Response::json([
            'content' => $content,
        ], 200);
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
