<?php

namespace App\Console\Commands;

use App\Models\GalleryPicContent;
use App\MyClasses\BlogCreator;
use Illuminate\Console\Command;

/**
 * Wandelt bereits gespeicherte Videos (gallery_pic_content, size = MOV) in
 * H.264/MP4 um. Neue Uploads macht BlogCreator schon selbst - das hier ist
 * fuer den Bestand, z. B. HEVC-Videos vom iPhone, die Chrome nicht abspielt.
 */
class Tb2TranscodeVideos extends Command
{
    protected $signature = 'tb2:transcode-videos
                            {ids?* : Nur diese pic_ids (Standard: alle Videos, die noch kein video/mp4 sind)}
                            {--dry-run : Nur anzeigen, nichts aendern}';

    protected $description = 'Wandelt gespeicherte Videos nach H.264/MP4 um (Browser-kompatibel)';

    public function handle(): int
    {
        $query = GalleryPicContent::where('size', 'MOV');

        if ($ids = $this->argument('ids')) {
            $query->whereIn('pic_id', array_map('intval', $ids));
        } else {
            $query->where('filecontent', 'not like', 'data:video/mp4;%');
        }

        // Nur die IDs laden - die Inhalte koennen jeweils >100 MB gross sein.
        $rows = $query->pluck('id', 'pic_id');

        if ($rows->isEmpty()) {
            $this->info('Nichts zu tun.');
            return self::SUCCESS;
        }

        $this->info($rows->count() . ' Video(s): ' . $rows->keys()->implode(', '));
        if ($this->option('dry-run')) {
            return self::SUCCESS;
        }

        $tmp  = storage_path('tmp');
        @mkdir($tmp, 0777, true);
        $fail = 0;

        foreach ($rows as $picId => $id) {
            $content = GalleryPicContent::find($id);
            [$meta, $b64] = explode(',', $content->filecontent, 2);

            $in  = "$tmp/tb2_transcode_{$picId}_in";
            $out = "$tmp/tb2_transcode_{$picId}.mp4";
            file_put_contents($in, base64_decode($b64));
            unset($b64);

            if (BlogCreator::transcodeToWebMp4($in, $out)) {
                $before = filesize($in);
                $content->filecontent = 'data:video/mp4;base64,' . base64_encode(file_get_contents($out));
                $content->save();
                $this->line(sprintf('  %d: %.1f MB -> %.1f MB', $picId, $before / 1048576, filesize($out) / 1048576));
            } else {
                $this->error("  $picId: Umwandlung fehlgeschlagen (siehe Log)");
                $fail++;
            }

            @unlink($in);
            @unlink($out);
            unset($content);
        }

        return $fail ? self::FAILURE : self::SUCCESS;
    }
}
