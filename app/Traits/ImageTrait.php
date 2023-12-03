<?php

namespace App\Traits;

use Illuminate\Http\Request;
use File;
use Image;

use function PHPUnit\Framework\fileExists;

trait ImageTrait {

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

        $imgSmall  = $newDir.'/'.$filename."_s.".$extension;
        $imgMedium = $newDir.'/'.$filename."_m.".$extension;
        $imgLarge  = $newDir.'/'.$filename."_768.".$extension;
        $img = Image::make($file);

        // resize the image to a width of 768 and constrain aspect ratio (auto height)
        $img->orientate();
        $img->fit(768, null, function($constraint){
            $constraint->upsize();
            $constraint->aspectRatio();
        });
        $img->save($imgLarge);
        /* $img->resize(1024, 768, function ($constraint) {
            $constraint->aspectRatio();
        })->save($imgLarge); */
        $img->fit(640, null, function($constraint){
            $constraint->upsize();
            $constraint->aspectRatio();
        });
        $img->save($imgMedium);
        $img->fit(320, null, function($constraint){
            $constraint->upsize();
            $constraint->aspectRatio();
        });
        $img->save($imgSmall);
        return true;
    }

}    