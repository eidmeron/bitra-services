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
            $table->text('full_content')->nullable();
            $table->string('image')->nullable();
            $table->json('features')->nullable();
            $table->json('includes')->nullable();
            $table->json('faq')->nullable();
            $table->string('booking_url')->nullable();
            $table->string('icon')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // Pricing
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(25.00);
            
            // Booking types
            $table->boolean('one_time_booking')->default(true);
            $table->boolean('subscription_booking')->default(false);
            $table->json('subscription_types')->nullable();
            $table->decimal('daily_multiplier', 5, 2)->default(1.05);
            
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

