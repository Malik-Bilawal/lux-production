<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_gallery', function (Blueprint $table) {
            if (Schema::hasColumn('product_gallery', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });

        Schema::table('product_images', function (Blueprint $table) {
            if (Schema::hasColumn('product_images', 'is_primary')) {
                $table->dropColumn('is_primary');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_gallery', function (Blueprint $table) {
            $table->integer('quantity')->nullable(); // Only add if you want rollback
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false); // Only add if you want rollback
        });
    }
};
