<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Session;

class GoogleGemeni extends Component
{

    public $message;
    public $responseText;
    public $loading = false;

    public function __construct()
    {
        $this->message = "Hi Gemeni, erzähl mir bitte einen Flachwitz";
    }

    public function sendMessage()
    {
        $this->loading = true;
        $this->responseText = null;

        // 1. Chat-ID aus der Session abrufen oder neu erstellen
        $chatId = session('current_chat_id');
        if (!$chatId) {
            $chatId = Str::uuid()->toString();
            session(['current_chat_id' => $chatId]);
            session(['conversation_history_' . $chatId => []]); // Initialisiere den Verlauf für diese Chat-ID
        }

        // 2. Gesprächsverlauf aus der Session abrufen
        $conversationHistory = session('conversation_history_' . $chatId, []);

        // 3. Aktuelle Benutzernachricht erstellen (mit Rolle 'user')
        $userMessage = [
            'role' => 'user',
            'parts' => [
                ['text' => $this->message]
            ]
        ];

        // 4. API-Aufruf an Gemini
        $payload = [
            'contents' => [$userMessage], // Starte mit der aktuellen Benutzernachricht
        ];

        // Wenn es einen vorherigen Verlauf gibt, füge ihn hinzu
        if (!empty($conversationHistory)) {
            $payload['contents'] = $conversationHistory;
            $payload['contents'][] = $userMessage; // Füge die aktuelle Nachricht zum bestehenden Verlauf hinzu
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'), $payload);

        $data = $response->json();
        $rawResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Keine Antwort erhalten.';

        // 5. Antwort formatieren (für die Anzeige an den Benutzer)
        $formattedResponse = $this->formatResponse($rawResponse);
        $this->responseText = $formattedResponse;

        // 6. Antwort des Modells zum Gesprächsverlauf in der Session hinzufügen
        $conversationHistory[] = [
            'role' => 'model',
            'parts' => [
                ['text' => $rawResponse]
            ]
        ];
        session(['conversation_history_' . $chatId => $conversationHistory]);

        $this->message = "";
        $this->loading = false;
    }


    protected function getConversationHistory(string $chatId): array
    {
        // Implementiere deine Logik zum Abrufen des Verlaufs
        return session('chat_history_' . $chatId, []); // Beispiel mit Session
    }

    protected function saveConversationHistory(string $chatId, array $history): void
    {
        // Implementiere deine Logik zum Speichern des Verlaufs
        session(['chat_history_' . $chatId => $history]); // Beispiel mit Session
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
