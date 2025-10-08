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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('form_name');
            $table->string('form_slug')->unique();
            $table->json('form_schema'); // Stores form fields configuration
            $table->text('success_message')->default('Tack för din bokning!');
            $table->boolean('redirect_after_submit')->default(false);
            $table->string('redirect_url')->nullable();
            $table->json('custom_styles')->nullable(); // Custom CSS/styling
            $table->string('shortcode')->unique(); // WordPress shortcode
            $table->string('public_token')->unique(); // For public link access
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};

