<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\GalleryPics;
use PhpParser\Node\Expr\AssignOp\Concat;
use App\Traits\ImageTrait;
use File;

class GalleryController extends Controller
{
   
    
    use ImageTrait;
    var $reloadItems = 3;
    var $img_base_path =  "gallery/";

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
                $html.= view('components.gallery-item', ['pic' => $pic, 'content' => $pic->text]);
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
        
        $path = $this->img_base_path.$request->country_code;
        if (!file_exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }    
        $request->validate([
            #'file' => 'required|max:2048',
            'file' => 'required',
            'country_code' => 'required',
        ]);
        
        
        $fileName = $request->file->getClientOriginalName();  
        $successfullyMoved = $request->file->move($path, $fileName);
        if ($successfullyMoved){
                // Create smaller pics
                // Trait
            $file  = $path."/".$fileName;
            $fname = strtoupper(pathinfo($file, PATHINFO_FILENAME));
            $extension = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension,['JPG', 'JPEG'])){
                $thumpsCreatedSuccsess =  $this->createImgSourceSet($path, $fileName);
                if ($thumpsCreatedSuccsess){
                    // Save to DB
                    $gp  = new GalleryPics();
                    $gp->gallery_id = $this->getGalIdFromCode($request->country_code);
                    $gp->pic = $path."/srcset/".$fileName."/".$fname."_768.".$extension;
                    $gp->text = $request->content;
                    $res = $gp->save();    
                } 
            }
        }
        
        return back()
            ->with('success','File successfully uploaded.')
            ->with('file', $fileName);
    }

    public function deletePic(Request $request ){
        $id = $request->item_id;
        $galPics = GalleryPics::find($id);
        $galPics->delete();
        return 'Jupp';
        /* return back()
        ->with('success','File successfully deleted.'); */
    }
}
