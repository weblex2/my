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
use DB;
use Response;


class GalleryController extends Controller
{
   
    
    use ImageTrait;
    var $reloadItems = 3;
    var $img_base_path =  "gallery/";
    var $lang; 
    var $mappoint_name;
    var $alterviveGalleries;

    public function index(){
        $lang =  session('lang');
        if (!$lang){
            session(['lang' => 'DE']);
        }
        $galleries = Gallery::orderBy('name')->get();
        $galleries->load('GalleryMappoint');
        $mappoints = GalleryMappoint::all();
        $mappoints->load('GalleryPics');
        $mappoints->loadCount('GalleryPics');
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

    public function showGallery($code, $mappoint_id=-1, $offset=0){
        $gallery = Gallery::where('code', "=", $code)->get();
        $gallery_id = $gallery[0]->id; 

        if ($mappoint_id==-1){
            $mp  = GalleryMappoint::where('gallery_id','=',$gallery_id)->orderBy('ord')->first();
            $mappoint_id = $mp->id;
        }

        $mp = GalleryMappoint::find($mappoint_id);
        $used_mappoints = [0 => $mappoint_id];
        session(['blog_current_gallery' => $gallery_id]);
        session(['blog_current_mappoint_id' => $mappoint_id]);
        session(['used_mappoints' => $used_mappoints]); 
        $gc = new GalleryPics();
        $pics = $gc->select("*")
        ->where('gallery_id', '=', $gallery_id)
        ->where('mappoint_id', '=', $mappoint_id)
        ->offset(0)
        ->limit($this->reloadItems)
        ->orderBy('ord')
        ->get();
        $pics->load('GalleryText');
        $pics->load('Mappoint');
        return view('gallery.showGallery', compact('gallery','pics','offset','mp'));
    }

    public function showMore($offset=0){
        $gallery_end = false;
        $mappoint_id = session('blog_current_mappoint_id');
        $offset = $offset*$this->reloadItems;
        $gc = new GalleryPics();
        $pics = $gc->select("*")
        ->where('mappoint_id', '=', $mappoint_id)
        ->offset($offset)
        ->orderBy('ord')
        ->limit($this->reloadItems)
        ->get();
        $pics->load('gallery');
        $pics->load('Mappoint');
        $html="";
        $alt_blogs = "";
        $mp_end=false;
        if (count($pics)== $this->reloadItems){
            foreach ($pics as $pic){
                $html.= view('components.gallery-item', ['pic' => $pic, 'content' => $pic->text]);
            }
        }
        else{
            foreach ($pics as $pic){
                $html.= view('components.gallery-item', ['pic' => $pic, 'content' => $pic->text]);
            }
            $alternatives = [];
            $current_gallery[0] = session('blog_current_gallery');
            $morePicsCnt = $this->reloadItems - count($pics);
            $moreHtml = $this->getImagesFromNextMapPoint($morePicsCnt); 
            if (!$moreHtml){
                $gallery_end = true;
                $alt_blogs = "";
                $alternatives = Gallery::whereNotIn('id', $current_gallery)->orderBy('id')->limit(6)->get();
                $alt_blogs.= view('components.gallery.show-alternative-blog', ['alternativeBlogs' => $alternatives]);
                $html.=$alt_blogs;
            }
            else{
                $html .= $moreHtml;
            }
        }
        return Response::json([
            'html' => $html,
            'offset' => $offset,
            'mp_end' => $mp_end,
            'mp_name' => $this->mappoint_name,
            'gallery_end' => $gallery_end,
            'alternatives' => $alt_blogs,
            'current_mappoint' => session('blog_current_mappoint_id'),
        ], 201);
    }

    public function getImagesFromNextMapPoint($morePicsCnt){
        $gallery_id = session('blog_current_gallery');
        $used_mappoints = session('used_mappoints');
        $nextMapPoint = GalleryMappoint::where('gallery_id', "=", $gallery_id) 
                        ->whereNotIn('id', $used_mappoints)->orderBy('ord')->first();
        if (!$nextMapPoint){
            return false;
        }                
        $mappoint_id = $nextMapPoint->id;
        $used_mappoints[] = $mappoint_id;  
        $this->mappoint_name = $nextMapPoint->mappoint_name;              
        session(['blog_current_mappoint_id' => $mappoint_id]);
        session(['used_mappoints' => $used_mappoints]);    
        $gc = new GalleryPics();
        $pics = $gc->select("*")
            ->where('mappoint_id', '=', $mappoint_id)
            ->limit($morePicsCnt)
            ->get();
        $pics->load('gallery');
        $html="<div class='spacer'></div><div class='mappoint-header'>".$pics[0]->Mappoint->mappoint_name."</div>";
        foreach ($pics as $pic){
            $html.= view('components.gallery-item', ['pic' => $pic, 'content' => $pic->text]);
        }
        return $html;
    }

    public function editMappointPics($mappoint_id){
        $mp  = GalleryMappoint::find($mappoint_id);
        $mp->load('GalleryPics');
        return view('gallery.editMappointPics', compact('mp'));
    }
    
    public function upload($country_code, $map_point_id){
        $mappoints = GalleryMappoint::where('country_id', '=', $country_code)->orderBy('mappoint_name')->get();
        return view('gallery.upload', compact('country_code', 'map_point_id', 'mappoints'));
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
            'contentDE' => 'required',
            'mappoint_id' => 'required',
        ]);
        
