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
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname');
            $table->string('surname');
            $table->string('sex');
            $table->string('avatar');
            $table->string('location');
            $table->date("birth");
            $table->string('education');
            $table->string('family_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('firstname');
            $table->dropColumn('surname');
            $table->dropColumn('sex');
            $table->dropColumn('avatar');
            $table->dropColumn('location');
            $table->dropColumn('birth');
            $table->dropColumn('education');
            $table->dropColumn('family_status');
        });
    }
};
