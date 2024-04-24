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
        Schema::create('dialog_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dialog')->constrained('dialogs');
            $table->foreignId('message')->constrained('messages');
            $table->boolean('delete_for_sender')->default(false);
            $table->boolean('delete_for_recipient')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialog_messages');
    }
};
