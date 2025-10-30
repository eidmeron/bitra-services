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
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_org_number');
            $table->text('company_address');
            $table->string('company_phone');
            $table->string('company_email');
            $table->string('bankgiro_number');
            $table->string('invoice_prefix')->default('INV');
            $table->integer('invoice_counter')->default(0);
            $table->integer('payment_due_days')->default(30);
            $table->text('invoice_footer_text')->nullable();
            $table->text('payment_instructions')->nullable();
            $table->boolean('auto_send_invoices')->default(true);
            $table->boolean('include_booking_details')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_settings');
    }
};
