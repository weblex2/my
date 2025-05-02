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
        Schema::table('filament_configs', function (Blueprint $table) {
            $table->string('field')->nullable()->after('resource'); // oder `->after('email')`
        });
    }

    public function down(): void
    {
        Schema::table('filament_configs', function (Blueprint $table) {
            $table->dropColumn('field');
        });
    }
};
