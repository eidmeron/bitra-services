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
        Schema::table('reviews', function (Blueprint $table) {
            // Add review type to distinguish between Bitra and Company reviews
            $table->enum('review_type', ['bitra', 'company'])->default('company')->after('service_id');
            
            // Add separate fields for Bitra review
            $table->integer('bitra_rating')->nullable()->after('rating');
            $table->text('bitra_review_text')->nullable()->after('review_text');
            $table->enum('bitra_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            
            // Add company-specific fields (keeping original as company fields)
            $table->integer('company_rating')->nullable()->after('bitra_rating');
            $table->text('company_review_text')->nullable()->after('bitra_review_text');
            $table->enum('company_status', ['pending', 'approved', 'rejected'])->default('pending')->after('bitra_status');
        });
        
        // Copy existing data to company fields
        DB::statement('UPDATE reviews SET company_rating = rating, company_review_text = review_text, company_status = status');
        
        // Drop original columns
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['rating', 'review_text', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add back original columns
            $table->integer('rating')->after('service_id');
            $table->text('review_text')->nullable()->after('rating');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('review_text');
        });
        
        // Copy company data back to original columns
        DB::statement('UPDATE reviews SET rating = company_rating, review_text = company_review_text, status = company_status');
        
        // Drop new columns
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn([
                'review_type', 
                'bitra_rating', 
                'bitra_review_text', 
                'bitra_status',
                'company_rating',
                'company_review_text', 
                'company_status'
            ]);
        });
    }
};