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
            $table->boolean('sortable')->nullable()->default(0)->change();
            $table->boolean('searchable')->nullable()->default(0)->change();
            $table->boolean('is_toggable')->nullable()->default(0)->change();
            $table->boolean('disabled')->nullable()->default(0)->change();
            $table->boolean('required')->nullable()->default(0)->change();
            $table->boolean('dehydrated')->nullable()->default(0)->change();
            $table->boolean('collapsible')->nullable()->default(0)->change();
            $table->integer('colspan')->nullable()->default(1)->change();
            $table->string('bagdecolor')->nullable()->change();
            $table->integer('order')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fil_table_fields', function (Blueprint $table) {

        });
    }
};
