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
       // migration file me:
Schema::create('home_videos', function (Blueprint $table) {
    $table->id();
    $table->string('video_link');
    $table->string('thumbnail');
    $table->boolean('status')->default(1);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_video');
    }
};
