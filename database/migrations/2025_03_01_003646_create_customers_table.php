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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->defaultTrue();
            $table->string('name');
            $table->string('first_name')->nullabe();
            $table->foreignId('company_id')
                ->constrained('company')
                ->nullable();
            $table->string('email')->nullabe();
            $table->string('phone')->nullabe();
            $table->string('website')->nullabe();
            $table->longText('comments')->nullabe();
            $table->date('date_birth')->nullabe();
            $table->string('language')->nullabe();
            $table->string('external_id');
            $table->integer('primary_address')->nullabe();
            $table->date('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
