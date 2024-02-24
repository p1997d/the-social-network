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
        Schema::create('chat_system_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender')->constrained('users');
            $table->unsignedBigInteger('recipient')->nullable();
            $table->foreignId('chat')->constrained('chats');
            $table->string('content');
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->foreign('recipient')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_system_messages');
    }
};
