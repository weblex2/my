<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Index auf gallery_pic_content(pic_id).
 *
 * Ohne ihn gab es nur den PRIMARY auf id. Jeder Bildabruf ueber
 * GalleryController::serveImage() loeste damit einen Full Table Scan aus —
 * ueber eine Tabelle mit hunderten MB Blob-Daten. Gemessen: ~10 Sekunden
 * pro Bild, unabhaengig von der Bildgroesse.
 *
 * Nur pic_id, nicht (pic_id, size): size ist ein TEXT und liesse sich nur mit
 * Praefixlaenge indizieren. Pro Foto gibt es ohnehin nur wenige Zeilen, das
 * Filtern nach size danach kostet nichts.
 */
return new class extends Migration
{
    public function up(): void
    {
        $exists = collect(DB::select('show index from gallery_pic_content'))
            ->contains(fn($i) => $i->Key_name === 'gpc_pic_id_idx');

        if ($exists) {
            return;
        }

        Schema::table('gallery_pic_content', function (Blueprint $table) {
            $table->index('pic_id', 'gpc_pic_id_idx');
        });
    }

    public function down(): void
    {
        $exists = collect(DB::select('show index from gallery_pic_content'))
            ->contains(fn($i) => $i->Key_name === 'gpc_pic_id_idx');

        if (!$exists) {
            return;
        }

        Schema::table('gallery_pic_content', function (Blueprint $table) {
            $table->dropIndex('gpc_pic_id_idx');
        });
    }
};
