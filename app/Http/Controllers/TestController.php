<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use App\Models\CustomerAssd;
use App\Models\CustomerAddress;
use App\Models\FilTableFields;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\FilamentConfig;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Mail;
use Webklex\PHPIMAP\ClientManager;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Http\Controllers\MailController;


class TestController extends Controller
{

    public function testRedis(){
        try {
            $value = Redis::get('test:key');
            if (!$value){
                // Setze einen Schlüssel
                Redis::set('test:key', 'Hallo Redis!');
            }
            // Lese den Schlüssel aus
            $value = Redis::get('test:key');

            return response()->json([
                'status' => 'OK',
                'message' => $value,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Fehler',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function chatGptApi(){
        //phpinfo();
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
       $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"model\": \"gpt-3.5-turbo\", \"prompt\": \"Say this is a test\", \"temperature\": 0, \"max_tokens\": 7}");

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
        $headers = array();
        $headers[] = 'Content-Type: application/json';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);


        dump($result);

    }

    public function testModel(){


        $emails = Customer::all()->pluck('email', 'email')->toArray();
        dd($emails);

        $filter = FilamentConfig::getFiltersFor('customer');
        dump($filter);
        die();
        $user = User::find(1);
        dump($user);
        dump($user->user01);
        #$pw = decrypt($user->user01);
        #echo $pw;
        die();
        #$customer = Customer::find(5);
        #$customer->load('assd');
        //dump($customer);
        #dump($customer->assd->bi);
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );
        $user = \App\Models\User::find(1);
        $user->assignRole('admin');
        $adminRole->givePermissionTo(Permission::all());
        echo "Done";
    }


    public function testEmail(){
        $x = 1;

        $mailcontent = [
           'subject' => 'it´s just a Test',
           'recipient' => 'alex@noppenberger.org',
           'body' => '<!DOCTYPE html>
                <html>
                <head>
                    <title>Test-E-Mail</title>
                </head>
                <body>
                    <h1>Test-E-Mail</h1>
                    <p>Dies ist eine Test-E-Mail, gesendet über Strato.</p>
                </body>
                </html>'

        ];

        $mail = new MailController();
        $mail->sendMail($mailcontent);
        $mail->putMailInSentItems($mailcontent);
        die();

        try {
            // Strato-Konfiguration direkt im Code
            config([
                'mail.mailer' => 'smtp',
                'mail.host' => 'smtp.strato.de',
                'mail.port' => 465,
                'mail.username' => 'alex@noppenberger.org', // Ersetze mit deiner Strato-E-Mail
                'mail.password' => '!Cyberbob03', // Ersetze mit deinem Passwort
                'mail.encryption' => 'ssl',
                'mail.from.address' => 'test@example.com', // Ersetze mit deiner Strato-E-Mail
                'mail.from.name' => 'Test-App',
            ]);

            // E-Mail-Empfänger
            $recipient = 'alex@noppenberger.org'; // Ersetze mit einer echten E-Mail-Adresse

            // E-Mail-Inhalt direkt als HTML
            $messageContent = "<!DOCTYPE html>
            <html>
            <head>
                <title>Test-E-Mail</title>
            </head>
            <body>
                <h1>Test-E-Mail</h1>
                <p>Dies ist eine Test-E-Mail, gesendet über Strato.</p>
            </body>
            </html>";

            // E-Mail senden
            #Mail::html($messageContent, function ($message) use ($recipient) {
            #    $message->to($recipient)
            #            ->subject('Test-E-Mail von Strato');
            #});

            $cm = new ClientManager();
            $client = $cm->make([
                'host' => 'imap.strato.de',
                'port' => 993,
                'encryption' => 'ssl',
                'validate_cert' => true,
                'username' => 'alex@noppenberger.org', // Ersetze mit deiner Strato-E-Mail
                'password' => '!Cyberbob03', // Ersetze mit deinem Passwort
                'protocol' => 'imap',
            ]);

            // Verbindung herstellen
            $client->connect();
            $folders = $client->getFolders();
            dump($folders);
            // "Gesendet"-Ordner auswählen
            $folder = $client->getFolder('Sent Items');
            if (!$folder) {
                return response()->json(['status' => 'error', 'message' => 'Gesendet-Ordner nicht gefunden'], 500);
            }

            $subject = "Email im Postausgang";
            // E-Mail-Inhalt für IMAP vorbereiten
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
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }


    public function sendEmail(){
           $mail = new MailController();
           $mail->putMailInSentItems();
           die();

        // PHPMailer-Instanz erstellen
            $mailer = new PHPMailer(true);

            // SMTP-Konfiguration für Strato
            $mailer->isSMTP();
            $mailer->Host = 'smtp.strato.de';
            $mailer->SMTPAuth = true;
            $mailer->Username = 'alex@noppenberger.org'; // Ersetze mit deiner Strato-E-Mail
            $mailer->Password = '!Cyberbob03'; // Ersetze mit deinem Passwort
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mailer->Port = 465;
            $mailer->CharSet = 'UTF-8';

            // Absender und Empfänger
            $fromName = 'Test-App';
            $fromEmail = 'alex@noppenberger.net'; // Ersetze mit deiner Strato-E-Mail
            $recipient = 'alex@noppenberger.org'; // Ersetze mit einer echten E-Mail-Adresse
            $subject = 'Test-E-Mail von Strato';

            $mailer->setFrom($fromEmail, $fromName);
            $mailer->addAddress($recipient);

            // E-Mail-Inhalt
            $messageContent = "<!DOCTYPE html>
            <html>
            <head>
                <title>Test-E-Mail</title>
                <meta charset='UTF-8'>
            </head>
            <body>
                <h1>Test-E-Mail</h1>
                <p>Dies ist eine Test-E-Mail, gesendet über Strato mit Sonderzeichen: äöüß.</p>
            </body>
            </html>";

            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            $mailer->Body = $messageContent;

            // E-Mail senden
            $mailer->send();
    }

    public function sendWhatsApp(){
        $phone="+491722044069";  // Enter your phone number here
        $apikey="4153439";       // Enter your personal apikey received in step 3 above
        $message = "hi from WhatsApp";
        $url='https://api.callmebot.com/whatsapp.php?source=php&phone='.$phone.'&text='.urlencode($message).'&apikey='.$apikey;

        if($ch = curl_init($url))
        {
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $html = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // echo "Output:".$html;  // you can print the output for troubleshooting
            curl_close($ch);
            return (int) $status;
        }
        else
        {
            return false;
        }
    }

}
