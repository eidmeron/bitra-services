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
        // Drop existing incomplete invoice tables
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        
        // Create proper invoices table
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique(); // Swedish format: INV-YYYY-NNNNNN
            $table->date('invoice_date');
            $table->date('due_date');
            $table->date('period_start'); // For weekly reports
            $table->date('period_end'); // For weekly reports
            $table->integer('week_number')->nullable();
            $table->integer('year')->nullable();
            
            // Invoice amounts
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(25.00); // Swedish VAT
            $table->decimal('vat_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            
            // Commission details
            $table->integer('total_bookings')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('total_commission', 12, 2)->default(0);
            $table->decimal('total_loyalty_points_used', 12, 2)->default(0);
            $table->decimal('total_loyalty_points_value', 12, 2)->default(0);
            $table->decimal('net_deposit', 12, 2)->default(0); // Amount company must pay
            
            // Status and payment
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable(); // Swedish payment reference
            $table->text('notes')->nullable();
            
            // Swedish payment details
            $table->string('bankgiro_number')->nullable(); // Our bankgiro
            $table->string('payment_reference_number')->nullable(); // Reference for payment
            
            $table->timestamps();
            
            $table->index('company_id');
            $table->index('invoice_number');
            $table->index('status');
            $table->index('due_date');
            $table->index(['week_number', 'year']);
        });
        
        // Create invoice items table
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->string('description');
            $table->text('details')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('loyalty_points_used', 10, 2)->default(0);
            $table->decimal('loyalty_points_value', 10, 2)->default(0);
            $table->timestamps();
            
            $table->index('invoice_id');
            $table->index('booking_id');
        });
        
        // Invoice settings table already exists from previous migration
        
        // Create loyalty point transactions table
        Schema::create('loyalty_point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['earned', 'spent', 'expired', 'adjusted', 'refunded'])->default('earned');
            $table->decimal('points', 10, 2);
            $table->decimal('value', 10, 2)->default(0); // Value in SEK
            $table->string('description');
            $table->text('notes')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('booking_id');
            $table->index('type');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_point_transactions');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};