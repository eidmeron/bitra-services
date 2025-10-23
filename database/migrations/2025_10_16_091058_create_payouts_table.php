<?php

declare(strict_types=1);

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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->string('payout_period')->nullable(); // e.g., "Week 42, 2024"
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->decimal('booking_amount', 10, 2)->default(0); // Total booking amount
            $table->decimal('commission_amount', 10, 2)->default(0); // Admin commission
            $table->decimal('commission_rate', 5, 2)->default(0); // Rate applied
            $table->decimal('payout_amount', 10, 2)->default(0); // Amount to pay company (booking - commission)
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('company_id');
            $table->index('booking_id');
            $table->index('status');
            $table->index('payout_period');
            $table->index(['period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
