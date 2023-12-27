<?php
namespace App\MyClasses;

use DB;
use App\MyClasses\Misc as Misc;
use App\Models\GalleryPics;
use App\Models\GalleryText;
use App\Models\GalleryPicContent;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BlogCreator{
    
    var $manager;
    private $org;
    private $img;
    private $misc;
    private $status;
    private $filename;
    private $extention;
    private $imgPath;
    private $text;
    private $thumbs = [];
    private $tmpPath;
    private $mimeType;
    private $gallery_id;
    private $mappoint_id;
    private $pic_id;
    private $fullname;
    private $content;
    private $ratio;
    private $size;

    function __construct() {
        $this->manager = new ImageManager(new Driver());
        $this->misc = new Misc();
        $this->tmpPath = Misc::getConfig('tmp_path')[0]->value;
        $this->status = 'INIT';
    }

    public function loadImage($path, $image, $gallery_id, $mappoint_id, $content){
        $fullpath = $path."/".$image;
        $this->fullname  = $image;
        $this->img = $this->manager->read($fullpath);
        $this->org = $this->img;
        $this->filename = pathinfo($fullpath,  PATHINFO_FILENAME);
        $this->extention = pathinfo($fullpath, PATHINFO_EXTENSION);
        $this->imgPath = $path;
        $this->mimeType = $this->img->origin()->mediaType();
        $this->size = $this->img->size();
        $this->ratio = $this->size->aspectRatio();
        $this->gallery_id = $gallery_id; 
        $this->mappoint_id = $mappoint_id; 
        $this->content = $content;
    }

    public function createThumbs(){
        
        $thumbsizes = $this->misc->getConfig('pic_size%', 'value', 'DESC');
        $thumbsizes = $thumbsizes->sortByDesc('value');
        
        foreach ($thumbsizes as $size){
            $type = explode('_', $size->option)[2];
            $name = $this->filename."_".$type.".".$this->extention;
            
            // Create squared image
            if ($size->value2!="") {
                $res = $this->img->resize($size->value, $size->value2)->save('img/tmp/'.$name);  
            }
            
            // Create scaled width image
            else{
                $res = $this->img->scale(width: $size->value)->save('img/tmp/'.$name);
            }    
        }
        return true;
    }

    public function saveThumbsToDb(){
        // Start transaction!
        DB::beginTransaction();
        try {
            
            $this->pic_id = Misc::getPicId();
            
            $pic = new GalleryPics;
            $pic->gallery_id = $this->gallery_id;
            $pic->mappoint_id = $this->mappoint_id;
            $pic->pic = $this->fullname;
            $pic->pic_id = 1;
            $pic->ord = Misc::getPicOrder($this->mappoint_id);
            $res = $pic->save();
            $pic_id = $pic->id;

            // Save Text in language
            $galText  = new GalleryText();
            $galText->pic_id = $pic_id;
            $galText->gallery_id = $this->gallery_id;
            $galText->mappoint_id = $this->mappoint_id;
            $content = str_replace('<a ', '<a target="_blank" ',$this->content);
            $galText->text =  $content;
            $galText->language = session('lang');
            $galText->save();

            $files = glob($this->tmpPath.'/'.$this->filename."*");
            foreach ($files as $file){
                $fileName = pathinfo($file)['filename'];
                $size = strtoupper(substr($fileName, strrpos( $fileName, '_' ) + 1 ));
                $pic  = new GalleryPicContent();
                $pic->pic_id = $pic_id;
                $pic->size = $size;
                $pic->filecontent = "data:".$this->mimeType.";base64,".base64_encode(file_get_contents($file));
                $res = $pic->save();
            }
        }
        catch(\Exception $e){
            DB::rollback();
	        return $e;
        } 

        /* --------------------------------
                $galText  = new GalleryText();
                $galText->pic_id = $pic_id;
                $galText->gallery_id = $gallery_id;
                $galText->mappoint_id = $request->mappoint_id;
                $text = str_replace('<a ', '<a target="_blank" ',$request->content);
                $galText->text =  $text;
                $galText->language =  'DE'; 
        --- */

        DB::commit();

        return true;

        //$lon = $this->getGps($img->exif('GPS')["GPSLongitude"], $img->exif('GPS')['GPSLongitudeRef']);
        //$lat = $this->getGps($img->exif('GPS')["GPSLatitude"], $img->exif('GPS')['GPSLatitudeRef']);
        //var_dump($lat, $lon);
    }
}

?>