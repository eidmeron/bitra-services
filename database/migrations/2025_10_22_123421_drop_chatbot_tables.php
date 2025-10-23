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
        Schema::dropIfExists('chatbot_conversations');
        Schema::dropIfExists('booking_chats');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: We cannot recreate these tables as we don't have the original migration files
        // If you need to restore them, you would need to recreate the migration files
    }
};