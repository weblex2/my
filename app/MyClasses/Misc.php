<?php
namespace App\MyClasses;
use App\Models\GalleryConfig;
use App\Models\GalleryPics;

class Misc {

    public static function getConfig($conf, $order=null, $ascDesc='ASC'){
        if ($order!==null){
            $config  = GalleryConfig::where('option', 'like', $conf)->orderBy($order, $ascDesc)->get();
        }
        else{
            $config  = GalleryConfig::where('option', 'like', $conf)->get();
        }    
        return $config;
    }

    public static function getPicOrder($mappoint_id){
        $ord = GalleryPics::where('mappoint_id' ,"=" ,$mappoint_id)->max('ord') + 1;
        $ord = isset($ord) ? $ord : 0;
        return $ord;
    }

    public static function getPicId(){
        $ord = GalleryPics::all();
        $ord = isset($ord) ? $ord->count() + 1 : 1;
        return $ord;
    }

    public static function array_flatten($array) {
        $return = array();
        foreach ($array as $key => $value) {
            if (is_array($value)){
                $return = array_merge($return, self::array_flatten($value));
            } else {
                $return[$key] = $value;
            }
        }
        return $return;
    }
}