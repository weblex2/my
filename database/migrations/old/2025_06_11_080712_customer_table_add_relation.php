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
            $table->string('relation_table')->nullable()->after('type');
            $table->string('relation_show_field')->nullable()->after('relation_table');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fil_table_fields', function (Blueprint $table) {
            $table->dropColumn('relation_table');
            $table->dropColumn('relation_show_field');
        });
    }
};
