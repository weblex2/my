<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class reactTutorialController extends Controller
{
    public function index(){
        return view('reactTutorial.index');
    }

    public function counter(){
        return view('reactTutorial.counter');
    }

    public function cv(){
        //$file = file_get_contents('E:\web\my\resources\js\components\ReactTutorial\Cv.jsx');
        //return view('reactTutorial.cv',compact('file'));
        return view('reactTutorial.cv');
    }

    public function getMessage(){
        return "message from ". date("Y-m-d H:i:s");
    }

}
