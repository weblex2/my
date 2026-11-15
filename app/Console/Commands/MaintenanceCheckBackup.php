<?php

namespace App\Console\Commands;

use App\Http\Controllers\NtfyController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Prueft das naechtliche DB-Backup (maintenance:run backupdb, 02:00 UTC) und
 * meldet das Ergebnis per NTFY.
 *
 *   php artisan maintenance:check-backup            # Ergebnis an Topic "ssh"
 *   php artisan maintenance:check-backup --topic=x
 */
class MaintenanceCheckBackup extends Command
{
    protected $signature = 'maintenance:check-backup {--topic=ssh : NTFY-Topic fuer die Meldung}';

    protected $description = 'Prueft, ob das heutige DB-Backup lokal erstellt und nach S3 hochgeladen wurde, und meldet es per NTFY';

    public function handle(): int
    {
        $problems = [];
        $details  = [];

        // 1) lokale Dump-Datei von heute
        $local = storage_path('app/laravel.sql');
        if (!is_file($local)) {
            $problems[] = 'keine lokale Backup-Datei';
        } else {
            $age  = time() - filemtime($local);
            $size = filesize($local);
            $details[] = sprintf('lokal %.2f GB, vor %d min', $size / 1073741824, $age / 60);
            if ($age > 6 * 3600) {
                $problems[] = 'lokale Datei ist aelter als 6 Stunden';
            }
            if ($size < 1024 * 1024) {
                $problems[] = 'lokale Datei ist fast leer';
            }
        }

        // 2) heutige Datei auf S3
        $s3name = 'sql_backup/laravel_' . date('Ymd') . '.sql';
        try {
            if (Storage::disk('s3')->exists($s3name)) {
                $details[] = sprintf('S3 %s (%.2f GB)', $s3name, Storage::disk('s3')->size($s3name) / 1073741824);
            } else {
                $problems[] = "nicht auf S3 ($s3name)";
            }
        } catch (\Throwable $e) {
            $problems[] = 'S3 nicht pruefbar: ' . substr($e->getMessage(), 0, 120);
        }

        $ok = empty($problems);
        $text = ($ok ? 'Backup ok: ' : 'Backup FEHLER: ' . implode('; ', $problems) . '. ') . implode(', ', $details);
        $this->line($text);

        (new NtfyController())->sendMessage((object) [
            'priority'    => $ok ? 3 : 5,
            'topic'       => $ok ? 'noppal.de Backup ok' : 'noppal.de Backup FEHLGESCHLAGEN',
            'tags'        => $ok ? 'white_check_mark,floppy_disk' : 'rotating_light,floppy_disk',
            'description' => $text,
        ], $this->option('topic'));

        return $ok ? self::SUCCESS : self::FAILURE;
    }
}
