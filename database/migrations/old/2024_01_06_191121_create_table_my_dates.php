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
        Schema::create('my_dates', function (Blueprint $table) {
            $table->id();
            $table->text('topic',200);
            $table->datetime('date');
            $table->datetime('reminder');
            $table->boolean('recurring');
            $table->text('recurring_interval',4);
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
        Schema::dropIfExists('table_my_dates');
    }
};
