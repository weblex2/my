<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MaintainanceController extends Controller
{
    function backupDatabase()
    {
        ini_set('memory_limit', '2G');
        $containerName = 'mysql8'; // Name deines Docker-Containers
        $dbUser = env('DB_USERNAME', 'root');
        $dbPassword = env('DB_PASSWORD', '!Cyberbob03');
        $dbName = env('DB_DATABASE', 'laravel');
        $backupPath = storage_path('app/laravel.sql');

        $command = "docker exec $containerName mysqldump -u $dbUser -p$dbPassword $dbName > $backupPath";
        echo "<pre>". $command."<br>";
        $process = Process::fromShellCommandline($command);
            try {
                $process->mustRun();
                $this->uploadToS3($backupPath);
                return "Backup erfolgreich erstellt: " . $backupPath;
            } catch (ProcessFailedException $exception) {
                return "Fehler beim Backup: " . $exception->getMessage();
            }
        }

        public function uploadToS3($backupPath){
            $res = Storage::disk('s3')->put('sql_backup/laravel_'.date('Ymd').".sql", file_get_contents($backupPath));
        }
}
