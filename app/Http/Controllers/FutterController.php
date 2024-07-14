<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Futter;
use File;


class FutterController extends Controller
{
    public function index(){
        $futter = Futter::inRandomOrder()->limit(3)->get();
        //$futter = Futter::all();
        return view('futter.index', compact('futter'));
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
        foreach ($futter as $i => $f){
            $ing = implode("<br>",$f->ingredients);
            unset($futter[$i]['ingredients']);
            $futter[$i]['ingredients']=$ing;
        }
        return view('futter.all', compact('futter'));
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
            $data['ingredients'] = json_encode(explode("\r\n",$data['ingredients']));
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
}
