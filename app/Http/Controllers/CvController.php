<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cv;
use Collections\Collection;
use Carbon\Carbon;
use Route;
use Response;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Browsershot\Browsershot;


class CvController extends Controller
{
    public function index($name='', $edit=0){
        
        $cv_data = Cv::orderBy('type')->orderBy('date_from', 'desc')->get();
        Carbon::setLocale('de');
        $data = [];
        foreach ($cv_data as $i => $dat){
            $data[$dat->type][] = $dat;
            // Format Dates
            $x= Carbon::parse($dat['date_from'])->format('Y-F');
            //echo $x."<br>";
        }
        return view('cv', compact('data', 'edit'));
    }

    public function json($name='', $edit=0){
        $cv_data = Cv::orderBy('type')->orderBy('date_from', 'desc')->get();
        Carbon::setLocale('de');
        $data = [];
        foreach ($cv_data as $i => $dat){
            $data[$dat->type][] = $dat;
            // Format Dates
            $x= Carbon::parse($dat['date_from'])->format('Y-F');
            //echo $x."<br>";
        }
        //$data = json_encode($data);
        return response()->json($data, 200);
    }

    public function indexm(){
    
        return "yoooo";
    }


    public function pdf(){
        //echo public_path().'/cv.pdf';
        ini_set('max_execution_time', 300);    
        Browsershot::url('http://noppal.de/cv')
            ->setNodeBinary('/home/ec2-user/.nvm/versions/node/v20.11.0/bin/node')
            ->setNpmBinary('/home/ec2-user/.nvm/versions/node/v20.11.0/bin/npm')
            ->save(storage_path().'/tmp/cv.pdf');
        //Pdf::url('http://noppal.de/cv')->save('cv.pdf');
    }

    public function edit(){
        return $this->index('', 1);
    }
}
