<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name'); // For guest reviews
            $table->string('email')->nullable(); // For guest reviews
            $table->unsignedTinyInteger('overall_rating'); // 1-5
            $table->unsignedTinyInteger('service_quality_rating')->nullable(); // 1-5
            $table->unsignedTinyInteger('price_rating')->nullable(); // 1-5
            $table->unsignedTinyInteger('speed_rating')->nullable(); // 1-5
            $table->unsignedTinyInteger('staff_rating')->nullable(); // 1-5
            $table->text('comment');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            $table->index(['status', 'overall_rating']);
            $table->index('is_featured');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_reviews');
    }
};