        $fileName = $request->file->getClientOriginalName(); 
        //$successfullyMoved = $this->uploadFile($request->file, $path);
        $fileName = $request->file->getClientOriginalName();  
        $successfullyMoved = $request->file->move($path, $fileName);
        if ($successfullyMoved){
            $file  = $path."/".$fileName;
            $fname = strtoupper(pathinfo($file, PATHINFO_FILENAME));
            $extension = strtoupper(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array(strtoupper($extension),['MOV', ''])){
                // Trait / Create smaller pics
                $thumpsCreatedSuccsess =  $this->createImgSourceSet($path, $fileName);
                if ($thumpsCreatedSuccsess){
                    // Save to DB
                    $gal_id = $this->getGalIdFromCode($request->country_code);
                    $gp  = new GalleryPics();
                    $ord = GalleryPics::where('mappoint_id' ,"=" ,$request->mappoint_id)->max('ord') + 1;
                    $ord = isset($ord) ? $ord : 0;
                    $gp->gallery_id = $gal_id;
                    $gp->ord= $ord;
                    $gp->pic = $path."/srcset/".$fileName."/".$fname."_l.".$extension;
                    $gp->mappoint_id = $request->mappoint_id;

                    $res = $gp->save();  
                    $pic_id = $gp->id;
                    $galText  = new GalleryText();
                    $galText->pic_id = $pic_id;
                    $galText->gallery_id = $gal_id;
                    $galText->mappoint_id = $request->mappoint_id;
                    $text = str_replace('<a ', '<a target="_blank" ',$request->contentDE);
                    $galText->text =  $text;
                    $galText->language =  'DE';
                    $galText->save();
                    $galText  = new GalleryText();
                    $galText->pic_id = $pic_id;
                    $galText->gallery_id = $gal_id;
                    $galText->mappoint_id = $request->mappoint_id;
                    $galText->text =  $request->contentES;
                    $galText->language =  'ES';
                    $res = $galText->save();
                } 
            }
            else{
                $res = false;
            }

        }
        
        if ($res){
            return back()
            ->with('success','File successfully uploaded.')
            ->with('file', $fileName);
        }
        else{
            return back()
            ->with('error','Videos are not supported.')
            ->with('file', $fileName);
        }
        
    }

    public function getGalIdFromCode($code){
        $gal  = Gallery::where('code', "=", $code)->first();
        return $gal->id;
    }

    public function deletePic(Request $request ){
        $pic_id = $request->id;
        $pic = GalleryPics::find($pic_id);
        $picTexts = GalleryText::where('pic_id', "=", $pic_id);
        $picTexts->delete();
        $pic->delete();
        return back()->with('success','File successfully deleted.'); 
    }

    public function editPic($id){
        $pic  = GalleryPics::find($id);
        $pic->load('Gallery');
        $pic->load('GalleryTextAll');
        $mappoints = GalleryMappoint::all();
        return view('gallery.editPic', compact('pic','mappoints'));
    } 

    public function updatePic(Request $request){
        $error = 0;
        $gt = GalleryText::where('pic_id',"=",$request->id)->get();
        $pic = GalleryPics::find($request->id);
        //$pic->fill($request->all());
        //dump($request);
        DB::beginTransaction();
        try {
            foreach($gt as $i =>  $gtl){
                if ($gtl->language=="DE"){
                    $text = str_replace('<a ', '<a target="_blank" ',$request->contentDE);
                    $gt[$i]->text = $text;
                }
                if ($gtl->language=="ES"){
                    $gt[$i]->text = $request->contentES;
                }
                $res = $gt[$i]->save();
                if (!$res){
                    $error++;
                }
            }
        }
        catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        
        if ($error == 0){
            return back()->with('success','File successfully updated.'); 
        }
        else{
             return back()->with('error','Problem...'); 
        }    
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
        $gallery_id = $this->getGalIdFromCode($request->country_id);
        $request->gallery_id = $gallery_id;
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
       
        $mp = GalleryMappoint::where('gallery_id','=',$gallery_id)->get();
        if (count($mp)==0){
            $ord=1;
        }
        else{
            $ord=count($mp)+1;
        }
        $req['gallery_id'] = $gallery_id;
        $req['ord'] = $ord;
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

    public function updatePicOrder(Request $request){
        $data  = $request->all();
        $data  = json_decode($data['data'], true);
        $err=0;
        foreach ($data as $i => $row){
            $pic = GalleryPics::find($row['id']);
            $pic->ord = $row['ord'];
            $res = $pic->save();
            if (!$res){
                return Response::json([
                    'data' => print_r($data,1),
                    'status' => 'error',
                ], 400);
            }
        }
        return Response::json([
            'data' => print_r($data,1),
            'status' => 'success',
        ], 200);
    }

    public function picTest(){
        $img="IMG_6913.JPG";
        //$img="noppal.jpg";
        $path = "img";
        $this->createImgSourceSet($path, $img);
        //$data  = file_get_contents($path."/".$img);
        //dump(base64_encode($data));
    }

}
