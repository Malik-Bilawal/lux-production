<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('product_gallery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->string('add_to_cart_uri');
            $table->string('buy_now_uri');
            $table->string('image_banner')->nullable();
            $table->text('craftsmanship_description');
            $table->text('premium_material_description');
            $table->text('key_features_description');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_gallery');
    }
};
