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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->string('field_type'); // text, email, phone, textarea, file, date, time, url, number, select, radio, checkbox, slider, step, divider, container
            $table->string('field_label');
            $table->string('field_name');
            $table->string('placeholder_text')->nullable();
            $table->text('help_text')->nullable();
            $table->enum('field_width', ['25', '33', '50', '100'])->default('100');
            $table->boolean('required')->default(false);
            $table->integer('sort_order')->default(0);
            
            // Pricing for field values
            $table->json('pricing_rules')->nullable(); // For number fields, select options, etc.
            
            // Conditional logic
            $table->json('conditional_logic')->nullable(); // {show_if: {field: 'id', value: 'x'}}
            
            // Field options (for select, radio, checkbox)
            $table->json('field_options')->nullable();
            
            // Step/Container config
            $table->string('parent_id')->nullable(); // For nested fields in containers
            $table->integer('step_number')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};

