<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Redirect;
use Log;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $message = $request->input('message');
        $user_id = $request->input('user_id');
        $channel = $request->input('channel');
        $icon = 'img/unknown.jpg';
        switch ($user_id){
            case 25:
                $nick = 'Fritz';
                break;
            case 36:
                $nick = 'Herbert';
                break;
            case 0:
                $nick = 'System';
                break;
            default:
                $nick = 'No Nickname found!';
                break;
        }
        $message = $this->renderMessage($message, $user_id, $icon, $channel, $nick);

        try {
            MessageSent::dispatch($message, $channel);
        } catch (\Exception $e) {
            Log::error('Error dispatching event:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }

    public static function sendMessage($message){
        MessageSent::dispatch($message);
        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }

    public function test()
    {
        for ($i=0; $i< 11; $i++){
            $message = "hallo Alex :". date('H:i:s');
            MessageSent::dispatch($message, 'test');
            sleep(1);
        }
        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }

    public function renderMessage($msg, $user_id, $icon, $channel, $nick){
        return view("components.chat.message", [
            'message' => $msg,
            'user_id' => $user_id,
            'icon'    => $icon,
            'channel' => $channel,
            'nick'    => $nick,
        ])->render();
    }

    public function login(Request $request){
        $user_id = $request->userid;
        return Redirect::to('/message?userid='.$user_id);
    }

    public function showLogin(){
        return view('websocket.login');
    }
}
