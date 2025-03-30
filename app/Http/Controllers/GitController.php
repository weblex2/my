<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GitController extends Controller
{
    public function pull()
    {
        // Setze das Repo-Verzeichnis (Passe den Pfad an!)
        $repoPath = base_path(); // oder "/var/www/html/deinprojekt"

        // Führe "git pull" aus und speichere die Ausgabe
        $output = shell_exec("cd $repoPath && git pull 2>&1");

        // Gib das Ergebnis an die View zurück
        return view('laravelMyAdmin.tools', compact('output'));
    }
}

