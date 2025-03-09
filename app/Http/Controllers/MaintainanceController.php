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
    $containerName = 'mysql8'; // Name deines Docker-Containers
    $dbUser = env('DB_USERNAME', 'root');
    $dbPassword = "!Cyberbob03"; #env('DB_PASSWORD', 'secret');
    $dbName = env('DB_DATABASE', 'laravel');
    $backupPath = storage_path('app/laravel.sql');

    $command = "docker exec $containerName mysqldump -u $dbUser -p$dbPassword $dbName > $backupPath";
    echo "<pre>". $command."<br>";
    $process = Process::fromShellCommandline($command);
        try {
            $process->mustRun();
            return "Backup erfolgreich erstellt: " . $backupPath;
        } catch (ProcessFailedException $exception) {
            return "Fehler beim Backup: " . $exception->getMessage();
        }
    }
}
