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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // Pricing
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            
            // Booking types
            $table->boolean('one_time_booking')->default(true);
            $table->boolean('subscription_booking')->default(false);
            
            // ROT-avdrag
            $table->boolean('rot_eligible')->default(false);
            $table->decimal('rot_percent', 5, 2)->default(30.00);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

