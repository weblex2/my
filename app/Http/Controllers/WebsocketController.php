<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;

class WebsocketController extends Controller
{
    public function store(Request $request)
    {
        $message = $request->input('message');

        MessageSent::dispatch($message);
        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }

    public function message(){
        return view('websocket.index');
    }
}
