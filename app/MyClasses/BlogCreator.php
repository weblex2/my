<?php
namespace App\MyClasses;

use DB;
use File;
use FFMpeg;
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
    private $fullpath;
    private $originalName;
    private $tmpFileName;
    private $fileId;
    private $isVideo = false;

    function __construct($data) {

        // get the temp folder
        $this->tmpPath = Misc::getConfig('tmp_path')[0]->value;

        // Image with full path
        $this->fullpath = $data['path']."/".$data['image'];

        // Filename
        $this->filename = pathinfo($this->fullpath,  PATHINFO_FILENAME);

        // Extention
        $this->extention = pathinfo($this->fullpath, PATHINFO_EXTENSION);

        // set Video flag
        if (in_array($this->extention, ['MOV'])){
            $this->isVideo = true;
        }

        // Save Original Name for later
        $this->originalName = $data['image'];
        
        // rename File 
        $this->fileId = uniqid();
        $this->tmpFileName = $this->tmpPath."/".$this->fileId.".".$this->extention;
        rename($this->fullpath, $this->tmpFileName);

        $this->fullname  = $data['image'];
        $this->gallery_id = $data['gallery_id']; 
        $this->mappoint_id = $data['mappoint_id']; 
        $this->content = $data['content'];
        $this->imgPath = $data['path'];
        $this->manager = new ImageManager(new Driver());
        
        /* if (!is_dir($this->tmpPath)){
            File::makeDirectory($this->tmpPath, $mode = 0777, true, true);
        } */
        $this->status = 'INIT';
    }

    
    public function loadImage(){
        
        if (!in_array(strtoupper($this->extention),['MOV']) ){  // Images
            $this->img = $this->manager->read($this->tmpFileName);
            $this->mimeType = $this->img->origin()->mediaType();
            $this->size = $this->img->size();
            $this->ratio = $this->size->aspectRatio();
        }
        else{   // Videos
           //$this->filename = $this->tmpPath."/".$this->fullname;
            $this->mimeType = mime_content_type($this->tmpFileName);
        } 
    }

    public function createThumbs(){
        
        $thumbsizes = Misc::getConfig('pic_size%', 'value', 'DESC');
        $thumbsizes = $thumbsizes->sortByDesc('value');
        
        foreach ($thumbsizes as $size){
            $type = explode('_', $size->option)[2];
            $name = $this->fileId."_".$type.".".$this->extention;
            
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
             $fileName = pathinfo($file,  PATHINFO_FILENAME);
             $extention = pathinfo($file, PATHINFO_EXTENSION);
        
            if (in_array($extention, ['MOV'])){
                $size = 'MOV';
            }
            else{
                $size = strtoupper(substr($fileName, strrpos( $fileName, '_' ) + 1 ));
            }    
            $mimeType = mime_content_type($file);
            $pic  = new GalleryPicContent();
            $pic->pic_id = $this->pic_id;
            $pic->size = $size;
            $pic->filecontent = "data:".$mimeType.";base64,".base64_encode(file_get_contents($file));
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
            if ($this->isVideo==false){
                //delete original file 
                unlink($this->tmpFileName);
            }
            // save the rest
            $tmpFiles = glob($this->tmpPath.'/'.$this->fileId."*");
            $this->savePicContent($tmpFiles);
        }
        catch(\Exception $e){
            DB::rollback();
	        return $e->getMessage();
        } 
        DB::commit();
        return true;
    }

    public function createThumbnail(){
        try{
        FFMpeg::fromDisk('gallery')
            ->open($this->tmpFileName)
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('gallery')
            ->save($this->tmpPath."/".$this->filename."_tn.JPG");
        } catch (\EncodingException $exception) {
            $command = $exception->getCommand();
            $errorLog = $exception->getErrorOutput();
            dump($command);
            dump($errorLog);
        }    
    }

    public function saveVideoToDb(){
        DB::beginTransaction();
        try{
            $this->savePic();
            $this->createThumbnail();
            $this->savePicText();
            $tmpFiles = glob($this->tmpPath.'/*');
            $this->savePicContent($tmpFiles);
        }
        catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }
        DB::commit();
        return true;
    }

    public function cleanup(){
        // Cleanup temporary files
        $tmpFiles = glob($this->tmpPath.'/*');
        foreach ($tmpFiles as $file){
            unlink($file);
        } 
    }

}

?>