<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Storage;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class ZimbraController extends Controller
{
    private $soapUrl;
    private $username;
    private $password;
    private $authToken;

    private $user_id;
    private $customerEmailIds;
    public  $result;

    public function __construct()
    {
        $this->user_id = Auth()->id();
        $this->customerEmailIds = Customer::getEmailToIdMap();
        $this->result = [];
    }

    public function importEmails(){

        $result = [
            'status' => 'success',
            'saved' => 0,
            'errors' => [],
            'attachements' => [],
            'documents_imported' => 0,
            'emails_imported' => 0,
        ];


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


        // Datum für gestern berechnen
        #$yesterday = Carbon::yesterday()->format('d-M-Y'); // Format: "16-Apr-2025"
        #$dayAfterYesterday = Carbon::yesterday()->addDay()->format('d-M-Y H:i:s'); // Für den Bereich

        $to = Carbon::now('UTC')->format('d-M-Y');
        // Datum vor 3 Tagen in UTC
        $from = Carbon::now('UTC')->subDays(3)->format('d-M-Y');


        // E-Mails von gestern abrufen
        $messages = $folder->query()
                        ->since($from)
                        //->before($to)
                        ->get();

        foreach ($messages as $message) {
            //echo $message->getSubject() . "<br>";
            $parsedEmail = $this->parseSingleEmail($message);
            $res = $this->saveEmail($parsedEmail);
            if ($res){
                if ($result['status']!="error"){
                    $result['status'] = "success";
                }
                $result['emails_imported'] = $result['emails_imported']+1;
                if ($res['attachements']!=[]){
                    $result['attachements'][] = $res['attachements'];
                    $result['documents_imported'] = $result['documents_imported']+ $res['attachements'][0]['saved'];
                }
            }
            //$parsedEmails[] = $parsedEmail;
        }

        return $result;
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
                        'content' => $attachment->getContent(),
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

    private function saveEmail($parsedEmail){
        $result = [
            'status' => 'success',
            'saved' => 0,
            'errors' => [],
            'attachements' => [],
        ];

        if (!$this->emailExists($parsedEmail['id'])){
            $contact = new Contact();
            $email = $parsedEmail['from'];
            $customer_id = $this->customerEmailIds[$email] ?? false;
            if (!$customer_id){
                return false;
            }
            // Benutzer-ID setzen (Fallback: aktueller Benutzer oder 1)
            $userId = $this->user_id ?? auth()->id() ?? 1;
            $contact->external_id   = $parsedEmail['id'];
            $contact->customer_id   = $customer_id;
            $contact->type          = 'email';
            $contact->from          = $parsedEmail['from'];
            $contact->to            = $parsedEmail['to'];
            $contact->contacted_at  = $parsedEmail['date'];
            $contact->subject       = $parsedEmail['subject'];
            $contact->details       = $parsedEmail['plain_text'];
            $success = $contact->save();
            if ($success){
                $result['status'] = "success";
                $result['saved'] = 1;
                $attResult =  $this->saveAttachement($parsedEmail, $customer_id , $userId);
                if (($attResult['status']=='success' && $attResult['saved']!= 0) || $attResult['status']!="success"){
                    $result['attachements'][]  = $attResult;
                }
            }
            else{
                $result['status'] = "error";
                $result['error'] = 1;
            }
            return $result;
        }
    }

    private function emailExists($external_id){
        $count =  Contact::where('external_id',$external_id)->count();
        return $count;
    }

    private function saveAttachement(array $parsedEmail, ?int $customerId = null, int $userId ): array
    {



        $result = [
            'status' => 'success',
            'saved' => 0,
            'errors' => [],
        ];

        try {
            // Überprüfe, ob die E-Mail gültig ist
            if (isset($parsedEmail['error']) || !isset($parsedEmail['attachments'])) {
                $result['status'] = 'error';
                $result['errors'][] = 'Ungültige E-Mail-Daten: ' . ($parsedEmail['error'] ?? 'Keine Anhänge vorhanden');
                return $result;
            }


            // Anhänge speichern
            foreach ($parsedEmail['attachments'] as $attachment) {
                try {
                    $res = Document::create([
                        'external_id' => $parsedEmail['id'], // Verknüpfung mit contacts.external_id
                        'customer_id' => $customerId,
                        'filename' => $attachment['filename'],
                        'size' => $attachment['size'],
                        'mime_type' => $attachment['mime_type'],
                        'content' => $attachment['content'],
                        'user' => $userId,
                    ]);
                    $result['saved']++;
                } catch (\Exception $e) {
                    $result['status'] = 'error';
                    $result['errors'][] = 'Fehler beim Speichern des Dokuments (' . $attachment['filename'] . '): ' . $e->getMessage();
                    Log::error('Fehler beim Speichern des Dokuments', [
                        'filename' => $attachment['filename'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            return $result;

        } catch (\Exception $e) {
            $result['status'] = 'error';
            $result['errors'][] = 'Allgemeiner Fehler beim Speichern der Dokumente: ' . $e->getMessage();
            Log::error('Allgemeiner Fehler beim Speichern der Dokumente', ['error' => $e->getMessage()]);
            return $result;
        }
    }

}
