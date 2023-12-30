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
use Owenoj\LaravelGetId3\GetId3;
use App\Http\Controllers\GalleryController;

class BlogCreator{
    
    var $manager;
    private $org;
    private $img;
    private $status;
    private $fileName;
    private $extention;
    private $imgPath;
    private $thumbs = [];
    private $tmpPath;
    private $mimeType;
    private $gallery_id;
    private $mappoint_id;
    private $pic_id;
    private $fullPathName;
    private $content;
    private $ratio;
    private $size;
    private $fullpath;
    private $originalName;
    private $tmpFileName;
    private $fileId;
    private $isVideo = false;
    private $request;
    private $tmpFolder;
    private $videos;

    function __construct($request) {

        $this->videos =['MOV','MP4'];

        $this->request = $request;
        // get the temp folder
        $this->tmpPath = Misc::getConfig('tmp_path')[0]->value;

        // Filename
        $this->fileName = $request->file->getClientOriginalName();

        // Extention
        $this->extention = pathinfo( $this->fileName, PATHINFO_EXTENSION);

        // set Video flag
        if (in_array(strtoupper($this->extention), $this->videos)){
            $this->isVideo = true;
        }

        // Set Full Path & Name
        $this->fullPathName  = $this->tmpPath."/".$this->fileName;
        $this->gallery_id = GalleryController::getGalIdFromCode($request->country_code); 
        $this->mappoint_id = $request->mappoint_id; 
        $this->content = $request->content;
        $this->status = 'INIT';
    }

    public function uploadFile(){
        $successfullyMoved = $this->request->file->move($this->tmpPath, $this->fileName);
        return $successfullyMoved;
    }
    
    public function loadMedia(){
        
        if ($this->isVideo ){   // Videos
            $this->mimeType = mime_content_type($this->fullPathName);
        }
        else{                   // Images
            $this->manager = new ImageManager(new Driver());
            $this->img = $this->manager->read($this->fullPathName);
            $this->mimeType = $this->img->origin()->mediaType();
            $this->size = $this->img->size();
            $this->ratio = $this->size->aspectRatio(); 
        } 
    }

    public function createThumbNails(){
        
        // create a temporary folder for the thumbnails
        $this->tmpFolder = $this->tmpPath."/".pathinfo( $this->fileName, PATHINFO_FILENAME);
        if (!file_exists($this->tmpFolder)) {
            File::makeDirectory($this->tmpFolder, 0777, true, true);
        }

        // Get file zizes from config
        $thumbsizes = Misc::getConfig('pic_size%', 'value', 'DESC');
        $thumbsizes = $thumbsizes->sortByDesc('value');

        if (!$this->isVideo){   // Image
            
            foreach ($thumbsizes as $size){
                $type = explode('_', $size->option)[2];
                $name = $type.".".$this->extention;
                
                // Create squared image
                if ($size->value2!="") {
                    $res = $this->img->resize($size->value, $size->value2)->save($this->tmpFolder."/".$name);  
                }
                
                // Create scaled image
                else{
                    $res = $this->img->scale(width: $size->value)->save($this->tmpFolder."/".$name);
                }    
            }
        } 
        else {   // Video 
            $this->createVideoThumbnail();
            foreach ($thumbsizes as $size){
            }        
        }    
        
        return true;
    }

    private function savePic(){
        $track = new GetId3($this->fullPathName);
        $meta = $track->extractInfo();
        $ext  = strtolower($this->extention); 
        $lon = null;
        $lat = null;

        if (isset($meta[$ext]['exif']['GPS']["GPSLongitude"]) && isset($meta[$ext]['exif']['GPS']["GPSLongitudeRef"])){
            $lon  = $this->gps($meta[$ext]['exif']['GPS']["GPSLongitude"], $meta[$ext]['exif']['GPS']["GPSLongitudeRef"]);
        }
        if (isset($meta[$ext]['exif']['GPS']["GPSLatitude"]) && isset($meta[$ext]['exif']['GPS']["GPSLatitudeRef"])){
            $lat  = $this->gps($meta[$ext]['exif']['GPS']["GPSLatitude"], $meta[$ext]['exif']['GPS']["GPSLatitudeRef"]);
        }
        
        $this->pic_id = Misc::getPicId();
        $pic = new GalleryPics;
        $pic->gallery_id = $this->gallery_id;
        $pic->mappoint_id = $this->mappoint_id;
        $pic->pic = $this->fileName;
        $pic->meta = json_encode($meta)!=null ? $meta : null;
        $pic->lon = $lon;
        $pic->lat = $lat;
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
        
            if (in_array($extention, $this->videos)){
                $size = 'MOV';
            }
            else{
                $size = $fileName;
            }    
            $mimeType = mime_content_type($file);
            $pic  = new GalleryPicContent();
            $pic->pic_id = $this->pic_id;
            $pic->size = strtoupper($size);
            $pic->filecontent = "data:".$mimeType.";base64,".base64_encode(file_get_contents($this->fullPathName));
            $res = $pic->save();        
        }
    }

    private function saveVideoContent(){
        $pic  = new GalleryPicContent();
        $pic->pic_id = $this->pic_id;
        $pic->size = 'MOV';
        $pic->filecontent = "data:".$this->mimeType.";base64,".base64_encode(file_get_contents($this->fullPathName));
        $res = $pic->save();
    }

    public function createBlog(){
        // Start transaction!
        DB::beginTransaction();
        try {
            if ($this->isVideo){
                $vtn = $this->createVideoThumbnails();
            }
            $this->savePic();
            $this->savePicText();
            $tmpFiles = glob($this->tmpFolder."/*");
            $this->savePicContent($tmpFiles);
        }
        catch(\Exception $e){
            DB::rollback();
            throw new \Exception($e->getMessage());
        } 
        DB::commit();
        $this->cleanup();
        return true;
    }

    public function createVideoThumbnails(){
        try{
        FFMpeg::fromDisk('gallery')
            ->open($this->fullPathName)
            ->getFrameFromSeconds(1)
            ->export()
            ->toDisk('gallery')
            ->save($this->tmpFolder."/videotn.JPG");
        } catch (\EncodingException $exception) {
            $command = $exception->getCommand();
            $errorLog = $exception->getErrorOutput();
        }    
    }


    public function cleanup(){
        // Cleanup temporary files
        $tmpFiles = glob($this->tmpFolder.'/*');
        foreach ($tmpFiles as $file){
            unlink($file);
        } 
        // remove folder
        File::deleteDirectory($this->tmpFolder);
        // remove original file
        unlink($this->fullPathName);
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

?>