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
use Session;


class CvController extends Controller
{
    public function index($name='', $edit=0){

        $cv_data = Cv::orderBy('type')->orderBy('date_from', 'desc')->get();
        #echo json_encode($cv_data);
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

    public function json(){
        $data = Session::get('cvdata', false);
        unset($data['edit']);
        $edit = request('edit');
        //$data = false;
        if (!$data || $data = []){
            $cv_data = Cv::orderBy('type')->orderBy('date_from', 'desc')->get();
            foreach ($cv_data as $i =>  $row){
                $cv_data[$i]['value'] = str_replace("\n","<br/>", $row['value']);
            }
            Carbon::setLocale('de');
            $data = [];
            foreach ($cv_data as $i => $dat){
                $data[$dat->type][] = $dat;
            }


            $data['AU'] = [];
            $data['PD'] = [];
            $data['KN'] = [];
            $data['BL'] = [];
            $data['LANG'] = [];

            session(['cvdata' => $data]);
        }
        $data['edit'] = false;
        session(['cvdata' => $data]);

        return response()->json($data, 200);
    }

    public function saveJson(Request $request){
        $data = $request->all();
        if ($data){
            //$data['edit'] = false;
            session(['cvdata' => $data]);
        }
        return response()->json($data, 200);
    }

    public function downloadJson(){
        $data = json_encode(Session::get('cvdata', false),JSON_PRETTY_PRINT);
        //return Response::download($data);
        $filename  = "cv_".date('YmdHis').".json";
        return response($data, 200, [
                'Content-Disposition' => 'attachment; filename="'.$filename.'"'
        ]);
    }

    public function uploadJson(Request $request){
        $req = $request->all();
        $file = $request->file('jsonfile');
        $data = file_get_contents($file->getRealPath());
        $data = json_decode($data, true);
        session(['cvdata' => $data]);
        return response()->json($data, 200);
    }

    public function pdf(){
        //dd($_SERVER);
        ini_set('max_execution_time', 300);
        $html = file_get_contents("https://noppal.de/cv");
        $html = str_replace('downloadlink', 'downloadlink hidden invisible', $html);

        $filename = 'Alex Noppenberger CV '.date('Y-m-d').'.pdf';
        if (in_array($_SERVER['SERVER_PORT'],["80", "433"])) {
            Browsershot::html($html)
                ->setNodeBinary('/usr/bin/node')
                ->setNpmBinary('/usr/bin/npm')
                ->save(storage_path().DIRECTORY_SEPARATOR.$filename);
        }
        else{
            Browsershot::html($html)
                ->save(storage_path().DIRECTORY_SEPARATOR.$filename);
        }

          return response()->download(storage_path().DIRECTORY_SEPARATOR.$filename);

    }

    public function edit(){
        return $this->index('', 1);
    }
}
