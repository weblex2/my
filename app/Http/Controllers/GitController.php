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

    public function runBuild()
    {
        // Verzeichnis, in dem das Projekt liegt
        $projectDirectory = '/var/www/html/my';  // Dein Verzeichnis hier anpassen

        // Der Befehl zum Ausführen von npm run build
        $command = "cd {$projectDirectory} && sudo -u apache npm run build";

        // Starten eines Prozesses und Öffnen eines Streams
        $process = proc_open($command, [
            1 => ['pipe', 'w'], // stdout stream für die Ausgabe
            2 => ['pipe', 'w'], // stderr stream für Fehler
        ], $pipes);

        // Wenn der Prozess erfolgreich geöffnet wurde
        if (is_resource($process)) {
            // Das Standardausgabe-Stream (stdout) auslesen
            while ($line = fgets($pipes[1])) {
                echo $line;          // Ausgabe sofort an den Browser senden
                ob_flush();          // Puffer leeren und an Browser senden
                flush();             // Sicherstellen, dass alles sofort gesendet wird
                usleep(100000);      // Kleine Pause, um die Ausgabe etwas zu drosseln (optional)
            }

            // Fehlerausgabe (stderr) auslesen
            while ($line = fgets($pipes[2])) {
                echo $line;          // Fehler sofort an den Browser senden
                ob_flush();
                flush();
            }

            // Prozess beenden
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        } else {
            echo "Fehler beim Starten des Prozesses.";
        }



    }
}

