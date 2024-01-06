<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NtfyController extends Controller
{
    public function index(){
        
        $endpoint = "https://f180-88-70-254-60.ngrok-free.app/noppal";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "Work Harder");

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
}
