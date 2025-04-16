<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Exception;
use SoapClient;
use Carbon\Carbon;

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

    public function index(){
        return view('zimbra.index');
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
            // Header und Body holen
            $header = imap_headerinfo($imap, $emailId);
            $body = imap_fetchbody($imap, $emailId, 1); // 1 = Textteil, 1.1 für HTML (falls nötig)

            $result[] = [
                'subject' => isset($header->subject) ? imap_utf8($header->subject) : '',
                'from' => isset($header->from[0]->mailbox, $header->from[0]->host)
                    ? $header->from[0]->mailbox . '@' . $header->from[0]->host
                    : '',
                'date' => isset($header->date) ? $header->date : '',
                'body' => imap_utf8($body),
            ];
        }

        // Verbindung schließen
        imap_close($imap);
        dump($result);
        return response()->json($result);
    }
}
