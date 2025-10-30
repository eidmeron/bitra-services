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
        Schema::table('services', function (Blueprint $table) {
            // Add subscription multipliers if they don't exist
            if (!Schema::hasColumn('services', 'weekly_multiplier')) {
                $table->decimal('weekly_multiplier', 5, 2)->default(1.00)->after('daily_multiplier');
            }
            
            if (!Schema::hasColumn('services', 'biweekly_multiplier')) {
                $table->decimal('biweekly_multiplier', 5, 2)->default(0.95)->after('weekly_multiplier');
            }
            
            if (!Schema::hasColumn('services', 'monthly_multiplier')) {
                $table->decimal('monthly_multiplier', 5, 2)->default(0.90)->after('biweekly_multiplier');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['weekly_multiplier', 'biweekly_multiplier', 'monthly_multiplier']);
        });
    }
};