<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;
use Config; 
use App\Http\Controllers\ChatController;
//use App\Http\Controllers\VideoProcessBuilder;

//use Response;

class yt2mp3 extends Controller
{

    public function test(){
        $str="ERR > [debug] Command-line config: ['--ignore-config', '--ignore-errors', '--write-info-json', '--output=/var/www/html/my/mp3/%(title)s.%(ext)s', '--verbose', '--extract-audio', '--audio-format=mp3', '--audio-quality=0', 'https://www.youtube.com/watch?v=Qq4j1LtCdww'] ERR > [debug] Encodings: locale UTF-8, fs utf-8, pref UTF-8, out utf-8 (No ANSI), error utf-8 (No ANSI), screen utf-8 (No ANSI) ERR > [debug] yt-dlp version nightly@2024.02.24.232815 from yt-dlp/yt-dlp-nightly-builds [5eedc208e] (pip) ERR > [debug] Python 3.9.16 (CPython x86_64 64bit) - Linux-6.1.38-59.109.amzn2023.x86_64-x86_64-with-glibc2.34 (OpenSSL 3.0.8 7 Feb 2023, glibc 2.34) ERR > [debug] exe versions: ffmpeg 6.1-static (setts) [debug] Optional libraries: Cryptodome-3.20.0, brotli-1.1.0, certifi-2024.02.02, mutagen-1.47.0, requests-2.31.0, sqlite3-3.40.0, urllib3-2.2.1, websockets-12.0 [debug] Proxy map: {} [debug] Request Handlers: urllib, requests, websockets ERR > [debug] Loaded 1833 extractors OUT > [youtube] Extracting URL: https://www.youtube.com/watch?v=Qq4j1LtCdww OUT > [youtube] Qq4j1LtCdww: Downloading webpage OUT > [youtube] Qq4j1LtCdww: Downloading ios player API JSON OUT > [youtube] Qq4j1LtCdww: Downloading android player API JSON OUT > [youtube] Qq4j1LtCdww: Downloading m3u8 information ERR > [debug] Sort order given by extractor: quality, res, fps, hdr:12, source, vcodec:vp9.2, channels, acodec, lang, proto ERR > [debug] Formats sorted by: hasvid, ie_pref, quality, res, fps, hdr:12(7), source, vcodec:vp9.2(10), channels, acodec, lang, proto, size, br, asr, vext, aext, hasaud, id OUT > [info] Qq4j1LtCdww: Downloading 1 format(s): 251 OUT > [info] Writing video metadata as JSON to: /var/www/html/my/mp3/Alice Cooper - Poison.info.json ERR > [debug] Invoking http downloader on \"https://rr2---sn-vgqsknzr.googlevideo.com/videoplayback?expire=1708899208&ei=KGfbZdLkL4yM_9EPreWvgAo&ip=3.217.55.149&id=o-AEMVe8YyOhnIWsB-1P40fjUMzIXdDcvrRFiF1GGdMA1L&itag=251&source=youtube&requiressl=yes&xpc=EgVo2aDSNQ%3D%3D&mh=oQ&mm=31%2C26&mn=sn-vgqsknzr%2Csn-p5qs7n6d&ms=au%2Conr&mv=u&mvi=2&pl=22&gcr=us&spc=UWF9f-Gua6i_AbhthG6ZRztoQZES9gPsiBNsvA6ow1PLXTk&vprv=1&svpuc=1&mime=audio%2Fwebm&gir=yes&clen=4267867&dur=268.281&lmt=1699492424558331&mt=1708877214&fvip=4&keepalive=yes&fexp=24007246&c=ANDROID&txp=4532434&sparams=expire%2Cei%2Cip%2Cid%2Citag%2Csource%2Crequiressl%2Cxpc%2Cgcr%2Cspc%2Cvprv%2Csvpuc%2Cmime%2Cgir%2Cclen%2Cdur%2Clmt&sig=AJfQdSswRQIhAM6vPKJk0NjM4Jqh4KUieZFf8LZFdtFk4CaRIW-QUXaAAiAcA3R-XfHd5XPDkeWR3aFVNgph3O9QqfiQ7w6Lxe3LSg%3D%3D&lsparams=mh%2Cmm%2Cmn%2Cms%2Cmv%2Cmvi%2Cpl&lsig=APTiJQcwRQIgXkuxrx-n4_LvbJGPya5EnoBYvyKTT5YYySW9YDRo3nUCIQDaoC_7U-gfsPXGV4_MkVKGO5hHHaALGOwlcB6r4oQBdA%3D%3D\" OUT > [download] Destination: /var/www/html/my/mp3/Alice Cooper - Poison.webm OUT > [download] 0.0% of 4.07MiB at 324.08KiB/s ETA 00:12OUT > [download] 0.1% of 4.07MiB at 774.67KiB/s ETA 00:05OUT > [download] 0.2% of 4.07MiB at 1.56MiB/s ETA 00:02OUT > [download] 0.4% of 4.07MiB at 2.99MiB/s ETA 00:01OUT > [download] 0.7% of 4.07MiB at 1.60MiB/s ETA 00:02OUT > [download] 1.5% of 4.07MiB at 2.07MiB/s ETA 00:01OUT > [download] 3.0% of 4.07MiB at 2.20MiB/s ETA 00:01OUT > [download] 6.1% of 4.07MiB at 3.10MiB/s ETA 00:01OUT > [download] 12.3% of 4.07MiB at 4.81MiB/s ETA 00:00OUT > [download] 24.5% of 4.07MiB at 7.96MiB/s ETA 00:00OUT > [download] 49.1% of 4.07MiB at 13.43MiB/s ETA 00:00OUT > [download] 98.3% of 4.07MiB at 21.94MiB/s ETA 00:00OUT > [download] 100.0% of 4.07MiB at 22.16MiB/s ETA 00:00OUT > [download] 100% of 4.07MiB in 00:00:00 at 15.62MiB/s OUT > ERR > [debug] ffmpeg command line: ffmpeg -i 'file:/var/www/html/my/mp3/Alice Cooper - Poison.webm' OUT > [ExtractAudio] Destination: E:\web\my\mp3\Alice Cooper - Poison.mp3 ERR > [debug] ffmpeg command line: ffmpeg -y -loglevel repeat+info -i 'file:/var/www/html/my/mp3/Alice Cooper - Poison.webm' -vn -acodec libmp3lame -q:a 0.0 -movflags +faststart 'file:/var/www/html/my/mp3/Alice Cooper - Poison.mp3' OUT > Deleting original file /var/www/html/my/mp3/Alice Cooper - Poison.webm (pass -k to keep)";
        $str=substr($str,strpos($str,'[ExtractAudio] Destination: ')+28, strlen($str));
        $extpos = strpos($str,'.mp3');
        $str = substr($str,0,$extpos+4);
        $filename = basename($str);
        return response()->download($str, $filename);
        //echo $str;
    }

