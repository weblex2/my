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
        $rawResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Keine Antwort erhalten.';

        // Antwort formatieren
        $this->responseText = $this->formatResponse($rawResponse);

        $this->message = ""; // Textfeld leeren
        $this->loading = false; // Ladeanzeige deaktivieren
    }

    private function formatResponse($text)
    {
        // Beispiel für einfache Formatierungen, du kannst hier noch mehr hinzufügen
        // Absätze durch <p> ersetzen
        $formattedText = nl2br($text); // Zeilenumbrüche in <br> umwandeln

        // Weitere Formatierungen wie Fettdruck, Kursivschrift, etc. hinzufügen
        $formattedText = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $formattedText); // **Text** -> <strong>Text</strong>
        $formattedText = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $formattedText); // *Text* -> <em>Text</em>

        return $formattedText;
    }


    public function render()
    {
        return view('livewire.google-gemeni');
    }
}
