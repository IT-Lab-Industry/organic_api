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
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('category_id');
            $table->integer('brand_id');
            $table->string('thumbnail_image')->nullable();
            $table->text('product_description')->nullable();
            $table->tinyInteger('featured')->default(0);
            $table->double('price');
            $table->double('discount_price')->nullable();
            $table->string('discount_type')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->double('rating')->nullable();
            $table->string('product_video')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
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
