<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cv;
use Collections\Collection;
use Carbon\Carbon;
use Route;


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

    public function indexm(){
    
        return "yoooo";
    }

    public function edit(){
        return $this->index('', 1);
    }
}
