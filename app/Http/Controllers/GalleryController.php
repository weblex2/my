<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\GalleryPics;
use PhpParser\Node\Expr\AssignOp\Concat;

class GalleryController extends Controller
{

    var $reloadItems = 3;

    public function index(){
        $galleries = Gallery::all();
        return view('gallery.index', compact('galleries'));
    }

    public function showGallery($id, $offset=0){
        $pics = Gallery::find($id);
        $limit  = 2;
        $gc = new GalleryPics();
        $pics = $gc->select("*")
        ->offset(0)
        ->limit($this->reloadItems)
        ->get();
        return view('gallery.showGallery', compact('pics','offset'));
    }

    public function showMore($offset=0){
        $offset = $offset*$this->reloadItems;
        $limit  = 2;
        $gc = new GalleryPics();
        $pics = $gc->select("*")
        ->offset($offset)
        ->limit($this->reloadItems)
        ->get();
        $pics->load('gallery');
        $html="";
        if (count($pics)>0){
            foreach ($pics as $pic){
                $html.= view('components.gallery-item', ['pic' => $pic->pic, 'content' => $pic->text]);
            }
        }
        else{
            $html="-1";
        }    
        return $html;
    }

    public function test(){
        return view('components.gallery-item', ['pic' => '1.JPG', 'content' => 'Tah is a test']);
    }
}
