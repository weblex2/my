<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use RecursiveArrayIterator;
use RecursiveRegexIterator;
use RegexIterator;
use File;

class ShowSourcecodeController extends Controller
{
    public function index(String $mypath=""){
        $path= base_path()."/resources/js/components/ReactTutorial";
        $files = $this->getTree($path);
        $this->formatFileStruct($files);
        dump($files);
        die();

        /* $directory = new \RecursiveDirectoryIterator($mypath);
        $iterator = new \RecursiveIteratorIterator($directory);
        $files = array();
        foreach ($iterator as $info) {
            if (!in_array($info->getFilename(), ['.','..'] )) {
                $files[] = $info->getPathname();;
            }
        } */

        /* $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($mypath), RecursiveIteratorIterator::SELF_FIRST);
        foreach($objects as $name => $object){
            echo "$name"."<br>";
        } */
        //dd($files);


        

        //return view('showSourceCode.index', compact('files'));
    }

    public function padFiles($item, $key){
        
        echo "$key holds $item<br>";
    }

    public function getTree($path)
        {
            $tree = [];
        
            $branch = [
            'dir' => basename($path)
            ];
        
            foreach (File::files($path) as $file) {
                $branch['file'][] = $file;
            }
        
            foreach (File::directories($path) as $directory) {
            $branch['file'][] = $this->getTree($directory);
            }
        
            $finalStruct =  array_merge($tree, $branch);
            return $finalStruct;
        }

    
    
    
    public function formatFileStruct($tree){
        array_walk_recursive($tree, 'self::padFiles');
    }    
}
