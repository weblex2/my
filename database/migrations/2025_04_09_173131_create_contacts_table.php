<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('fil_customers')->onDelete('cascade');
            $table->string('type'); // z. B. "Telefonat", "Email"
            $table->text('details')->nullable(); // Details zum Kontakt, z. B. Notizen oder Inhalt
            $table->dateTime('contacted_at')->nullable(); // Wann der Kontakt stattgefunden hat
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
