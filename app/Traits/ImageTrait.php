<?php

namespace App\Traits;

use Illuminate\Http\Request;
use File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use function PHPUnit\Framework\fileExists;

trait ImageTrait {


    public function uploadFile($file, $destination){
        $successfullyUploaded = $file->move($destination, $file);
        return $successfullyUploaded; 
    }
    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function createImgSourceSet($path, $filename) {

        $newDir = $path.'/srcset/'.$filename;
        if(!File::isDirectory($newDir)){
            File::makeDirectory($newDir, 0777, true, true);
        }   
        $file  = $path."/".$filename;
        if (!fileExists($file)){
            return "file does not exist!"; 
        }
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $img_org = $newDir.'/'.$filename.".".$extension;
        $img_s   = $newDir.'/'.$filename."_s.".$extension;
        $img_m  = $newDir.'/'.$filename."_m.".$extension;
        $img_l   = $newDir.'/'.$filename."_l.".$extension;
        $img_xl  = $newDir.'/'.$filename."_xl.".$extension;
       
        try{
            $manager = new ImageManager(new Driver());
            $img = $manager->read($file);
        }
        catch(\Exception $e){
            dump($e);
        }
        // resize the image to a width of 768 and constrain aspect ratio (auto height)
        //$img->orientate();
        $img->scale(768)->save($img_l);
        $img->resize(4096, 800)->save($img_xl);
        return true;
    }

}    