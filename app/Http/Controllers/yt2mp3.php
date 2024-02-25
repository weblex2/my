<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class yt2mp3 extends Controller
{
    public function download(){    

        $yt = new YoutubeDl();
        //$yt->setBinPath("C:\Python27\yt-dlp.exe");
        //$yt->setPythonPath("C:\Python311\python.exe");
        //$yt->setBinPath('C:\Python311\yt-dlp.exe');
        //$yt->setBinPath('/home/ec2-user/.local/bin/yt-dlp');
        //$yt->setBinPath('C:\Python311\yt-dlp.exe');
         // Enable debugging
        $yt->debug(function ($type, $buffer) {
            if (\Symfony\Component\Process\Process::ERR === $type) {
                echo 'ERR > ' . $buffer;
            } else {
                echo 'OUT > ' . $buffer;
            }
        });
        $collection = $yt->download(
            Options::create()
                ->downloadPath('mp3')
                ->extractAudio(true)
                ->verbose(true)
                ->audioFormat('mp3')
                ->audioQuality('0') // best
                ->output('%(title)s.%(ext)s')
                ->url('https://www.youtube.com/watch?v=oDAw7vW7H0c')
        );

        foreach ($collection->getVideos() as $video) {
            if ($video->getError() !== null) {
                echo "Error downloading video: {$video->getError()}.";
            } else {
                $video->getFile(); // audio file
            }
        }
    }  

    public function getmp3(){
        $this->download();
    }
}
