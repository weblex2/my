<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Log;
use App\Mail\DemoMail;
use Webklex\PHPIMAP\ClientManager;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */

    private $imap = [];
    private $smtp = [];
    private $sentFolder = '';

    public function __construct(){

        $this->smtp = [
            'mail.mailer' => 'smtp',
            'mail.host' => 'smtp.strato.de',
            'mail.port' => 465,
            'mail.username' => 'alex@noppenberger.org', // Ersetze mit deiner Strato-E-Mail
            'mail.password' => '!Cyberbob03', // Ersetze mit deinem Passwort
            'mail.encryption' => 'ssl',
            'mail.from.address' => 'test@example.com', // Ersetze mit deiner Strato-E-Mail
            'mail.from.name' => 'Test-App',
        ];

        $this->imap = [
            'host' =>  'imap.strato.de',
            'port' =>  993,
            'encryption' =>  'ssl',
            'validate_cert' =>  true,
            'username' =>  'alex@noppenberger.org',
            'password' =>  '!Cyberbob03',
            'protocol' =>  'imap',
        ];
        $this->sentFolder = 'Sent Items';
    }

    public function sendMail($email){

        $mail = new PHPMailer(true);

        try {
            // Server-Einstellungen
            $mail->isSMTP();
            $mail->Host       = 'smtp.strato.de';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'alex@noppenberger.org';
            $mail->Password   = '!Cyberbob03';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $recipient = $email['recipient'];
            // Absender & Empfänger
            $mail->setFrom('alex@noppenberger.org', 'Alex');
            $mail->addAddress($recipient, 'Empfängername');

            // Inhalt
            $mail->isHTML(true);
            $mail->Subject = $email['subject'];
            $mail->Body    = $email['body'];
            $mail->AltBody = 'Das ist eine Text-Version für E-Mail-Clients ohne HTML';
            $mail->send();
            echo 'Nachricht wurde gesendet';
        } catch (Exception $e) {
            echo "Nachricht konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}";
        }
    }

    public function putMailInSentItems($mail){
        try{
            // Verbindung herstellen
            $cm = new ClientManager();
            $client = $cm->make($this->imap);
            $client->connect();

            // "Gesendet"-Ordner auswählen
            $folder = $client->getFolder($this->sentFolder);
            if (!$folder) {
                \Log::error('Folder "'. $this->sentFolder. '" not found!');
                return response()->json(['status' => 'error', 'message' => 'Gesendet-Ordner '.$this->sentFolder.' nicht gefunden'], 500);
            }

            $subject = $mail['subject'] ?? "Subject";
            $recipient = $mail['recipient'] ?? "";
            $messageContent = $mail['body'] ?? "";

            // E-Mail-Inhalt für IMAP vorbereiten
            $rawMessage = "From: Test-App <alex@noppenberger.org>\r\n"; // Ersetze mit deiner Strato-E-Mail
            $rawMessage .= "To: $recipient\r\n";
            $rawMessage .= "Subject: $subject\r\n";
            $rawMessage .= "MIME-Version: 1.0\r\n";
            $rawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
            $rawMessage .= "Content-Transfer-Encoding: quoted-printable\r\n";
            $rawMessage .= "\r\n";
            $rawMessage .= quoted_printable_encode($messageContent);

            // E-Mail im "Gesendet"-Ordner speichern
            $message = \Webklex\PHPIMAP\Message::fromString($rawMessage);
            $folder->appendMessage($rawMessage); // Direkt rawMessage verwenden

            // Verbindung trennen
            $client->disconnect();

            return response()->json(['status' => 'success', 'message' => 'E-Mail erfolgreich gesendet und im Gesendet-Ordner gespeichert']);
        }
        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

    }


    public function test($mailData=[])
    {
        if (!isset($mailData['title']) || $mailData['title']=="") {
            $mailData['title'] = "Test";
        }
        if (!isset($mailData['body']) || $mailData['body']=="") {
            $mailData['body'] = "This is for testing email using smtp.";
        }

        Mail::to('alex@noppenberger.org')->send(new DemoMail($mailData));
    }
}
