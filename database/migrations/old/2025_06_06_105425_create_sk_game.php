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
        Schema::create('sk_game', function (Blueprint $table) {
            $table->id();
            $table->string('player_1')->nullable();
            $table->string('player_2')->nullable();
            $table->string('player_3')->nullable();
            $table->string('player_4')->nullable();
            $table->string('game_type')->default('new');
            $table->string('solo_player')->nullable();
            $table->integer('points_1')->default(0);
            $table->integer('points_2')->default(0);
            $table->integer('points_3')->default(0);
            $table->integer('points_4')->default(0);
            $table->string('status')->default('new');
            $table->string('sauspiel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_game');
    }
};
