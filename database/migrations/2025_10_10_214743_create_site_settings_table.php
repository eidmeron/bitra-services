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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Setting key (e.g., 'site_name', 'logo', 'footer_text')
            $table->text('value')->nullable(); // Setting value
            $table->string('type')->default('text'); // text, textarea, image, url, json, boolean
            $table->string('group')->default('general'); // general, header, footer, seo, social, contact
            $table->string('label'); // Human-readable label
            $table->text('description')->nullable(); // Help text
            $table->integer('order')->default(0); // Display order
            $table->timestamps();
            
            $table->index('group');
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
