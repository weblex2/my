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
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\MailManager;
use App\Mail\FilamentMail;
use Illuminate\Mail\Transport\SmtpTransport;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\TransportManager;
use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Mail\Message;
use Swift_SmtpTransport;
use Swift_Mailer;
use Illuminate\Support\Facades\Crypt;
use App\Filament\Helpers\FilamentHelper;



class ZimbraController extends Controller
{
    private $soapUrl;
    private $username;
    private $password;
    private $authToken;

    private $user_id;
    private $customerEmailIds;
    public  $result;
    private $folderName;

    public function __construct()
    {
        $this->user_id = Auth()->id();
        $this->customerEmailIds = Customer::getEmailToIdMap();
        $this->result = [];
    }

    public function importEmails()
    {
        $result = [
            'status' => 'success',
            'saved' => 0,
            'errors' => [],
            'attachements' => [],
            'documents_imported' => 0,
            'emails_imported' => 0,
        ];

        $user = Auth::user();
        #$host = "mail.efm.de";
        $host = FilamentHelper::readSetting('imap_server');
        #$port = 993;
        $port = FilamentHelper::readSetting('imap_port');
        $username = $user->email;
        #$password = 'Akwg44Frt6Cx';
        try{
            $password = decrypt($user->user01);
        }
        catch(\Exception $e){
            $result['status'] = "error";
            $result['errors'][] = "No valid IMAP Password found. You can set it via User Management.";
            return $result;
        }
        $cm = new ClientManager();

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


        // Datum
        $to = Carbon::now('UTC')->format('d-M-Y');
        $from = Carbon::now('UTC')->subDays(5)->format('d-M-Y');

        // 📥 INBOX & 📤 SENT Folder
        $folders = ['INBOX', 'Sent'];

        foreach ($folders as $folderName) {
            try {
                $folder = null;
                $folder = $client->getFolder($folderName);
                if ($folderName=="Sent"){
                    $this->folderName = "Sent";
                }
            } catch (\Exception $e) {
                $result['errors'][] = "Ordner '$folderName' konnte nicht geladen werden: " . $e->getMessage();
                continue;
            }

            $messages = $folder->query()
                ->since($from)
                ->get();

            foreach ($messages as $message) {
                $parsedEmail = $this->parseSingleEmail($message);
                $res = $this->saveEmail($parsedEmail);

                if ($res) {
                    if ($result['status'] != "error") {
                        $result['status'] = "success";
                    }

                    $result['emails_imported']++;

                    if (!empty($res['attachements'])) {
                        $result['attachements'][] = $res['attachements'];
                        $result['documents_imported'] += $res['attachements'][0]['saved'];
                    }
                }
            }
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
            //dd($message->getTo());
            $to = collect($message->getTo()->toArray() ?? [])
            ->map(fn($address) => $address->mail ?? null)
            ->filter()
            ->implode(', ') ?: 'Unbekannter Empfänger';

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
            $from = $parsedEmail['from'];
            $to = $parsedEmail['to'];
            $customer_id = $this->customerEmailIds[$from] ?? false;
            if (!$customer_id && $this->folderName!="Sent"){
                return false;
            }

            if (!$customer_id && $this->folderName="Sent"){
                $customer_id = $this->customerEmailIds[$to] ?? false;
            }

            if (!$customer_id){
                return false;
            }

            // Benutzer-ID setzen (Fallback: aktueller Benutzer oder 1)
            $userId = $this->user_id ?? auth()->id() ?? 1;
            $contact->external_id   = $parsedEmail['id'];
            $contact->customer_id   = $customer_id != false ? $customer_id : null;
            $contact->type          = 'email';
            $contact->from          = $parsedEmail['from'];
            $contact->to            = $parsedEmail['to'];
            $contact->contacted_at  = $parsedEmail['date'];
            $contact->subject       = $parsedEmail['subject'];
            $contact->details       = $parsedEmail['plain_text'];
            $success = $contact->save();
            if ($success){
                $contact_id = $contact->id;
                $result['status'] = "success";
                $result['saved'] = 1;
                $attResult =  $this->saveAttachement($parsedEmail, $contact_id, $customer_id , $userId);
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

    private function saveAttachement(array $parsedEmail, $contact_id, ?int $customerId = null, int $userId ): array
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
                        'contact_id' => $contact_id,
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

 public static function sendMail($mail)
{
    // Dynamische Config
    $config = [
        'transport' => 'smtp',
        'host' => 'mail.efm.de',
        'port' => 587,
        'encryption' => 'tls',
        'username' => 'alex@noppenberger.org',
        'password' => 'Akwg44Frt6Cx',
        'from' => ['address' => 'alex@noppenberger.org', 'name' => 'Alex'],
    ];

    // MailManager aus dem Service Container holen
    $manager = app(MailManager::class);

    // Mailer mit dynamischer Config erstellen
    $mailer = $manager->mailer('smtp', $config);

    // E-Mail senden
    $res = $mailer->to($mail['to'])->send(new FilamentMail($mail['body'], $mail['attachment'][0]));
}

    function getUserMailer($host, $port, $username, $password, $encryption = 'tls') {
        $transport = new SmtpTransport($host, $port, $encryption);
        $transport->setUsername($username);
        $transport->setPassword($password);

        return new Mailer('user-mailer', app()['view'], $transport, app()['events']);
    }

}
