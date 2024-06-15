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
        Schema::create('PDFGenerate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('show_name');
            $table->string('series');
            $table->string('lead_actor');
            $table->timestamps();
        });
}
};
