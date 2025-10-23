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
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_type'); // 'service', 'category', 'city', 'zone', 'city_service', 'category_service'
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('slug')->unique();
            
            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            
            // Page Content
            $table->string('h1_title')->nullable();
            $table->text('hero_text')->nullable();
            $table->longText('content')->nullable();
            $table->longText('features')->nullable(); // JSON
            $table->longText('faq')->nullable(); // JSON
            
            // Schema.org JSON-LD
            $table->longText('schema_markup')->nullable(); // JSON
            
            // Settings
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index(['page_type', 'is_active']);
            $table->index(['city_id', 'service_id']);
            $table->index(['category_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
    }
};
