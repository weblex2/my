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
            $table->string('link')->nullable()->after('icon_color');
            $table->string('link_target')->nullable()->after('link');
            $table->integer('section')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fil_table_fields', function (Blueprint $table) {
            $table->dropColumn('link');
            $table->dropColumn('link_target');
            $table->dropColumn('section');
        });
    }
};
