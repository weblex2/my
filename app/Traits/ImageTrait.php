<?php

namespace App\Traits;

use Illuminate\Http\Request;
use File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\GalleryPics;
use function PHPUnit\Framework\fileExists;

$thumbsDir = "";
trait ImageTrait {

    public function uploadFile($file, $destination){
        $successfullyUploaded = $file->move($destination, $file);
        return $successfullyUploaded; 
    }
    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function createImgSourceSet($request, $path, $filename) {
        $gal_id = $this->getGalIdFromCode($request->country_code);
        $newDir = $path.'/srcset/'.$filename;
        $this->thumbsDir = $newDir;
        if(!File::isDirectory($newDir)){
            File::makeDirectory($newDir, 0777, true, true);
        }   
        $file  = $path."/".$filename;
        if (!fileExists($file)){
            return "file does not exist!"; 
        }
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        //$img_org = $newDir.'/'.$filename.".".$extension;
        //$img_s   = $newDir.'/'.$filename."_s.".$extension;
        $img_m   = $newDir.'/'.$filename."_m.".$extension;
        $img_l   = $newDir.'/'.$filename."_l.".$extension;
        $img_xl  = $newDir.'/'.$filename."_xl.".$extension;
        $img_tn  = $newDir.'/'.$filename."_tn.".$extension;
        try{
            $manager = new ImageManager(new Driver());
            $img = $manager->read($file);
            $mime = $img->origin()->mediaType();
        }
        catch(\Exception $e){
            dump($e);
        }

        $res = $img->scale(2000)->save($img_xl);
        $res = $img->scale(1000)->save($img_l);
        $res = $img->scale(768)->save($img_m);
        $res = $img->resize(100,100)->save($img_tn);

        return true;
    }

    public function saveImagesToDB($folder, $gallery_id, $mappoint_id, $ord){
        $error=0;
        $this->pic_id = $this->getPicId();
        foreach (glob($folder."/*.*") as $file) {
            echo "$file - Größe: " . filesize($file) . "\n";
            $manager = new ImageManager(new Driver());
            $img = $manager->read($file);
            $mime = $img->origin()->mediaType();
            $pic  = new GalleryPics();
            $pic->pic = $file; #$path."/srcset/".$fileName."/".$fname."_l.".$extension;
            $pic->pic_id = $this->pic_id;
            $pic->gallery_id = $gallery_id;
            $pic->ord = $ord;
            $pic->mappoint_id = $mappoint_id;
            $pic->filecontent =  "data:".$mime.";base64,".base64_encode(file_get_contents($file));
            $res = $pic->save(); 
            if (!$res){
                $error++;
            }
        }
        if ($error==0){
            return true;
        }
        else{
            return false;
        }     
    }

    

    function getGps($exifCoord, $hemi) {
        $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0;
        $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;
        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
    }

    function gps2Num($coordPart) {
        $parts = explode('/', $coordPart);
        if (count($parts) <= 0)
            return 0;
        if (count($parts) == 1)
            return $parts[0];
        return floatval($parts[0]) / floatval($parts[1]);
    }

}    