<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyDates;
use App\Models\Emoticons;
use Carbon\Carbon;
use Response;

class NtfyController extends Controller
{
    private $endpoint = "http://noppal.de:81/";
    private $channel = "ntfy";
    private $emoticons =[];
    public function index($msg){
        //$this->sendMessage($msg);
    }

    public function __construct(){
        $emdb = Emoticons::all();
        foreach ($emdb as $emoji){
            $this->emoticons[$emoji->xname] = $emoji->xdec;
        }
    }

    public function sendMessage($msg){
        $endpoint = $this->endpoint.$this->channel;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $msg->description);

        $headers = array();
        $headers[] = 'Priority: '.$msg->priority;
        $headers[] = 'Title: '.$msg->topic;
        $headers[] = 'Tags: '.$msg->tags;
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;

        
    }

    

    public function test($text=null){
       $msg = MyDates::find(4);
       $res = $this->sendMessage($msg);
       dump($res);
    }

   public function showAll(){
       $emoticons = Emoticons::all(); 
       $notifications = MyDates::orderBy('reminder', 'DESC')->get();
       foreach ($notifications as $i =>  $notification){
            $emoticons  = explode(",",trim($notification->tags,","));
            $emoticons_icons = [];
            if ($emoticons){
                foreach ($emoticons as $j => $emoji){
                    if (isset($this->emoticons[$emoji])){
                    $emoticons_icons[$j]  = '<div class="ntfy-tag">' . $this->emoticons[$emoji] . '</div>';
                    }
                    else{
                    $emoticons_icons[$j] = '<div class="ntfy-tag">' . $emoji . '</div>';
                    }
                }
            }
            $notifications[$i]->tags2 = $emoticons_icons;
       } 
       return view('ntfy.show', compact('notifications','emoticons'));
   }

   public function editNotification($id){
        
        $emoticons2 = Emoticons::all(); 
        $notification = MyDates::find($id);

        $emoticons  = explode(",",trim($notification->tags,","));
        $emoticons_icons = [];
        if ($emoticons){
            foreach ($emoticons as $j => $emoji){
                if (isset($this->emoticons[$emoji])){
                    $emoticons_icons[$j]  = '<div class="ntfy-tag">' . $this->emoticons[$emoji] . '</div>';
                }
                else{
                    $emoticons_icons[$j] = '<div class="ntfy-tag">' . $emoji . '</div>';
                }
            }
        }
        $notification->tags2 = $emoticons_icons;

        $html = view('components.ntfy.notification', 
                    ['notification' => $notification, 'emoticons'=>$emoticons2, 'mode' => 'edit']);
        echo $html;
   }

   public function show($id){
        $notification = MyDates::find($id);
        $emoticons  = explode(",",trim($notification->tags,","));
        $emoticons_icons = [];
        if ($emoticons){
            foreach ($emoticons as $j => $emoji){
                if (isset($this->emoticons[$emoji])){
                    $emoticons_icons[$j]  = '<div class="ntfy-tag">' . $this->emoticons[$emoji] . '</div>';
                }
                else{
                    $emoticons_icons[$j] = '<div class="ntfy-tag">' . $emoji . '</div>';
                }
            }
        }
        $notification->tags2 = $emoticons_icons;
    
        $html = view('components.ntfy.notification', ['notification' => $notification, 'mode' => 'show']);
        echo $html;
   }

   public function new(){
        date_default_timezone_set('Europe/Berlin');
        $myNotification = new MyDates();
        $emoticons = Emoticons::all(); 
        $myNotification->date = date('Y-m-d H:i:s');
        $myNotification->reminder = date('Y-m-d H:i:s');
        $myNotification->tags2 = [];
        $html = view('components.ntfy.notification', 
                    ['notification' => $myNotification, 'emoticons'=> $emoticons, 'mode' => 'edit']);
        echo $html;
   }

   public function deleteNotification(Request $request){
        $id = $request->id;
        $notification = MyDates::find($id);
        $res = $notification->delete();
        if ($res){
            return Response::json([
                'status' => 'ok',
            ], 200);
        }
        else{
           return Response::json([
                'data' => $res,
                'status' => 'error',
            ], 500);
        }    
   }

   public function getDates($date=null){
        if ($date==null){
            $date=date('Y-m-d H:i:s');
        }
        $res = MyDates::where('reminder' ,"<=", $date)
                      ->where('reminder' ,"!=", '0000-00-00 00:00:00')
                      ->get();
        foreach ($res as $notify){
            if ($notify->recurring){
                if ($notify->recurring_interval=="D")
                    $new_notify = MyDates::find($notify->id);
                    $new_notify->date = date('Y-m-d',strtotime($notify->date) + 86400);
                    $new_notify->save();
            }
            $this->sendMessage($notify->topic);
        }
    }

    public function createNotification(){
        return view('ntfy.create');
    }

    public function save(Request $request){
        $id = $request->id;
        $req = $request->all();
        $req['recurring'] = $req['recurring_interval']!="" ? 1 : 0;
        if ($id){
            $notification = MyDates::find($id);
        }
        else{
            $notification = new MyDates();
        }        
        $notification->fill($req);
        $res = $notification->save();
        if (!$id) {
            $id = $notification->id;
        }
        if ($res){
            $this->show($id);
            #$emoticons = Emoticons::all(); 
            #$html = view('components.ntfy.notification', ['notification' => $notification, 'emoticons'=> $emoticons, 'mode' => 'show']);
            #echo $html;
        }
        else{
           return Response::json([
                'data' => $data,
                'status' => $httpcode,
            ], 200);
        }    
        
    }

    public function storeNotification(Request $request){
        $req = $request->all();
        $req['recurring'] = $req['recurring_interval']!="" ? 1 : 0;
        $mydate = new MyDates();
        $mydate->fill($req);
        $mydate->save();
        return view('ntfy.show');
        //return back()->with('success','Date successfully stored.'); 
    }

    public function sendNotifications(){
        date_default_timezone_set('Europe/Berlin');
        $now=date('Y-m-d H:i:s');
        $myDates = MyDates::where('reminder','<', date('Y-m-d H:i:s'))
                          ->where('reminder','!=','0000-00-00 00:00:00')
                          ->get();
        dump($myDates);                          
        foreach ($myDates as $notification){
            if ($notification->recurring == 1){
                switch ($notification->recurring_interval){
                    case "H":
                        $notification->reminder  = date('Y-m-d H:i:s',strtotime("+1 hour", strtotime($notification->reminder)));
                        break;
                    case "D":
                        $notification->reminder  = date('Y-m-d H:i:s',strtotime("+1 day", strtotime($notification->reminder)));
                        break;
                    case "M": 
                        $notification->reminder  = date('Y-m-d H:i:s',strtotime("+1 month", strtotime($notification->reminder)));
                        break;
                    case "Y": 
                        $notification->reminder  = date('Y-m-d H:i:s',strtotime("+1 year", strtotime($notification->reminder)));
                        break;
                    default: 
                        $notification->reminder   = "0000-00-00 00:00:00";
                        break;
                }
            }
            else{
                $notification->reminder   = "0000-00-00 00:00:00";
            }
            $this->sendMessage($notification);
            $res = $notification->update();
        }  
        
    }

}
