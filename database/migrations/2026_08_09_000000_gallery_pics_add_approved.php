<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Freigabe-Flag fuer Fotos.
 *
 * Default 1, damit der Bestand und der alte Upload-Weg unveraendert
 * weiterlaufen. Nur der Massenimport setzt bewusst 0 — diese Fotos
 * erscheinen erst auf /travel-blog2, nachdem sie im Admin freigegeben wurden.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('gallery_pics', 'approved')) {
            return;
        }

        Schema::table('gallery_pics', function (Blueprint $table) {
            $table->boolean('approved')->default(1)->index();
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('gallery_pics', 'approved')) {
            return;
        }

        Schema::table('gallery_pics', function (Blueprint $table) {
            $table->dropColumn('approved');
        });
    }
};
