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
        Schema::create('fil_table_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('table');
            $table->string('field');
            $table->string('type');
            $table->boolean('searchable')->default(0);;
            $table->boolean('sortable')->default(0);;
            $table->boolean('disabled')->default(0);;
            $table->boolean('required')->default(0);;
            $table->boolean('dehydrated')->default(0);;
            $table->boolean('collapsible')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_fil_table_fields');
    }
};
