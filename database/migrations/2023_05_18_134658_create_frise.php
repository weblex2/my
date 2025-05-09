<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frise', function (Blueprint $table) {
            $table->id();
            $table->string('name',200);
            $table->string('street',200);
            $table->string('plz',200);
            $table->string('city',200);
            $table->string('tel',200);
            $table->string('email',200);
            $table->string('openhours',200);
            $table->binary('pic');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_frise');
    }
};
