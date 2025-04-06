<?php

namespace App\Livewire;

use Livewire\Component;
use Artisan;

class ClearCache extends Component
{
    public $message = '';
    public $isProcessing = false;

    // Funktion zum Leeren des Caches
    public function clearCache()
    {
        $this->message = 'Leere Cache...<br>';
        Artisan::call('cache:clear');
        $this->message .= 'Cache wurde gelöscht.<br>';
    }

    // Funktion zum Leeren der Konfiguration
    public function clearConfig()
    {
        $this->message = 'Leere Config...<br>';
        Artisan::call('config:clear');
        $this->message .= 'Config wurde gelöscht.<br>';
    }

    // Funktion zum Leeren der Views
    public function clearViews()
    {
        $this->message = 'Leere Views...<br>';
        Artisan::call('view:clear');
        $this->message .= 'Views wurden gelöscht.<br>';
    }

    // Funktion zum Leeren von allen Caches
    public function clearAll()
    {
        $this->message = 'Leere Cache...<br>';
        Artisan::call('cache:clear');
        $this->message .= 'Cache wurde gelöscht.<br>';

        $this->message .= 'Leere Config...<br>';
        Artisan::call('config:clear');
        $this->message .= 'Config wurde gelöscht.<br>';

        $this->message .= 'Leere Views...<br>';
        Artisan::call('view:clear');
        $this->message .= 'Views wurden gelöscht.<br>';
    }

    // Funktion für git pull
    public function gitPull()
    {
        $this->message = 'Führe git pull aus...<br>';
        $output = shell_exec('git pull 2>&1');
        $this->message .= nl2br($output) . '<br>';
    }

    // Funktion für git stash
    public function gitStash()
    {
        $this->message = 'Führe git stash aus...<br>';
        $output = shell_exec('git stash 2>&1');
        $this->message .= nl2br($output) . '<br>';
    }

    // Funktion für git push
    public function gitPush()
    {
        $this->message = 'Führe git push aus...<br>';
        $output = shell_exec('git push 2>&1');
        $this->message .= nl2br($output) . '<br>';
    }

    // Funktion für npm run dev
    public function npmRunDev()
    {
        $this->message = 'Starte npm run dev...<br>';
        $this->streamCommand('npm run dev');
    }

    // Funktion für npm run build
    public function npmRunBuild()
    {

        $projectRoot = base_path();  // Laravel's base_path() gibt das Stammverzeichnis zurück
        // Setze das Arbeitsverzeichnis
        chdir($projectRoot);
        $this->message = getCwd().' <br>Starte npm run build...<br>';
        $this->streamCommand('npx run build');
    }

    // Hilfsfunktion zum Streamen der Ausgabe von Befehlen
    private function streamCommand($command)
    {
        $this->isProcessing = true;
        $output = shell_exec($command . ' 2>&1');
        $this->isProcessing = false;
        $this->message .= nl2br($output) . '<br>';
    }

    public function render()
    {
        return view('livewire.clear-cache');
    }
}
