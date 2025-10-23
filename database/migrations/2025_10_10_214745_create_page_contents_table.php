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
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique(); // Unique identifier (e.g., 'homepage', 'about', 'how_it_works')
            $table->string('page_name'); // Human-readable name
            $table->string('page_type')->default('static'); // static, dynamic, landing
            
            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            
            // Content Fields
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_cta_text')->nullable();
            $table->string('hero_cta_link')->nullable();
            
            // JSON Fields for flexible content blocks
            $table->json('sections')->nullable(); // Array of content sections
            $table->json('features')->nullable(); // Features/benefits list
            $table->json('faqs')->nullable(); // Frequently asked questions
            $table->json('testimonials')->nullable(); // Customer testimonials
            $table->json('schema_markup')->nullable(); // Structured data for SEO
            
            // Additional Settings
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index('page_key');
            $table->index('page_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};
