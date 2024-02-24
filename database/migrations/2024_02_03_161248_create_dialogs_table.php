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
        Schema::create('dialogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender');
            $table->unsignedBigInteger('recipient');
            $table->string('content');
            $table->timestamp('sent_at');
            $table->timestamp('viewed_at')->nullable();
            $table->boolean('delete_for_sender')->default(false);
            $table->boolean('delete_for_recipient')->default(false);

            $table->foreign('sender')->references('id')->on('users');
            $table->foreign('recipient')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialogs');
    }
};
