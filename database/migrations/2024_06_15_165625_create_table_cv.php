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
        Schema::create('cv', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->text('value')->nullable();
            $table->text('header')->nullable();
            $table->date('date_from')->default('0000-00-00');
            $table->date('date_to')->default('0000-00-00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_cv');
    }
};
