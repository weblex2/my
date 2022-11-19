<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class TwilioController extends Controller
{

    

    public function sendWhatsApp( $to, $body )
    {
        $sid    = env('TWILIO_AUTH_SID'); 
        $token  = env('TWILIO_AUTH_TOKEN'); 
        $twilio = new Client($sid, $token); 
 
        $message = $twilio->messages 
                  ->create("whatsapp:" .$to, // to 
                        array( 
                               "from" => "whatsapp:+14155238886",       
                               "body" => $body 
                        ) 
                  ); 
 
        print($message->sid);
    }
}
