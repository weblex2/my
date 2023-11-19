<?php

namespace App\Traits;

use Illuminate\Http\Request;
use File;
use Image;

trait ImageTrait {

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function createImgSourceSet($path, $filename) {

        $newDir = $path.'\\srcset\\'.$filename;
        if(!File::isDirectory($newDir)){
            File::makeDirectory($newDir, 0777, true, true);
        }   
        $file  = $path."/".$filename;
        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $imgSmall  = $newDir.'/'.$filename."_s.".$extension;
        $imgMedium = $newDir.'/'.$filename."_m.".$extension;
        $imgLarge  = $newDir.'/'.$filename."_768.".$extension;
        $img = Image::make($file);

            // resize the image to a width of 768 and constrain aspect ratio (auto height)
            $img->resize(768, null, function ($constraint) {
                $constraint->aspectRatio();
            })->rotate(-90)->save($imgLarge);
            /* $img->resize(1024, 768, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imgLarge); */
            $img->resize(640, 480, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->rotate(-90);
            $img->save($imgMedium);
            $img->resize(320, 240, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imgSmall);
            return true;
    }

}    