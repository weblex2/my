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
        Schema::create('customer_assds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('fil_customers')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('spread')->nullable();
            $table->string('arr')->nullable();
            $table->string('cm')->nullable();
            $table->string('bi')->nullable();
            $table->string('solution')->nullable();
            $table->string('houses')->nullable();
            $table->string('rooms')->nullable();
            $table->decimal('sales_valume',10,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_assds');
    }
};
