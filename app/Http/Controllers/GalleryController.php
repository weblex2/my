<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\GalleryPics;
use PhpParser\Node\Expr\AssignOp\Concat;

class GalleryController extends Controller
{

    var $reloadItems = 3;

    public function index(){
        $galleries = Gallery::all();
        return view('gallery.index', compact('galleries'));
    }

    public function showGallery($id, $offset=0){
        echo storage_path('gallery/test');
        $pics = Gallery::find($id);
        $limit  = 2;
        $gc = new GalleryPics();
        $pics = $gc->select("*")
        ->where('gallery_id', '=', 0)
        ->offset(0)
        ->limit($this->reloadItems)
        ->get();
        return view('gallery.showGallery', compact('pics','offset'));
    }

    public function showMore($offset=0){
        $offset = $offset*$this->reloadItems;
        $limit  = 2;
        $gc = new GalleryPics();
        $pics = $gc->select("*")
        ->where('gallery_id', '=', 0)
        ->offset($offset)
        ->limit($this->reloadItems)
        ->get();
        $pics->load('gallery');
        $html="";
        if (count($pics)>0){
            foreach ($pics as $pic){
                $html.= view('components.gallery-item', ['pic' => $pic->pic, 'content' => $pic->text]);
            }
        }
        else{
            $html="-1";
        }    
        return $html;
    }

    
    public function upload(){
        return view('gallery.upload');
    }

    public function store(Request $request){
        
        $path = "gallery/test";
        $request->validate([
            #'file' => 'required|max:2048',
            'file' => 'required',
        ]);

        $gp  = new GalleryPics();
        $gp->gallery_id = 0;
        $gp->pic = $request->file->getClientOriginalName();
        $gp->text = $request->content;
        $res = $gp->save();
        if ($res){
            $fileName = $request->file->getClientOriginalName();  
            $x =  storage_path($path);
            $res = $request->file->move(storage_path($path), $fileName);
        }    
        /*  
            Write Code Here for
            Store $fileName name in DATABASE from HERE 
        */
       
        return back()
            ->with('success','File successfully uploaded.')
            ->with('file', $fileName);
        dump($request);
    }
}
