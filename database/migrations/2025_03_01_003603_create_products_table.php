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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')
                ->constrained('brands')
                ->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('image')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('quantity');
            $table->decimal('price', 10, 2);
            $table->boolean('is_visible')->defaultFalse();
            $table->boolean('is_featured')->defaultFalse();
            $table->enum('type',['deliverable','downloadable'])->default('deliverable');
            $table->date('published_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
