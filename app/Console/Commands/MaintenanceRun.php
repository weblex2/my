<?php

namespace App\Console\Commands;

use App\Http\Controllers\MaintainanceController;
use Illuminate\Console\Command;

/**
 * Wartungsaufgaben direkt auf dem Server statt per curl auf die Web-Routen.
 * Die Routen /maintainance/* verlangen seit 2026-11-15 einen Admin-Login;
 * die naechtlichen Cronjobs laufen deshalb ueber diesen Befehl.
 *
 *   php artisan maintenance:run backupdb
 *   php artisan maintenance:run certlog2db
 */
class MaintenanceRun extends Command
{
    protected $signature = 'maintenance:run {task : backupdb oder certlog2db}';

    protected $description = 'Fuehrt eine Wartungsaufgabe aus (DB-Backup nach S3, Zertifikats-Log in die DB)';

    public function handle(): int
    {
        $controller = app(MaintainanceController::class);

        ob_start();  // die Controller-Methoden geben HTML aus
        try {
            match ($this->argument('task')) {
                'backupdb'   => $controller->backupDatabase(),
                'certlog2db' => $controller->certLog2DB(),
                default      => throw new \InvalidArgumentException('Unbekannte Aufgabe: ' . $this->argument('task')),
            };
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->error($e->getMessage());
            return self::FAILURE;
        }
        $html = ob_get_clean();

        $this->line(trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", (string) $html))));
        return self::SUCCESS;
    }
}
