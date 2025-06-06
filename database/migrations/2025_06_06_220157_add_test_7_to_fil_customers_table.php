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
        Schema::table('fil_customers', function (Blueprint $table) {
                        $table->string('test_7', 155);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fil_customers', function (Blueprint $table) {
                        $table->string('test_7', 155);

        });
    }
};
