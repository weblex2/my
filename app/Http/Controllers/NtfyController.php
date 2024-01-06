<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyDates;

class NtfyController extends Controller
{
    private $endpoint = "https://bluebird-adapted-drake.ngrok-free.app/";
    private $channel = "noppal";
    public function index($msg){
        $this->sendMessage($msg);
    }

    public function sendMessage($msg){
        $endpoint = $this->endpoint.$this->channel;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        /*
        $client = new \GuzzleHttp\Client();

        $response = $client->post($endpoint, [
                \GuzzleHttp\RequestOptions::JSON => ['value' => "Work Harder!!"],
            ]);

        $statusCode = $response->getStatusCode();
        */
    }

    private function sendNotifications($date='0000-00-00'){
        if ($date==null) {
            $date = date('Y-m-d');
        }
    }

    public function getDates($date=null){
        if ($date==null){
            $date=date('Y-m-d');
        }
        $res = MyDates::where('date' ,">=", $date)->get();
        foreach ($res as $notify){
            $this->sendMessage($notify->topic);
        }
    }
}
