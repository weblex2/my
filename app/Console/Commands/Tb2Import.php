<?php

namespace App\Console\Commands;

use App\Models\GalleryPics;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

/**
 * Einmaliger Massenimport fuer /travel-blog2.
 *
 * Laeuft serverseitig, umgeht damit die Anmeldung des HTTP-Uploads. Nutzt
 * dieselbe BlogCreator/TripAutoAssigner-Kette wie der normale Upload, setzt
 * die erzeugten Fotos aber auf approved = 0.
 */
class Tb2Import extends Command
{
    protected $signature = 'tb2:import
                            {dir : Verzeichnis mit den zu importierenden Bildern}
                            {--pause=1100 : Pause in ms zwischen Dateien (Nominatim-Ratelimit)}';

    protected $description = 'Importiert Fotos als nicht freigegeben fuer /travel-blog2';

    public function handle(): int
    {
        $dir = rtrim($this->argument('dir'), '/\\');

        if (!is_dir($dir)) {
            $this->error("Verzeichnis nicht gefunden: $dir");
            return self::FAILURE;
        }

        $files = collect(glob($dir . '/*'))
            ->filter(fn($f) => is_file($f))
            ->values();

        if ($files->isEmpty()) {
            $this->error('Keine Dateien gefunden.');
            return self::FAILURE;
        }

        $this->info("{$files->count()} Dateien gefunden.");
        $pause = (int) $this->option('pause') * 1000;

        // BlogCreator liest die Sprache aus der Session (BlogCreator.php:224).
        // Im CLI gibt es keine, sonst schlaegt der Insert in gallery_texts fehl.
        session(['lang' => 'DE']);

        $ok = 0;
        $fail = 0;
        $bar = $this->output->createProgressBar($files->count());
        $bar->start();

        foreach ($files as $path) {
            $before = (int) GalleryPics::max('id');

            try {
                // test = true: Datei wird nicht als echter HTTP-Upload validiert
                $upload = new UploadedFile($path, basename($path), null, null, true);

                $request = new Request();
                $request->files->set('file', $upload);
                $request->merge(['auto_assign' => 1]);

                $bc = new \App\MyClasses\BlogCreator($request);
                $bc->uploadFile();
                $bc->loadMedia();
                $bc->createThumbNails();
                $bc->createBlog();

                // Alles neu Angelegte auf "nicht freigegeben" setzen
                GalleryPics::where('id', '>', $before)->update(['approved' => 0]);

                $ok++;
            } catch (\Throwable $e) {
                $fail++;
                $this->newLine();
                $this->warn(basename($path) . ' — ' . $e->getMessage());
            }

            $bar->advance();
            if ($pause > 0) usleep($pause);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("fertig: $ok uebernommen, $fail abgelehnt");
        $this->line('Freigabe unter /travel-blog2/admin');

        return self::SUCCESS;
    }
}
