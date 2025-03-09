<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('messages', function (Blueprint $table) {
            // Drop the old foreign key and column
            $table->dropForeign(['channel_id']);
            $table->dropColumn('channel_id');

            // Add the new foreign key for conversations
            $table->foreignId('conversation_id')->after('user_id')->constrained()->onDelete('cascade');
        });
    }

    public function down() {
        Schema::table('messages', function (Blueprint $table) {
            // Drop the new foreign key and column
            $table->dropForeign(['conversation_id']);
            $table->dropColumn('conversation_id');

            // Restore the old column
            $table->foreignId('channel_id')->after('user_id')->constrained()->onDelete('cascade');
        });
    }
};