<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Storage;

class ZimbraController extends Controller
{
    private $soapUrl;
    private $username;
    private $password;
    private $authToken;


    public function test(){
        $host = "mail.efm.de"; #/zimbra/#1
        $port = 993;
        $username = 'alex.noppenberger@assd.com';
        $password = 'Akwg44Frt6Cx';
        $ssl = "/ssl";
        // ClientManager initialisieren
        $cm = new ClientManager();

        // Client mit Konfiguration erstellen
        $client = $cm->make([
                'host' => $host,
                'port' => 993,
                'encryption' => 'ssl',
                'validate_cert' => true,
                'username' => $username,
                'password' => $password,
                'protocol' => 'imap'
            ]);

        $client->connect();
        $folder = $client->getFolder('INBOX');
        $folder = $client->getFolder('INBOX');

        // Datum für gestern berechnen
        #$yesterday = Carbon::yesterday()->format('d-M-Y'); // Format: "16-Apr-2025"
        #$dayAfterYesterday = Carbon::yesterday()->addDay()->format('d-M-Y H:i:s'); // Für den Bereich

        $from = '2025-04-16';
        $to   = '2025-04-17';

        $from =  Carbon::parse($from)->format('d-M-Y H:i:s');
        $to   =  Carbon::parse($to)->format('d-M-Y H:i:s');



        // E-Mails von gestern abrufen
        $messages = $folder->query()
                        ->since($from)
                        ->before($to)
                        ->get();

        foreach ($messages as $message) {
            echo $message->getSubject() . "<br>";
            $parsedEmails[] = $this->parseSingleEmail($message);
        }
        dump($parsedEmails);
    }

    /**
     * Einzelne E-Mail parsen
     * @param Message $message E-Mail-Objekt
     * @param string $attachmentStoragePath Pfad zum Speichern von Anhängen
     * @return array Geparsed E-Mail-Daten
     */
    private function parseSingleEmail($message): array
    {
        try {
            // Eindeutige ID (Message-ID oder UUID)
            $messageId = $message->getMessageId() ?? (string) Str::uuid();

            // Metadaten extrahieren
            $subject = $message->getSubject() ?? 'Kein Betreff';
            $from = $message->getFrom()[0]->mail ?? 'Unbekannter Absender';
            $to = collect($message->getTo())->pluck('mail')->implode(', ') ?? 'Unbekannter Empfänger';
            // Datum extrahieren und formatieren
            $rawDate = $message->getDate(); // String oder Attribute-Objekt
            $date = $rawDate ? Carbon::parse($rawDate)->format('Y-m-d H:i:s') : 'Unbekanntes Datum';

            // Textkörper extrahieren
            $plainText = $message->hasTextBody() ? $message->getTextBody() : null;
            $htmlText = $message->hasHTMLBody() ? $message->getHTMLBody() : null;

            // Anhänge verarbeiten
            $attachments = [];
            $attachmentStoragePath = 'attachments';

            if ($message->hasAttachments()) {
                if (!Storage::exists($attachmentStoragePath)) {
                    Storage::makeDirectory($attachmentStoragePath);
                }

                foreach ($message->getAttachments() as $attachment) {
                    $filename = $attachment->getName();
                    $uniqueFilename = time() . '_' . $filename;
                    $filePath = "$attachmentStoragePath/$uniqueFilename";

                    Storage::put($filePath, $attachment->getContent());

                    $attachments[] = [
                        'filename' => $filename,
                        'stored_path' => $filePath,
                        'size' => $attachment->getSize(),
                        'mime_type' => $attachment->getMimeType(),
                    ];
                }
            }

            return [
                'id' => $messageId,
                'subject' => $subject,
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'plain_text' => $plainText,
                'html_text' => $htmlText,
                'attachments' => $attachments,
            ];

        } catch (\Exception $e) {
            return [
                'id' => null,
                'error' => 'Fehler beim Parsen der E-Mail: ' . $e->getMessage(),
            ];
        }
    }

}
