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
        Schema::create('weekly_payout_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->integer('week_number');
            $table->integer('year');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('total_bookings')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('total_commission', 12, 2)->default(0);
            $table->decimal('total_rot_deduction', 12, 2)->default(0);
            $table->decimal('net_payout', 12, 2)->default(0);
            $table->enum('status', ['pending', 'sent', 'paid'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'week_number', 'year']);
            $table->index('status');
            $table->index('invoice_number');
            $table->unique(['company_id', 'week_number', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_payout_reports');
    }
};