<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\GalleryPics;
use App\Models\GalleryMappoint;
use App\Models\GalleryText;
use App\Models\GalleryConfig;
use App\Models\GalleryPicContent;
use PhpParser\Node\Expr\AssignOp\Concat;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Validator;
use Owenoj\LaravelGetId3\GetId3;

//use Illuminate\Contracts\Session\Session as Session;
use Illuminate\Support\Facades\Session as FacadesSession;
use Session;
use Route;
use DB;
use Response;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\MyClasses\BlogCreator;
use Illuminate\Database\Events\TransactionBeginning;

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
        session(['blog_current_gallery' => ""]);
        session(['blog_current_mappoint_id' => ""]);
        session(['used_mappoints' => [] ]); 
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

    public function setLang(Request $request){
        session(['lang' => $request->lang]);
        return true;
        //return redirect()->route('gallery.index');
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
            if ($mp){
                $mappoint_id = $mp->id;
            }    
        }
        if ($mappoint_id){
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
            $pics->load('GalleryPicContent');
            $pics->load('Mappoint');
            if (count($pics)<$this->reloadItems){
                $picCnt = $this->reloadItems-count($pics);
                $morePics = $this->getImagesFromNextMapPoint($picCnt, true);
                if ($morePics) {
                    $pics = $pics->merge($morePics);
                }    
            }
        }
        else{
            $pics = new GalleryPics();
        }    
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

    public function getImagesFromNextMapPoint($morePicsCnt, $picsOnly=false){
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
        if (count($pics)==0) {
            return false;
        }
        else{
            if ($picsOnly){
                return $pics;
            }
            $html="<div class='spacer'></div><div class='mappoint-header'>".$pics[0]->Mappoint->mappoint_name."</div>";
            foreach ($pics as $pic){
                $html.= view('components.gallery-item', ['pic' => $pic, 'content' => $pic->text]);
            }
            return $html;
        }    
    }

    public function editMappointPics($mappoint_id){
        $mp  = GalleryMappoint::find($mappoint_id);
        $mp->load('GalleryPics.Thumbnail');
        return view('gallery.editMappointPics', compact('mp'));
    }
    
    public function upload($country_code, $map_point_id){
        $mappoints = GalleryMappoint::where('country_id', '=', $country_code)->orderBy('mappoint_name')->get();
        return view('gallery.upload', compact('country_code', 'map_point_id', 'mappoints'));
    }

    public static function getPicOrder($mappoint_id){
        $ord = GalleryPics::where('mappoint_id' ,"=" ,$mappoint_id)->max('ord') + 1;
        $ord = isset($ord) ? $ord : 0;
        return $ord;
    }

    public function getBigPic($id){
        $pic = GalleryPics::find($id);
        $pic->load('PicXl');
        return Response::json([
            'data' => $pic->PicXl->filecontent,
            'status' => 'ok',
        ], 200);
    }

    public function storePic(Request $request){
        //$max_upload = 65536; 
        /* $request->validate([
            //'file' => 'required|max:'. $max_upload,
            'file' => 'required',
            'country_code' => 'required',
            'mappoint_id' => 'required',
        ]); */
        
        try{
            $bc = new BlogCreator($request);
            $bc->uploadFile();
            $bc->loadMedia();
            $bc->createThumbNails();
            $bc->createBlog();
        }
        catch(\Exception $e){
            dump($e);
            // Error returned
            return back()
                ->with('error',$e->getMessage());
        }
        
        // All good
        return back()
            ->with('success','File successfully uploaded.');
    }

    public static function getGalIdFromCode($code){
        $gal  = Gallery::where('code', "=", $code)->first();
        return $gal->id;
    }

    public function deletePic(Request $request ){
        $pic_id = $request->id;
        DB::beginTransaction();
        try{
            $pic = GalleryPics::find($pic_id);
            $picTexts = GalleryText::where('pic_id', "=", $pic_id);
            $picTexts->delete();
            $pic->delete();
            $picContent = GalleryPicContent::where('pic_id', "=", $pic_id);
            $picContent->delete();
        }
        catch(\Exception $e){
            DB::rollback();
            return back()->with('error',$e->getMessage()); 
        }    
        DB::commit();
        return back()->with('success','File successfully deleted.'); 
    }

    public function editPic($id){
        $pic  = GalleryPics::find($id);
        $pic->load('Gallery');
        $pic->load('GalleryText');
        $pic->load('Thumbnail');
        $pic->load('GalleryPicContent');
        $mappoints = GalleryMappoint::all();
        return view('gallery.editPic', compact('pic','mappoints'));
    } 

    public function updatePic(Request $request){
        DB::beginTransaction();
        $lang  = session('lang');
        $text = str_replace('<a ', '<a target="_blank" ',$request->content);
        try {
            $picText = GalleryText::where('pic_id',"=",$request->id)->where('language','=', session('lang'))->first();
            if (!$picText){
                $picText = new GalleryText();
                $picText->pic_id = $request->id;
                $picText->gallery_id = $this->getGalIdFromCode($request->country_code);
                $picText->language = session('lang');
            }
            $picText->text = $text;
            $res = $picText->save();
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error','Problem...'. $e->getMessage()); 
        }
        DB::commit();
        return back()->with('success','File successfully updated.');  
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

    public function config(){
        $config  = GalleryConfig::all();
        return view('gallery.config', compact('config'));
    }

    public function storeConfig(Request $request){
        $req = $request->all();
        unset($req['_token']);
        DB::beginTransaction();
        try{
            foreach($req as $key => $values){
                $conf = GalleryConfig::where('option', '=', $key)->first();
                if (!isset($conf)){
                    $conf = new GalleryConfig();
                    $conf->option = $key;
                }
                $conf->value = $values['value'];
                $conf->value2 = $values['value2'];
                $conf->save();
            }
        }
        catch(\Exception $e){
            DB::rollback();
            return back()->with('error', $e->getMessage()); 
        }    
        DB::commit();
        return back()->with('success','Configuration successfully stored.'); 
    }

    public function picTest(){
        
        
        $img = "img/IMG_6961.MOV";
        $track = new GetId3($img);

        dump($track->extractInfo());

        $img="img/IMG_6913.JPG";
        $track = new GetId3($img);

        dump($track->extractInfo());    

        die();
        $exif = exif_read_data($img);
        $latitude = $this->gps($exif["GPSLatitude"], $exif['GPSLatitudeRef']);
        $longitude = $this->gps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);
        echo $latitude . "," . $longitude;
    }

    public function gps($coordinate, $hemisphere) {
        if (is_string($coordinate)) {
            $coordinate = array_map("trim", explode(",", $coordinate));
        }
        for ($i = 0; $i < 3; $i++) {
            $part = explode('/', $coordinate[$i]);
            if (count($part) == 1) {
            $coordinate[$i] = $part[0];
            } else if (count($part) == 2) {
            $coordinate[$i] = floatval($part[0])/floatval($part[1]);
            } else {
            $coordinate[$i] = 0;
            }
        }
        list($degrees, $minutes, $seconds) = $coordinate;
        $sign = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;
        return $sign * ($degrees + $minutes/60 + $seconds/3600);
    }
    

    
}
