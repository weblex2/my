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
    public function index(){
        return view('maintainance.index');
    }

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
                $s3_filename = 'sql_backup/laravel_'.date('Ymd').".sql";
                $this->uploadToS3($s3_filename, $backupPath);
                Log::channel('database')->info('Database backup successfully moved to S3.('. $s3_filename.')', ['type' =>'DB']);
                // Prüfen, ob der heutige Upload auf S3 existiert
                if (Storage::disk('s3')->exists($s3_filename)) {
                    $yesterdayFile = 'sql_backup/laravel_' . date('Ymd', strtotime('-1 day')) . '.sql';
                    // Prüfen, ob die Datei auf S3 existiert
                    if (Storage::disk('s3')->exists($yesterdayFile)) {
                        // Datei von S3 löschen
                        Storage::disk('s3')->delete($yesterdayFile);
                        echo "Das gestrige Backup ($yesterdayFile) wurde erfolgreich von S3 gelöscht.";
                        Log::channel('database')->info('Database backup from yesterday ('.$yesterdayFile.') successfully deleted from S3.', ['type' =>'DB']);
                    } else {
                        echo "Das gestrige Backup ($yesterdayFile) existiert nicht auf S3.";
                    }
                }
                return "Backup erfolgreich erstellt: (".$fileSizeInGB." GB)" . $backupPath;

            } catch (ProcessFailedException $exception) {
                $logMessage = $exception->getMessage();
                Log::channel('database')->error('Error while creating database backup.', ['type' =>'DB', 'exception' => $exception->getMessage()]);
                return "Fehler beim Backup: " . $exception->getMessage();
            }
        }

        public function uploadToS3($s3_filename, $backupPath){
            $res = Storage::disk('s3')->put($s3_filename, file_get_contents($backupPath));
        }

        public function showLogs(){
            $logs  = Logs::where('created_at', "like", date('Y-m-d')."%")->orderBy('created_at', 'DESC')->get();
            return view('logs.logs', compact('logs'));
        }

        public function refreshLogs(Request $request){
            $req = $request->all();
            $from  = $req['from'] ?? date('Y-m-d');
            $to = $req['to'] ?? date('Y-m-d');
            $type = $req['type'];
            $level = $req['level'];
            $types = Logs::select('type')->distinct()->orderBy('type','ASC')->get();
            $logs = Logs::whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                          ->where('level',"like", $level."%")
                          ->where('type',"like", $type."%")
                          ->orderBy('created_at', 'DESC')
                          ->get();
            return view('logs.logs', compact('logs', 'from', 'to','level','type','types'));
        }
}
