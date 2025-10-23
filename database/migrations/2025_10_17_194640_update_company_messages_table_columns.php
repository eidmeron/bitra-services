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
        Schema::table('company_messages', function (Blueprint $table) {
            // Add read_at column
            $table->timestamp('read_at')->nullable()->after('status');
            
            // Rename columns
            $table->renameColumn('name', 'guest_name');
            $table->renameColumn('email', 'guest_email');
            $table->renameColumn('phone', 'guest_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_messages', function (Blueprint $table) {
            // Rename columns back
            $table->renameColumn('guest_name', 'name');
            $table->renameColumn('guest_email', 'email');
            $table->renameColumn('guest_phone', 'phone');
            
            // Remove read_at column
            $table->dropColumn('read_at');
        });
    }
};
