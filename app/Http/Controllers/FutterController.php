<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Futter;
use App\Models\FutterPerDay;
use File;
use DB;
use Carbon\Carbon;




class FutterController extends Controller
{
    public function index(){
        $futterToday = FutterPerDay::where('day',">=", date('Y-m-d'))->get();
        $ft = [];
        foreach ($futterToday as $fday){
            $ft[$fday->day] = [
                'name' => $fday->futter->name,
                'img'  => url('storage/futter/'.$fday->futter->img)
            ];
        }

        $futter = Futter::inRandomOrder()->limit(3)->get();

        $dates   = $this->getDates()['dates'];
        $datesdb = $this->getDates()['datesdb'];

        foreach ($futter as $i => $f){
            $ing = implode("<br>",$f->ingredients);
            unset($futter[$i]['ingredients']);
            $futter[$i]['ingredients']= $ing==null ? '' : $ing;
        }
        return view('futter.index', compact('futter','ft','dates', 'datesdb'));
    }

    public function getDates(){
        $date=date('Y-m-d');
        $startDate = Carbon::createFromFormat('Y-m-d', $date);
        $startDateDb = Carbon::createFromFormat('Y-m-d', $date);
        //echo $startDateDb;
        
        $dates[] = $startDate->format('l d.m.y'); 
        $datesdb[] = $startDateDb->format('Y-m-d'); 
        for ($i=0; $i<6; $i++){
            $dates[] = $startDate->addDays(1)->format('l d.m.y');
            $datesdb[] = $startDateDb->addDays(1)->format('Y-m-d');
        } 

        $res['dates'] = $dates;
        $res['datesdb'] = $datesdb;
        return $res;
    
    }
    public function new(){
        $files = scandir(public_path('images/tmp'));
        $files = array_diff(scandir(public_path('images/tmp')), array('..', '.'));
        foreach ($files as $file){
                unlink(public_path('images/tmp/').$file); 
        }

        return view('futter.new');
    } 

    public function showAll(){
        $futter = Futter::all();
        $ft = [];
        $futterToday = FutterPerDay::where('day',">=", date('Y-m-d'))->get();
        
        foreach ($futterToday as $fday){
            $ft[$fday->day] = [
                'name' => $fday->futter->name,
                'img'  => url('storage/futter/'.$fday->futter->img)
            ];
        }

        foreach ($futter as $i => $f){
            $ing = implode("<br>",$f->ingredients);
            unset($futter[$i]['ingredients']);
            $futter[$i]['ingredients']=$ing;
        }

        $dates   = $this->getDates()['dates'];
        $datesdb = $this->getDates()['datesdb'];

        return view('futter.all', compact('futter','ft','datesdb','dates'));
    }

    public function showDetails($id){
        $futter = Futter::find($id);
        return view('futter.detail', compact('futter'));
    }

    public function save(Request  $request){
        $image = $request->file('file');
        $data = $request->all();
        $pics = array_diff(scandir(public_path('images/tmp')), array('..', '.'));
        if (isset($image)){
            $imgName = $image->getClientOriginalName();
            //save img to tmp dir
            if (!is_dir(public_path('images/tmp'))){
                mkdir(public_path('images/tmp'), 0777, true);
            }
            $image->move(public_path('images/tmp'),$imgName);
        }
        
        else{
            if ($data['ingredients']!=""){
                $data['ingredients'] = explode("\r\n",$data['ingredients']);
            }
            else{
                $data['ingredients'] = [""];
            }
            //unset($data['_token']);
            //dump($data['ingredients']);
            $files = scandir(public_path('images/tmp'));
            $files = array_diff(scandir(public_path('images/tmp')), array('..', '.'));
            $pic_name = array_values($files)[0];
            $pic_name_to = array_values($files)[0];
            if (substr($pic_name,0,8)=='download'){
                $ext = substr($pic_name,8);
                $pic_name_to = $data['name'].$ext; 
            }
            File::move(public_path('images/tmp/').$pic_name, storage_path('app/public/futter/'.$pic_name_to));
            
            $data['img'] = $pic_name_to;
            $futter = new Futter();    
            $futter->fill($data);
            $futter->save();
            //empty tmp folder
            $files = array_diff(scandir(public_path('images/tmp')), array('..', '.'));
            foreach ($files as $file){
                unlink(public_path('images/tmp/').$file); 
            }
            return redirect()->route('futter.index');
        }

        
        //$imageName  = $image->getClientOriginalName();
        //$imageName = time().'.'.$image->extension();
        //
    }

    public function saveFutterPerDay(Request  $request){
        $req = $request->all();
        $fpd = $model = FutterPerDay::where('day', '=', $request['day'])->get(); 
        $futter = Futter::find($req['futter_id']);
        $fname = $futter->name;
        $ingredients = implode("\n", $futter->ingredients);
        if (count($fpd) > 0){
            $fpd = $fpd[0];
            $fpd->fill($req);
            $res = $fpd->update();
        }
        else{
            $fpd = new FutterPerDay();
            $fpd->fill($req);
            $res = $fpd->save();
        }
        $ntfy = new NtfyController();
        $msg = "Am ". date('d.m.Y',strtotime($request['day'])). " gibts jetzt " .$fname;
        $msg .= "\n\n".$ingredients;
        $o_msg = (object)$msg;
        $o_msg->priority = 1;
        $o_msg->topic="Neue Futter Nachticht!";
        $o_msg->tags="";
        $o_msg->description=$msg;
        $ntfy->sendMessage($o_msg);
    }

    public function update(Request $request){
        $req = $request->all();
        $futter = Futter::find($req['id']);
        if ($req['ingredients']!=""){
            $req['ingredients'] = explode("\r\n",$req['ingredients']);
        }
        else {
            $req['ingredients'] = [""];
        }    
        $futter->fill($req);
        $res = $futter->update();
        if ($res){
            return redirect()->back()->with('success', 'Successfully Saved.');
        }
        else{
            return redirect()->back()->with('error', 'Error occured!'); 
        }    
    }
}
