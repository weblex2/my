<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use App\Models\Logs;
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
                $fileSize = filesize($backupPath);
                // Umrechnung von Byte in GB (1 GB = 1073741824 Bytes)
                $fileSizeInGB = round($fileSize / 1073741824,2);
                Log::channel('database')->info('Database backup successfully created. ('.$fileSizeInGB.' GB)', ['type' =>'DB']);
                $this->uploadToS3($backupPath);
                Log::channel('database')->info('Database backup successfully moved to S3.', ['type' =>'DB']);
                return "Backup erfolgreich erstellt: (".$fileSizeInGB." GB)" . $backupPath;
            } catch (ProcessFailedException $exception) {
                $logMessage = $exception->getMessage();
                Log::channel('database')->error('Error while creating database backup.', ['type' =>'DB', 'exception' => $exception->getMessage()]);
                return "Fehler beim Backup: " . $exception->getMessage();
            }
        }

        public function uploadToS3($backupPath){
            $res = Storage::disk('s3')->put('sql_backup/laravel_'.date('Ymd').".sql", file_get_contents($backupPath));
        }

        public function showLogs(){
            $logs  = Logs::where('created_at', "like", date('Y-m-d')."%")->orderBy('created_at', 'DESC')->get();
            return view('logs.logs', compact('logs'));
        }
}
