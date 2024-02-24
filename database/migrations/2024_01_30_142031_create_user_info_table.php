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
        Schema::create('user_info', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user')->unique();

            $table->string('avatar')->nullable();
            $table->string('location')->nullable();
            $table->string('education')->nullable();
            $table->string('family_status')->nullable();
            $table->string('phone')->nullable();

            $table->foreign('user')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_info');
    }
};
