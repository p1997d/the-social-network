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
        Schema::table('audios', function (Blueprint $table) {
            $table->dropForeign(['file']);
            $table->dropColumn('file');
            $table->string('path');
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audios', function (Blueprint $table) {
            $table->foreignId('file')->constrained('files');
            $table->dropColumn('path');
            $table->dropColumn('type');
        });
    }
};
