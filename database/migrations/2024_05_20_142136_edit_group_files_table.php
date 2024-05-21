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
        Schema::table('group_files', function (Blueprint $table) {
            $table->dropForeign(['file']);
            $table->dropColumn('file');

            $table->integer('file_id');
            $table->string('file_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_files', function (Blueprint $table) {
            $table->dropColumn('file_id');
            $table->dropColumn('file_type');

            $table->foreignId('file')->constrained('files');
        });
    }
};
