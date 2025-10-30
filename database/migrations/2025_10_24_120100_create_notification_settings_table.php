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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // email, sms, push, in_app
            $table->string('category'); // booking, user, company, admin, system
            $table->string('event'); // booking_completed, user_registered, etc.
            $table->string('subject')->nullable();
            $table->text('template');
            $table->json('variables')->nullable(); // Available template variables
            $table->boolean('enabled')->default(true);
            $table->boolean('auto_send')->default(true);
            $table->json('recipients')->nullable(); // Who receives this notification
            $table->json('conditions')->nullable(); // When to send this notification
            $table->integer('priority')->default(1); // 1-5, 5 being highest
            $table->timestamps();
            
            $table->unique(['type', 'category', 'event']);
            $table->index(['category', 'enabled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
