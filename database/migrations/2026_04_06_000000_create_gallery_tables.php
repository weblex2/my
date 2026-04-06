<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Consolidated gallery tables migration for dev environments.
 * Production already has these tables from the old/ migrations.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gallery')) {
            Schema::create('gallery', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->string('country_map_name')->nullable();
                $table->string('country_color')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gallery_mappoint')) {
            Schema::create('gallery_mappoint', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('gallery_id')->nullable();
                $table->string('country_id')->nullable();
                $table->string('mappoint_name')->nullable();
                $table->decimal('lon', 10, 6)->nullable();
                $table->decimal('lat', 10, 6)->nullable();
                $table->integer('ord')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gallery_pics')) {
            Schema::create('gallery_pics', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('gallery_id')->nullable();
                $table->unsignedBigInteger('mappoint_id')->nullable();
                $table->string('pic')->nullable();
                $table->json('meta')->nullable();
                $table->decimal('lon', 10, 6)->nullable();
                $table->decimal('lat', 10, 6)->nullable();
                $table->timestamp('taken_at')->nullable();
                $table->integer('ord')->nullable();
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('gallery_pics', 'taken_at')) {
            Schema::table('gallery_pics', function (Blueprint $table) {
                $table->timestamp('taken_at')->nullable()->after('lon');
            });
        }

        if (!Schema::hasTable('gallery_pic_content')) {
            Schema::create('gallery_pic_content', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pic_id')->nullable();
                $table->string('size', 10)->nullable();  // TN, XL, MOV
                $table->longText('filecontent')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gallery_texts')) {
            Schema::create('gallery_texts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pic_id')->nullable();
                $table->unsignedBigInteger('gallery_id')->nullable();
                $table->unsignedBigInteger('mappoint_id')->nullable();
                $table->longText('text')->nullable();
                $table->string('language', 5)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gallery_config')) {
            Schema::create('gallery_config', function (Blueprint $table) {
                $table->id();
                $table->string('option')->nullable();
                $table->string('value')->nullable();
                $table->string('value2')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_texts');
        Schema::dropIfExists('gallery_pic_content');
        Schema::dropIfExists('gallery_pics');
        Schema::dropIfExists('gallery_mappoint');
        Schema::dropIfExists('gallery_config');
        Schema::dropIfExists('gallery');
    }
};
