<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_info', function (Blueprint $table) {
            $table->dropForeign(['avatar']);
            $table->dropColumn('avatar');
        });

        Schema::dropIfExists('files');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('type');
            $table->unsignedBigInteger('size');
            $table->foreignId('author')->constrained('users');
            $table->timestamps();
        });

        Schema::table('user_info', function (Blueprint $table) {
            $table->unsignedBigInteger('avatar')->nullable();
            $table->foreign('avatar')->references('id')->on('files');
        });
    }
};
