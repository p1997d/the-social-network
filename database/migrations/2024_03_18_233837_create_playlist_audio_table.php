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
        Schema::create('playlist_audio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('playlist')->constrained('playlists');
            $table->foreignId('audio')->constrained('audios');
            $table->unique(['playlist', 'audio']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist_audio');
    }
};
