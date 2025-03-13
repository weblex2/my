<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Http;

class GoogleGemeniController extends Controller
{
    public function index(){
        return view('gemeni.index');
    }


    public function queryGemeni(Request $request){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'), [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $request->question]
                    ]
                ]
            ]
        ]);

        return response()->json($response->json());

    }
}
