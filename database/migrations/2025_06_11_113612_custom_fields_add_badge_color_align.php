<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('fil_table_fields', function (Blueprint $table) {
            $table->longText('badge_color')->nullable()->after('is_badge');
            $table->string('align')->nullable()->after('badge_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fil_table_fields', function (Blueprint $table) {
            $table->dropColumn('badge_color');
            $table->dropColumn('align');
        });
    }
};
