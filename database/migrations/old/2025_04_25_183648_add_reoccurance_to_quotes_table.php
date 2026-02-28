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
        Schema::table('quote_products', function (Blueprint $table) {
            $table->string('reoccurance')->nullable()->after('quantity'); // oder nach einer anderen passenden Spalte
        });
    }

    public function down(): void
    {
        Schema::table('quote_products', function (Blueprint $table) {
            $table->dropColumn('reoccurance');
        });
    }
};