    public function download($url){    
        //dump(phpinfo());
        //$processBuilder = new VideoProcessBuilder();

        //$processBuilder = new ProcessBuilder();

        // Provide your custom process builder as the first argument.
       //$yt = new YoutubeDl($processBuilder);
        $yt = new YoutubeDl();
        //$yt = new YoutubeDl();
        //$yt->setBinPath("C:\Python27\yt-dlp.exe");
        //$yt->setPythonPath("C:\Python311\python.exe");
        //$yt->setBinPath('C:\Python311\yt-dlp.exe');
        //$yt->setBinPath('/home/ec2-user/.local/bin/yt-dlp');
        //$yt->setBinPath('C:\Python311\yt-dlp.exe');
         // Enable debugging
        $this->log = ""; 
        $this->chat = new ChatController(); 
        $downloadLocation = storage_path("\\").\Config('video.downloadLocation');
        $yt->debug(function ($type, $buffer) {
            if (!isset($this->log)){
                $this->log="";
            }
            if (\Symfony\Component\Process\Process::ERR === $type) {
                $this->log.= 'ERR > ' . $buffer;
            } else {
                $this->log.= 'OUT > ' . $buffer;
            }
        });
        $yt->onProgress(static function (?string $progressTarget, string $percentage, string $size, string $speed, string $eta, ?string $totalTime): void {
            $status = "Download file: $progressTarget; Percentage: $percentage; Size: $size";
            if ($speed) {
                $status.= "; Speed: $speed";
            }
            if ($eta) {
                $status.= "; ETA: $eta";
            }
            if ($totalTime !== null) {
                $status.= "; Downloaded in: $totalTime";
            }
            $this->chat->sendMessage($status);
        });
        $collection = $yt->download(
            Options::create()
                ->downloadPath($downloadLocation)
                ->extractAudio(true)
                ->verbose(true)
                ->audioFormat('mp3')
                ->audioQuality('0') // best
                ->output('%(title)s.%(ext)s')
                ->url($url)
        );

        foreach ($collection->getVideos() as $video) {
            if ($video->getError() !== null) {
                echo "Error downloading video: {$video->getError()}.";
            } else {
                $video->getFile(); // audio file
            }
        }
        
        $log  = $this->log;
        $error=0;
        if (!strpos($log,'[ExtractAudio] Destination: ')) {
            $error = 1;
        }
        $error=0;
        if ($error==0) {
            $log=substr($log,strpos($log,'[ExtractAudio] Destination: ')+28, strlen($log));
            $extpos = strpos($log,'.mp3');
            $file = substr($log,0,$extpos+4);
            $filename = basename($file);
            echo "<a href='yt2mp3/download/".$filename."'><img src='public/img/download.png'> $filename</a>";
        }
        else{
          $x=1;  
          return "Error: ". $log;
          return Response::json([
                'data' => $log,
                'status' => 'error',
            ], 500);
        }
        //echo $this->log;
    }  

