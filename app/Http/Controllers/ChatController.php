<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $message = $request->input('message');

        MessageSent::dispatch($message);
        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }

    public function sendMessage($message){
        MessageSent::dispatch($message);
        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }

    public function test()
    {
        for ($i=0; $i< 11; $i++){
            $message = "hallo Alex :". date('H:i:s');
            MessageSent::dispatch($message);
            sleep(1);
        }
        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }
}
