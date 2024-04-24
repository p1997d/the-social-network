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
        Schema::table('chat_system_messages', function (Blueprint $table) {
            $table->dropForeign(['chat']);
            $table->dropColumn('chat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_system_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('chat')->nullable();
            $table->foreign('chat')->references('id')->on('chats');
        });
    }
};
