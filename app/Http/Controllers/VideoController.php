<?php

namespace App\Http\Controllers;
#use Illuminate\Support\Facades\Process;
use Symfony\Component\Process\Process;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(){
        return view('video.index');
    }

    public function create(){
        $command  = explode(' ', 'ffmpeg -r 1/5 -start_number 1 -i E:\web\my\tmp\ffmpeg\%04d.JPG -i audio.mp3 -c:v libx264 -r 30 -pix_fmt yuv420p out'.date('H_i_s').'.mp4');
        $process = new Process($command);
        $process->run();
        echo $process->getOutput();
    }
}
