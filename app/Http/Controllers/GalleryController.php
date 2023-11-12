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

    public function create(){
        return view('gallery.create');
    }

    public function store(Request $request){
        $gal  = new Gallery();
        $req = $request->all();
        unset($req['_token']);
        $gal->fill($req);
        $res = $gal->save();
        if ($res){
            $galleries = Gallery::all();
            return redirect()->route('gallery.index', compact('galleries'));
        }
        else{
            return back()->with('error','Problem...');
        }    
    }

    public function showGallery($code, $offset=0){
        $gal = Gallery::where('code', "=", $code)->get();
        $gal_id = $gal[0]->id; 
        $limit  = 2;
        $gc = new GalleryPics();
        $pics = $gc->select("*")
        ->where('gallery_id', '=', $gal_id)
        ->offset(0)
        ->limit($this->reloadItems)
        ->get();
        return view('gallery.showGallery', compact('gal_id','pics','offset'));
    }

    public function showMore($gallery_id, $offset=0){
        $offset = $offset*$this->reloadItems;
        $limit  = 2;
        $gc = new GalleryPics();
        $pics = $gc->select("*")
        ->where('gallery_id', '=', $gallery_id)
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

    
    public function upload($gal_id){
        return view('gallery.upload', compact('gal_id'));
    }

    public function getGalIdFromCode($code){
        $gal = Gallery::where('code','=', $code)->get();
        if (isset($gal[0]->id)){
            return $gal[0]->id;
        }
        else {
            return false;
        }    
    }

    public function storepic(Request $request){
        
        $path = "app/public/gallery/test";
        $request->validate([
            #'file' => 'required|max:2048',
            'file' => 'required',
            'country_code' => 'required',
        ]);
        $gp  = new GalleryPics();
        $gp->gallery_id = $this->getGalIdFromCode($request->country_code);
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

    public function deletePic(Request $request ){
        $id = $request->id;
        $galPics = GalleryPics::find($id);
        $galPics->delete();
        return back()
        ->with('success','File successfully deleted.');
    }
}
