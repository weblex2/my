<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Exception;
use SoapClient;
use Carbon\Carbon;
use App\Models\Contact;

class ZimbraController extends Controller
{
    private $soapUrl;
    private $username;
    private $password;
    private $authToken;

    public function __construct()
    {
        $this->soapUrl = 'https://mail.efm.de:7071/service/admin/soap/'; // Zimbra SOAP-URL
        $this->username = 'alex.noppenberger@assd.com'; // Deine E-Mail-Adresse
        $this->password = 'Akwg44Frt6Cx'; // Dein Passwort
        $this->authToken = null;
    }

    public function fetchTodayEmails()
    {
        // Konfigurationsdaten aus .env
        $host = "mail.efm.de"; #/zimbra/#1
        $port = 993;
        $username = $this->username;
        $password = $this->password;
        $ssl = "/ssl";

        // IMAP-Verbindungsstring
        $mailbox = "{" . $host . ":" . $port . "/imap" . $ssl . "}INBOX";

        // Verbindung herstellen
        $imap = imap_open($mailbox, $username, $password);
        if ($imap === false) {
            return response()->json(['error' => 'Verbindung fehlgeschlagen: ' . imap_last_error()], 500);
        }

        // Heutiges Datum für den Filter
        $today = Carbon::today()->format('d-M-Y');

        // Suche nach E-Mails von heute
        $emails = imap_search($imap, 'SINCE "' . $today . '"');
        if ($emails === false) {
            imap_close($imap);
            return response()->json(['message' => 'Keine E-Mails gefunden'], 200);
        }

        // Ergebnisse verarbeiten
        $result = [];
        foreach ($emails as $emailId) {
            $result[] = $this->parseEmail($imap, $emailId);
        }

        // Verbindung schließen
        imap_close($imap);
        //return response()->json($result);
        return $result;
    }

    /**
     * Parst eine einzelne E-Mail und extrahiert Metadaten, Inhalt und Anhänge.
     *
     * @param resource $imap IMAP-Verbindung
     * @param int $emailId E-Mail-ID
     * @return array Geparsed E-Mail-Daten
     */
    private function parseEmail($imap, $emailId)
    {
        // Header und Struktur holen
        $header = imap_headerinfo($imap, $emailId);
        $structure = imap_fetchstructure($imap, $emailId);
        $uid = imap_uid($imap, $emailId); // Eindeutige IMAP-UID

        // Message-ID aus Header extrahieren
        $rawHeader = imap_fetchheader($imap, $emailId);
        $messageId = '';
        if (preg_match('/Message-ID: <(.*?)>/i', $rawHeader, $match)) {
            $messageId = $match[1];
        }

        // Text- und HTML-Inhalt extrahieren
        $textBody = '';
        $htmlBody = '';
        if (isset($structure->parts) && count($structure->parts)) {
            foreach ($structure->parts as $partNumber => $part) {
                $partNum = $partNumber + 1;

                // Text/plain
                if ($part->type == 0 && $part->subtype == 'PLAIN') {
                    $textBody = imap_fetchbody($imap, $emailId, $partNum);
                    if ($part->encoding == 3) { // BASE64
                        $textBody = base64_decode($textBody);
                    } elseif ($part->encoding == 4) { // QUOTED-PRINTABLE
                        $textBody = quoted_printable_decode($textBody);
                    }
                    $textBody = imap_utf8($textBody);
                }

                // Text/html
                if ($part->type == 0 && $part->subtype == 'HTML') {
                    $htmlBody = imap_fetchbody($imap, $emailId, $partNum);
                    if ($part->encoding == 3) { // BASE64
                        $htmlBody = base64_decode($htmlBody);
                    } elseif ($part->encoding == 4) { // QUOTED-PRINTABLE
                        $htmlBody = quoted_printable_decode($htmlBody);
                    }
                    $htmlBody = imap_utf8($htmlBody);
                }
            }
        } else {
            // Einfache E-Mails ohne MIME-Teile
            $textBody = imap_fetchbody($imap, $emailId, 1);
            $textBody = imap_utf8($textBody);
        }

        // Anhänge
        $attachments = [];
        if (isset($structure->parts)) {
            foreach ($structure->parts as $partNumber => $part) {
                if (isset($part->ifdparameters) && $part->ifdparameters && $part->subtype != 'PLAIN' && $part->subtype != 'HTML') {
                    $partNum = $partNumber + 1;
                    $filename = '';
                    foreach ($part->dparameters as $param) {
                        if (strtolower($param->attribute) == 'filename') {
                            $filename = $param->value;
                        }
                    }
                    if ($filename) {
                        $attachmentData = imap_fetchbody($imap, $emailId, $partNum);
                        if ($part->encoding == 3) {
                            $attachmentData = base64_decode($attachmentData);
                        }
                        $attachments[] = [
                            'filename' => $filename,
                            'size' => strlen($attachmentData),
                            // 'data' => base64_encode($attachmentData), // Optional: Daten als Base64
                        ];
                    }
                }
            }
        }

        // Ergebnis zusammenstellen
        return [
            'uid' => $uid, // Eindeutige IMAP-UID
            'message_id' => $messageId, // Message-ID (falls vorhanden)
            'subject' => isset($header->subject) ? imap_utf8($header->subject) : '',
            'from' => isset($header->from[0]->mailbox, $header->from[0]->host)
                ? $header->from[0]->mailbox . '@' . $header->from[0]->host
                : '',
            'to' => isset($header->to[0]->mailbox, $header->to[0]->host)
                ? $header->to[0]->mailbox . '@' . $header->to[0]->host
                : '',
            'date' => isset($header->date) ? $header->date : '',
            'text_body' => $textBody,
            'html_body' => $htmlBody,
            'attachments' => $attachments,
        ];
    }

    public function saveEmails(){
        $emails = $this->fetchTodayEmails();
        //dd($emails);
        foreach ($emails as $email){
            $contact  = Contact::where('external_id',$email['uid'])->first();
            if ($contact){
                continue;
            }
            else{
                $contact  = new Contact();
                $contact->external_id = $email['uid'];
                $contact->customer_id = 1;
                $contact->subject = $email['subject'];
                $contact->type = 'email';
                $contact->details = $email['text_body'];
                $contact->contacted_at =  Carbon::parse($email['date'])->format('Y-m-d H:i:s');
                $contact->save();
            }
        }
    }
}
