<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_galleries', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->string('add_to_cart_uri', 255);
            $table->string('buy_now_uri', 255);
            $table->string('banner', 255)->nullable();

            $table->text('craftsmanship_desc')->nullable();
            $table->text('material_desc')->nullable();
            $table->text('key_features')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_galleries');
    }
};

