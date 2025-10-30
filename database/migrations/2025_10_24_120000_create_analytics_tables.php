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
        // Analytics tracking table
        Schema::create('analytics_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('user_id')->nullable();
            $table->string('page_url');
            $table->string('page_title');
            $table->string('referrer')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('device_type'); // desktop, mobile, tablet
            $table->string('browser');
            $table->string('os');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('ip_address');
            $table->integer('page_load_time')->nullable(); // in milliseconds
            $table->integer('bounce_rate')->default(0); // 0 or 1
            $table->integer('conversion')->default(0); // 0 or 1
            $table->json('custom_events')->nullable();
            $table->timestamps();
            
            $table->index(['session_id', 'created_at']);
            $table->index(['page_url', 'created_at']);
            $table->index(['utm_source', 'created_at']);
            $table->index(['device_type', 'created_at']);
            $table->index(['country', 'created_at']);
        });

        // SEO keywords tracking
        Schema::create('seo_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->string('page_url');
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->decimal('ctr', 5, 2)->default(0); // Click-through rate
            $table->integer('position')->nullable(); // Average position in search results
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->integer('conversions')->default(0);
            $table->timestamps();
            
            $table->unique(['keyword', 'page_url']);
            $table->index(['keyword', 'created_at']);
            $table->index(['page_url', 'created_at']);
        });

        // Page performance tracking
        Schema::create('page_performance', function (Blueprint $table) {
            $table->id();
            $table->string('page_url');
            $table->string('page_title');
            $table->integer('avg_load_time')->default(0);
            $table->integer('total_visits')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->decimal('bounce_rate', 5, 2)->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->integer('total_conversions')->default(0);
            $table->json('top_keywords')->nullable();
            $table->json('traffic_sources')->nullable();
            $table->timestamps();
            
            $table->unique('page_url');
            $table->index(['created_at']);
        });

        // Visitor demographics
        Schema::create('visitor_demographics', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('country');
            $table->string('city')->nullable();
            $table->string('device_type');
            $table->string('browser');
            $table->string('os');
            $table->integer('visitors')->default(0);
            $table->integer('sessions')->default(0);
            $table->integer('page_views')->default(0);
            $table->decimal('avg_session_duration', 8, 2)->default(0);
            $table->decimal('bounce_rate', 5, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['date', 'country', 'city', 'device_type', 'browser', 'os'], 'visitor_demo_unique');
            $table->index(['date', 'country']);
            $table->index(['date', 'device_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_demographics');
        Schema::dropIfExists('page_performance');
        Schema::dropIfExists('seo_keywords');
        Schema::dropIfExists('analytics_tracking');
    }
};
