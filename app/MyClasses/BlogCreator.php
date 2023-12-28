<?php
namespace App\MyClasses;

use DB;
use File;
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
    private $status;
    private $filename;
    private $extention;
    private $imgPath;
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
        $this->tmpPath = Misc::getConfig('tmp_path')[0]->value;
        if (!is_dir($this->tmpPath)){
            File::makeDirectory($this->tmpPath, $mode = 0777, true, true);
        }
        $this->status = 'INIT';
    }

    
    public function loadImage($path, $image, $gallery_id, $mappoint_id, $content){
        $fullpath = $path."/".$image;
        $this->fullname  = $image;
        $this->gallery_id = $gallery_id; 
        $this->mappoint_id = $mappoint_id; 
        $this->content = $content;
        $this->filename = pathinfo($fullpath,  PATHINFO_FILENAME);
        $this->extention = pathinfo($fullpath, PATHINFO_EXTENSION);
        $this->imgPath = $path;
        if (!in_array(strtoupper($this->extention),['MOV']) ){  // Images
            $this->img = $this->manager->read($fullpath);
            $this->mimeType = $this->img->origin()->mediaType();
            $this->size = $this->img->size();
            $this->ratio = $this->size->aspectRatio();
        }
        else{   // Videos
            // move File to tmp folder
            rename($fullpath, $this->tmpPath."/".$this->fullname);
            $this->filename = $this->tmpPath."/".$this->fullname;
            $this->mimeType = mime_content_type($this->filename);
        } 
    }

    public function createThumbs(){
        
        $thumbsizes = Misc::getConfig('pic_size%', 'value', 'DESC');
        $thumbsizes = $thumbsizes->sortByDesc('value');
        
        foreach ($thumbsizes as $size){
            $type = explode('_', $size->option)[2];
            $name = $this->filename."_".$type.".".$this->extention;
            
            // Create squared image
            if ($size->value2!="") {
                $res = $this->img->resize($size->value, $size->value2)->save($this->tmpPath."/".$name);  
            }
            
            // Create scaled image
            else{
                $res = $this->img->scale(width: $size->value)->save($this->tmpPath."/".$name);
            }    
        }
        
        return true;
    }

    private function savePic(){
        $this->pic_id = Misc::getPicId();
        $pic = new GalleryPics;
        $pic->gallery_id = $this->gallery_id;
        $pic->mappoint_id = $this->mappoint_id;
        $pic->pic = $this->fullname;
        $pic->pic_id = 1;
        $pic->ord = Misc::getPicOrder($this->mappoint_id);
        $res = $pic->save();
        $this->pic_id = $pic->id;
        return $res;
        //$lon = $this->getGps($img->exif('GPS')["GPSLongitude"], $img->exif('GPS')['GPSLongitudeRef']);
        //$lat = $this->getGps($img->exif('GPS')["GPSLatitude"], $img->exif('GPS')['GPSLatitudeRef']);
        //var_dump($lat, $lon);
    }

    private function savePicText(){
        $galText  = new GalleryText();
        $galText->pic_id = $this->pic_id;
        $galText->gallery_id = $this->gallery_id;
        $galText->mappoint_id = $this->mappoint_id;
        $content = str_replace('<a ', '<a target="_blank" ',$this->content);
        $galText->text =  $content;
        $galText->language = session('lang');
        $galText->save();
    }

    private function savePicContent($files){
        foreach ($files as $file){
            $fileName = pathinfo($file)['filename'];
            $size = strtoupper(substr($fileName, strrpos( $fileName, '_' ) + 1 ));
            $pic  = new GalleryPicContent();
            $pic->pic_id = $this->pic_id;
            $pic->size = $size;
            $pic->filecontent = "data:".$this->mimeType.";base64,".base64_encode(file_get_contents($file));
            $res = $pic->save();        
        }
    }

    private function saveVideoContent(){
        $pic  = new GalleryPicContent();
        $pic->pic_id = $this->pic_id;
        $pic->size = 'MOV';
        $pic->filecontent = "data:".$this->mimeType.";base64,".base64_encode(file_get_contents($this->filename));
        $res = $pic->save();
    }

    public function saveThumbsToDb(){
        // Start transaction!
        DB::beginTransaction();
        try {
            $this->savePic();
            $this->savePicText();
            $tmpFiles = glob($this->tmpPath.'/'.$this->filename."*");
            $this->savePicContent($tmpFiles);
            
            // Cleanup temporary files
            foreach ($tmpFiles as $file){
                unlink($file);
            }    
        }
        catch(\Exception $e){
            DB::rollback();
	        return $e->getMessage();
        } 
        DB::commit();
        return true;
    }


    public function saveVideoToDb(){
        DB::beginTransaction();
        try{
            $this->savePic();
            $this->savePicText();
            $this->saveVideoContent();
            unlink($this->filename);
        }
        catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }
        DB::commit();
        return true;
    }

}

?>