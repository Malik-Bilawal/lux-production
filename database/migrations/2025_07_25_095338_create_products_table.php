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
            $table->float('price');
            $table->float('cut_price')->nullable(); // ✅ now nullable
            $table->text('description');
            $table->string('image')->nullable(); // if multiple images, handle as JSON
            $table->enum('category', ['Watch', 'Neck & Wrist Collection']);
            $table->boolean('is_top_selling')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_feature_card')->default(false); // typo fixed: feature_card
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->float('rating')->nullable(); // manual rating input
            $table->json('tags')->nullable();
            $table->string('slug')->unique();
            $table->integer('stock_quantity')->default(0);
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
