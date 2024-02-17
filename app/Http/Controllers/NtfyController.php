<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyDates;
use Carbon\Carbon;
use Response;

class NtfyController extends Controller
{
    private $endpoint = "http://noppal.de:81/";
    private $channel = "ntfy";
    public function index($msg){
        //$this->sendMessage($msg);
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
       $notifications = MyDates::orderBy('reminder', 'DESC')->get();
       /* foreach ($notifications as $i =>  $notification){
            //$reminder = $notification->reminder!="0000-00-00 00:00:00" ? Carbon::parse($notification->reminder)->format('d.m.Y H:i') : "-";
            //$date = $notification->date!="0000-00-00 00:00:00" ? Carbon::parse($notification->date)->format('d.m.Y H:i') : "-";
            $notifications[$i]->reminder = $notification->reminder;
            $notifications[$i]->date = $notification->date;
       } */
       return view('ntfy.show', compact('notifications'));
   }

   public function editNotification($id){
        $myNotification = MyDates::find($id);
        $html = view('components.ntfy.notification', ['notification' => $myNotification, 'mode' => 'edit']);
        echo $html;
   }

   public function show($id){
        $myNotification = MyDates::find($id);
        $html = view('components.ntfy.notification', ['notification' => $myNotification, 'mode' => 'show']);
        echo $html;
   }

   public function new(){
        $myNotification = new MyDates();
        $myNotification->date = gmdate('Y-m-d H:i:s');
        $myNotification->reminder = gmdate('Y-m-d H:i:s');
        $html = view('components.ntfy.notification', ['notification' => $myNotification, 'mode' => 'edit']);
        echo $html;
   }

    public function getDates($date=null){
        if ($date==null){
            $date=date('Y-m-d H:i:s');
        }
        $res = MyDates::where('date' ,"<=", $date)->get();
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
        if ($res){
            $html = view('components.ntfy.notification', ['notification' => $notification, 'mode' => 'show']);
            echo $html;
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
