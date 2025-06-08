<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeleteFilamentResourceCommand extends Command
{
    protected $signature = 'make:delete-filament-resource {name : Name der Resource ohne "Resource"-Suffix} {--force : Lösche ohne Nachfrage}';

    protected $description = 'Löscht eine Filament Resource und die zugehörigen Pages';

    public function handle()
    {
        $name = $this->argument('name');

        $resourceFile = app_path("Filament/Resources/{$name}Resource.php");
        $pagesDir = app_path("Filament/Resources/{$name}Resource/Pages");

        if (! file_exists($resourceFile) && ! is_dir($pagesDir)) {
            $this->error("Resource oder Pages nicht gefunden: {$name}Resource");
            return 1;
        }

        if (! $this->option('force')) {
            if (! $this->confirm("Willst du die Resource '{$name}Resource' wirklich löschen?")) {
                $this->info('Löschvorgang abgebrochen.');
                return 0;
            }
        }

        // Resource-Datei löschen
        if (file_exists($resourceFile)) {
            unlink($resourceFile);
            $this->info("Resource-Datei gelöscht: {$resourceFile}");
        }

        // Pages-Verzeichnis löschen
        if (is_dir($pagesDir)) {
            $this->deleteDirectory($pagesDir);
            $this->info("Pages-Verzeichnis gelöscht: {$pagesDir}");
        }

        // Tabellenname ableiten (z.B. aus $name)
        // Annahme: Tabellenname ist snake_case und plural, z.B. "Post" => "posts"
        $table = Str::snake(Str::plural($name));

        // Einträge in fil_table_fields löschen
        $deleted = DB::table('fil_table_fields')
            ->where('table', $table)
            ->delete();

        $this->info("{$deleted} Einträge aus fil_table_fields mit table = '{$table}' gelöscht.");


        return 0;
    }

    protected function deleteDirectory(string $dir): void
    {
        $files = scandir($dir);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }
}
