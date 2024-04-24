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
        Schema::table('dialogs', function (Blueprint $table) {
            $table->dropColumn('content');
            $table->dropColumn('sent_at');
            $table->dropColumn('viewed_at');
            $table->dropColumn('delete_for_sender');
            $table->dropColumn('delete_for_recipient');
            $table->dropColumn('changed_at');
            $table->dropColumn('attachments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dialogs', function (Blueprint $table) {
            $table->longText('content');
            $table->timestamp('sent_at');
            $table->timestamp('viewed_at')->nullable();
            $table->boolean('delete_for_sender')->default(false);
            $table->boolean('delete_for_recipient')->default(false);
            $table->timestamp('changed_at')->nullable();
            $table->string('attachments')->nullable();
        });
    }
};
