<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\WegDoc;
use App\Models\WegDocAttachment;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Hfig\MAPI\MapiMessageFactory;
use Hfig\MAPI;
use Hfig\MAPI\OLE\Pear;
use DateTime;
use DateTimeZone;



class FileUpload extends Component
{
    use WithFileUploads;

    public array $files = [];

    public function updatedFiles()
    {
        foreach ($this->files as $file) {
            $content = file_get_contents($file->getRealPath());
            DB::transaction(function () use ($file, $content) {
                $data = [];

                if (strtolower($file->getClientOriginalExtension()) === 'msg') {
                    $message = $this->parseMsgFile($file->getRealPath());
                    $attachments = $message->getAttachments();
                    $properties = $message->properties;
                    $messageId  = $properties['internet_message_id'] ?? Uuid::uuid4()->toString();
                    $subject    = $properties['subject'];
                    $fromName   = $properties['sender_name'] ?? '(kein Name)';
                    $fromEmail  = $properties['sender_email_address'] ?? '(keine Adresse)';
                    $to         = $properties['display_to'] ?? '';
                    $cc         = $properties['display_cc'] ?? '';
                    $bcc        = $properties['display_bcc'] ?? '';
                    $body_plain = $properties['body'];
                    $body_html  = $properties['html_body'];
                    $recipients = $message->getRecipients();
                    $sentOn = $properties['creation_time'] ?? null;
                    $clientSubmitTime = $properties['clientSubmitTime'] ?? null;
                    $received = $properties['client_submit_time'] ?? null;  // Versandzeit

                    $dt = new DateTime("@$received"); // UTC Zeit
                    $dt->setTimezone(new DateTimeZone('Europe/Berlin')); // Setze lokale TZ

                    $received =  $dt->format('Y-m-d H:i:s'); // Ausgabe: 2025-07-23 12:06:25 (MESZ)

                    // Datei-Eintrag
                    $wegDoc = WegDoc::updateOrCreate(
                        ['message_id' => $messageId], // Suchkriterien
                        [
                            'name' => $file->getClientOriginalName(),
                            'subject' => $subject,
                            'from' => $fromEmail,
                            'from_name' => $fromName,
                            'to' => $to,
                            'body' => $body_html!="" ? $body_html : $body_plain,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'content' => json_encode($data),
                            'received' => $received,
                        ]
                    );

                    // Anhänge speichern
                    foreach ($attachments as $attachment) {
                        WegDocAttachment::updateOrCreate(
                            [
                                'message_id' => $messageId,
                                'filename' => $attachment->getFilename(),
                            ],
                            [
                                'content_type' => $attachment->getMimeType(),
                                'content' => base64_encode($attachment->getData()),
                                'size' => strlen($attachment->getData()),
                            ]
                        );
                    }
                } else {
                    // Normale Datei speichern
                    $message_id = Uuid::uuid4()->toString();
                    WegDoc::create([
                        'name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'content' => null,
                        'message_id' => $message_id,
                    ]);


                     // Anhänge speichern

                    $wegDoc = WegDocAttachment::create([
                        'message_id' => $message_id,
                        'filename' => $file->getClientOriginalName(),
                        'content_type' => $file->getMimeType(), // MIME-Typ (sofern vorhanden)
                        'content' => base64_encode($content) ?? '', // Optional
                        'size' => strlen($content),
                    ]);

                }
                if ($wegDoc->wasRecentlyCreated) {
                    session()->flash('success', 'Datei erfolgreich hochgeladen.');
                }
                else{
                    session()->flash('success', 'Datei erfolgreich aktualisiert.');
                }
            });
        }

        $this->reset('files');
    }

    private function getMessageDate($message): ?string
{
    $properties = $message->properties;
    $possibleDateKeys = ['delivery_time', 'creation_time', 'client_submit_time'];

    foreach ($possibleDateKeys as $key) {
        if (isset($properties[$key]) && $properties[$key] instanceof \DateTime) {
            return $properties[$key]->format('Y-m-d H:i:s');
        }
    }

    return null;
}


public function listProperties($properties)
{
    $propertiesArray = [];

    // Reflection verwenden, um auf das geschützte Property `map` zuzugreifen
    $reflection = new \ReflectionClass($properties);
    if ($reflection->hasProperty('map')) {
        $mapProp = $reflection->getProperty('map');
        $mapProp->setAccessible(true); // Zugriff erzwingen
        $map = $mapProp->getValue($properties);
        $map = (new \ReflectionClass($properties))->getProperty('map');
            $map->setAccessible(true);
            $mapArray = $map->getValue($properties);

            foreach ($mapArray as $name => $keyObj) {
                try {
                    $prop = $properties->get($keyObj);
                    $val = $prop->getValue();

                    echo (string) $name . ' => ';
                    echo ($val instanceof \DateTimeInterface) ? $val->format('Y-m-d H:i:s') : print_r($val, true);
                    echo "\n";
                } catch (\Throwable $e) {
                    echo (string) $name . ' => ERROR: ' . $e->getMessage() . "\n";
                }
            }
        foreach ($map as $key => $property) {
            try {
                $propertiesArray[(string) $key] = $property->getValue();
            } catch (\Throwable $e) {
                $propertiesArray[(string) $key] = 'ERROR: ' . $e->getMessage();
            }
        }
    }

    return $propertiesArray;
    }



    public function parseMsgFile(string $filePath)
    {
        try {

            $messageFactory = new MAPI\MapiMessageFactory();
            $documentFactory = new Pear\DocumentFactory();
            $ole = $documentFactory->createFromFile($filePath);
            $message = $messageFactory->parseMessage($ole);
            return $message;

        } catch (\Exception $e) {
            session()->flash('error', 'Fehler beim Parsen der MSG-Datei: ' . $e->getMessage());
            return null;
        }
    }


    public function render()
    {
        return view('livewire.file-upload');
    }
}
