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
        Schema::table('friends', function (Blueprint $table) {
            $table->dropColumn('accepted');
            $table->tinyInteger('status')->default(0);
            $table->timestamp('sented_at');
            $table->timestamp('status_changed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('friends', function (Blueprint $table) {
            $table->boolean('accepted')->default(false);
            $table->dropColumn('status');
            $table->dropColumn('sented_at');
            $table->dropColumn('status_changed_at');
        });
    }
};
