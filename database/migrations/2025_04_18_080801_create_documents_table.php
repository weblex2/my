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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->string('external_id')->nullable(); // Nullable Fremdschlüssel für contacts.external_id
            $table->foreign('external_id')->references('external_id')->on('contacts')->onDelete('cascade');
            $table->string('filename');
            $table->unsignedBigInteger('size');
            $table->string('mime_type');
            $table->binary('content'); // Binärinhalt des Dokuments
            $table->integer('user');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE documents MODIFY content LONGBLOB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
