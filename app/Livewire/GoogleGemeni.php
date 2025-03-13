<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
class GoogleGemeni extends Component
{

    public $message;
    public $responseText;
    public $loading = false;


    public function sendMessage()
    {
        $this->loading = true; // Ladeanzeige aktivieren
        $this->responseText = null; // Vorherige Antwort zurücksetzen

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'), [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $this->message]
                    ]
                ]
            ]
        ]);

        $data = $response->json();
        $this->responseText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Keine Antwort erhalten.';

        $this->message = ""; // Textfeld leeren
        $this->loading = false; // Ladeanzeige deaktivieren
    }


    public function render()
    {
        return view('livewire.google-gemeni');
    }
}