    public function download2(){    
        $url="https://www.youtube.com/watch?v=Qq4j1LtCdww";
        //dump(phpinfo());
        //$processBuilder = new VideoProcessBuilder();

        //$processBuilder = new ProcessBuilder();

        // Provide your custom process builder as the first argument.
       //$yt = new YoutubeDl($processBuilder);
        $yt = new YoutubeDl();
        //$yt = new YoutubeDl();
        //$yt->setBinPath("C:\Python27\yt-dlp.exe");
        //$yt->setPythonPath("C:\Python311\python.exe");
        //$yt->setBinPath('C:\Python311\yt-dlp.exe');
        //$yt->setBinPath('/home/ec2-user/.local/bin/yt-dlp');
        //$yt->setBinPath('C:\Python311\yt-dlp.exe');
         // Enable debugging
        $this->log = ""; 
        $this->chat = new ChatController(); 
        $downloadLocation = storage_path("\\").\Config('video.downloadLocation');
        $yt->debug(function ($type, $buffer) {
            if (!isset($this->log)){
                $this->log="";
            }
            if (\Symfony\Component\Process\Process::ERR === $type) {
                $this->log.= 'ERR > ' . $buffer;
            } else {
                $this->log.= 'OUT > ' . $buffer;
            }
        });
        $yt->onProgress(static function (?string $progressTarget, string $percentage, string $size, string $speed, string $eta, ?string $totalTime): void {
            $status = "Download file: $progressTarget; Percentage: $percentage; Size: $size";
            if ($speed) {
                $status['Speed'] =  $speed;
            }
            if ($eta) {
                $status['ETA'] =  $eta;
            }
            if ($totalTime !== null) {
                $status['TotalTime'] = $totalTime;
            }
            ChatController::sendMessage(json_encode($status));
        });
        $collection = $yt->download(
            Options::create()
                ->downloadPath($downloadLocation)
                ->extractAudio(true)
                ->verbose(true)
                ->audioFormat('mp3')
                ->audioQuality('0') // best
                ->output('%(title)s.%(ext)s')
                ->url($url)
        );

        foreach ($collection->getVideos() as $video) {
            if ($video->getError() !== null) {
                echo "Error downloading video: {$video->getError()}.";
            } else {
                $video->getFile(); // audio file
            }
        }
        
        $log  = $this->log;
        $error=0;
        if (!strpos($log,'[ExtractAudio] Destination: ')) {
            $error = 1;
        }
        if ($error==0) {
            $log=substr($log,strpos($log,'[ExtractAudio] Destination: ')+28, strlen($log));
            $extpos = strpos($log,'.mp3');
            $file = substr($log,0,$extpos+4);
            $filename = basename($file);
            echo "<a href='yt2mp3/download/".$filename."'><img src='public/img/download.png'> $filename</a>";
        }
        else{
          $x=1;  
          return "Error: ". $log;
          return Response::json([
                'data' => $log,
                'status' => 'error',
            ], 500);
        }
        //echo $this->log;
    }  

    public function getmp3(Request $request){
        $url = $request->url;
        $this->download($url);
    }

    public function index(){
        return view('yt2mp3.index');
    }

    public function downloadFile($file){
        $file = urldecode($file);
        $filename=basename($file);
        echo $file;
        return response()->download('../mp3/'.$file, $filename);
    } 
}
