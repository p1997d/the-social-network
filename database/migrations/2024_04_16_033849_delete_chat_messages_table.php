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
        Schema::dropIfExists('chat_message_delete');
        Schema::dropIfExists('chat_messages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender')->constrained('users')->nullable();
            $table->foreignId('chat')->constrained('chats');
            $table->longText('content');
            $table->string('attachments')->nullable();
            $table->timestamp('sent_at');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('changed_at')->nullable();
            $table->boolean('delete_for_all')->default(false);
            $table->timestamps();
        });

        Schema::create('chat_message_delete', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user')->constrained('users');
            $table->foreignId('message')->constrained('chat_messages');
            $table->timestamp('deleted_at');
            $table->timestamps();
        });
    }
};
