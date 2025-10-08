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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            
            // Relations
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            
            // Booking type
            $table->enum('booking_type', ['one_time', 'subscription'])->default('one_time');
            $table->enum('subscription_frequency', ['daily', 'weekly', 'biweekly', 'monthly'])->nullable();
            
            // Contact info
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('customer_message')->nullable();
            
            // Form data
            $table->json('form_data'); // All form field responses
            
            // Pricing breakdown
            $table->decimal('base_price', 10, 2);
            $table->decimal('variable_additions', 10, 2)->default(0);
            $table->decimal('city_multiplier', 5, 2);
            $table->decimal('rot_deduction', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2);
            
            // Status workflow
            $table->enum('status', [
                'pending',           // Awaiting admin review
                'assigned',          // Assigned to company
                'in_progress',       // Company working on it
                'completed',         // Service completed
                'cancelled',         // Cancelled by user/admin
                'rejected'           // Rejected by company
            ])->default('pending');
            
            // Dates
            $table->dateTime('preferred_date')->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

