<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class reactTutorialController extends Controller
{
    public function index(){
        return view('reactTutorial.index');
    }

    public function counter(){
        echo "counter";
        return view('reactTutorial.counter');
    }

    public function getMessage(){
        return "message from ". date("Y-m-d H:i:s");
    }

}
