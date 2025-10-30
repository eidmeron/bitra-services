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
        Schema::table('slot_times', function (Blueprint $table) {
            $table->decimal('price_multiplier', 5, 2)->default(1.00)->after('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slot_times', function (Blueprint $table) {
            $table->dropColumn('price_multiplier');
        });
    }
};