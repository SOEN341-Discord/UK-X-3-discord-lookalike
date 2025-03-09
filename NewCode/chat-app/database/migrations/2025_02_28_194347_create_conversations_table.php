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
        Schema::create('conversations', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->string('name')->nullable(); // For group chats, null for private chats
                $table->enum('type', ['private', 'group']); // Defines if it's a private or group, can only be one of these 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};

