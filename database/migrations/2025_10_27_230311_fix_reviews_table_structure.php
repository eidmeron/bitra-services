<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add missing company_status column
            if (!Schema::hasColumn('reviews', 'company_status')) {
                $table->enum('company_status', ['pending', 'approved', 'rejected'])->default('pending')->after('bitra_status');
            }
        });
        
        // Copy existing bitra_status to company_status for existing reviews
        DB::statement('UPDATE reviews SET company_status = bitra_status WHERE company_status IS NULL');
        
        // Drop the old status column
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add back status column
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('bitra_status');
        });
        
        // Copy company_status back to status
        DB::statement('UPDATE reviews SET status = company_status');
        
        // Drop company_status
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('company_status');
        });
    }
};