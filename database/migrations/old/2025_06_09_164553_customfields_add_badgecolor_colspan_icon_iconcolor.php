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
            $table->string('bagdecolor')->after('colspan');
            $table->string('icon')->nullable()->after('colspan');
            $table->string('icon_color')->nullable()->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fil_table_fields', function (Blueprint $table) {
            $table->dropColumn('bagdecolor');
            $table->dropColumn('icon');
            $table->dropColumn('icon_color');
        });
    }
};
