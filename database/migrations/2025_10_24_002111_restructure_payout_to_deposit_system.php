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
        // Drop old payout tables
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('weekly_payout_reports');
        
        // Create deposits table (replaces payouts)
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('booking_amount', 10, 2); // Total booking amount
            $table->decimal('commission_amount', 10, 2); // Admin commission
            $table->decimal('commission_rate', 5, 2); // Rate applied
            $table->decimal('loyalty_points_used', 10, 2)->default(0); // Loyalty points used
            $table->decimal('loyalty_points_value', 10, 2)->default(0); // Value of loyalty points
            $table->decimal('deposit_amount', 10, 2); // Amount company must pay (commission - loyalty points)
            $table->enum('status', ['pending', 'sent', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('sent_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('company_id');
            $table->index('booking_id');
            $table->index('status');
            $table->index('invoice_number');
            $table->index('due_date');
        });
        
        // Create weekly commission reports table
        Schema::create('weekly_commission_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->integer('week_number');
            $table->integer('year');
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('total_bookings')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('total_commission', 12, 2)->default(0);
            $table->decimal('total_loyalty_points_used', 12, 2)->default(0);
            $table->decimal('total_loyalty_points_value', 12, 2)->default(0);
            $table->decimal('net_deposit', 12, 2)->default(0); // Commission - loyalty points
            $table->enum('status', ['pending', 'sent', 'paid'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'week_number', 'year']);
            $table->index('status');
            $table->index('invoice_number');
            $table->unique(['company_id', 'week_number', 'year']);
        });
        
        // Add loyalty points fields to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('loyalty_points_used', 10, 2)->default(0)->after('discount_amount');
            $table->decimal('loyalty_points_value', 10, 2)->default(0)->after('loyalty_points_used');
            $table->decimal('commission_amount', 10, 2)->default(0)->after('loyalty_points_value');
            $table->decimal('commission_rate', 5, 2)->default(0)->after('commission_amount');
        });
        
        // Update companies table - rename payout fields to deposit fields
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('payout_method', 'deposit_method');
            $table->renameColumn('payout_notes', 'deposit_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new tables
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('weekly_commission_reports');
        
        // Remove loyalty points fields from bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['loyalty_points_used', 'loyalty_points_value', 'commission_amount', 'commission_rate']);
        });
        
        // Revert companies table changes
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('deposit_method', 'payout_method');
            $table->renameColumn('deposit_notes', 'payout_notes');
        });
        
        // Recreate old tables (simplified)
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->string('payout_period')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->decimal('booking_amount', 10, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('payout_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
        
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
        });
    }
};