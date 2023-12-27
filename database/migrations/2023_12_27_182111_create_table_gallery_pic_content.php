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
        Schema::create('gallery_pic_content', function (Blueprint $table) {
            $table->id();
            $table->integer('pic_id');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE gallery_pic_content ADD filecontent LONGBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gallery_pic_content');
    }
};
