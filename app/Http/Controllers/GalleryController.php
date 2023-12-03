<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\GalleryPics;
use App\Models\GalleryMappoint;
use App\Models\GalleryText;
use PhpParser\Node\Expr\AssignOp\Concat;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Validator;
use File;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\Session as FacadesSession;
use Session;


class GalleryController extends Controller
{
   
    
    use ImageTrait;
    var $reloadItems = 3;
    var $img_base_path =  "gallery/";
    var $lang; 


    public function index(){
        $lang =  session('lang');
        if (!$lang){
            session(['lang' => 'DE']);
        }
        //echo $lang = $lang;
        $galleries = Gallery::all();
        $galleries->load('GalleryMappoint');
        $mappoints = GalleryMappoint::all();
        return view('gallery.index', compact('galleries', 'mappoints'));
    }

    public function index2(){
        return view('gallery.index2');
    }

    public function setLang($route, $lang){
        session(['lang' => $lang]);
        return redirect()->route('gallery.index');
    }

    public function create(){
        return view('gallery.create');
    }

    public function edit(){
        $galleries = Gallery::select('*')->orderBy('code')->get();
        return view('gallery.edit', compact('galleries'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'country_map_name' => 'required',
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages());
            return redirect()->back();
        }

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

    public function delete(Request $request){
        $gallery = Gallery::find($request->id);
        $gallery->load('GalleryPics');
        $gallery->delete();
        $galleries = Gallery::select('*')->orderBy('code')->get();
        return view('gallery.edit', compact('galleries'));
    }

    public function showGallery($code, $offset=0){
        $gallery = Gallery::where('code', "=", $code)->get();
        $gal_id = $gallery[0]->id; 
        $limit  = 2;
        $gc = new GalleryPics();
        $pics = $gc->select("*")
        ->where('gallery_id', '=', $gal_id)
        ->offset(0)
        ->limit($this->reloadItems)
        ->get();
        $pics->load('GalleryText');
        return view('gallery.showGallery', compact('gallery','pics','offset'));
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

    
    public function upload($country_code, $map_point_id){
        
        //$country = Gallery
        $mappoints = GalleryMappoint::where('country_id', '=', $country_code)->orderBy('mappoint_name')->get();
        return view('gallery.upload', compact('country_code', 'map_point_id', 'mappoints'));
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
                    $gal_id = $this->getGalIdFromCode($request->country_code);
                    $gp  = new GalleryPics();
                    $gp->gallery_id = $gal_id;
                    $gp->pic = $path."/srcset/".$fileName."/".$fname."_768.".$extension;
                    //$gp->text = $request->content;
                    $gp->mappoint_id = $request->mappoint_id;
                    $res = $gp->save();  
                    $pic_id = $gp->id;
                    $galText  = new GalleryText();
                    $galText->pic_id = $pic_id;
                    $galText->gallery_id = $gal_id;
                    $galText->mappoint_id = $request->mappoint_id;
                    $galText->text =  $request->contentDE;
                    $galText->language =  'DE';
                    $galText->save();
                    $galText  = new GalleryText();
                    $galText->pic_id = $pic_id;
                    $galText->gallery_id = $gal_id;
                    $galText->mappoint_id = $request->mappoint_id;
                    $galText->text =  $request->contentES;
                    $galText->language =  'ES';
                    $galText->save();
                } 
            }
        }
        
        return back()
            ->with('success','File successfully uploaded.')
            ->with('file', $fileName);
    }

    public function deletePic(Request $request ){
        $pic_id = $request->id;
        $pic = GalleryPics::find($pic_id);
        $picTexts = GalleryText::where('pic_id', "=", $pic_id);
        $picTexts->delete();
        $pic->delete();
         return back()
        ->with('success','File successfully deleted.'); 
    }

    public function createGalleryMappoint(){
        $gallery = Gallery::select('*')->orderBy('code')->get();
        return view('gallery.createMapPoint', compact('gallery'));
    }

    public function editGalleryMappoints(){
        $mappoints = GalleryMappoint::select('*')->orderBy('country_id')->orderBy('mappoint_name')->get();
        return view('gallery.editMapPoints', compact('mappoints'));
    }
    
    public function storeGalleryMappoint(Request $request){
        $validator = Validator::make($request->all(), [
            'mappoint_name' => 'required',
            'lon' => 'required',
            'lat' => 'required',
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages());
            return redirect()->back()->withInput();
        }

        $req  = $request->all();
        $mapPoint  = new GalleryMappoint();
        $mapPoint->fill($req);
        $res = $mapPoint->save();
        if ($res){
            return back()->with('success','Map Point successfully created.');
        }
        else{
            return back()->with('error','Problem...');
        }    
    }

    public function deleteGalleryMappoint(Request $request){
        $gm = GalleryMappoint::find($request->id);
        $res = $gm->delete();
        if ($res){
            return back()->with('success','Map Point successfully deleted.');
        }
        else{
            return back()->with('error','Problem...');
        }  

    }

}
