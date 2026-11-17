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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('promotion_id')->nullable()->after('customer_id')->constrained('promotions')->nullOnDelete();
            $table->integer('points_earned')->default(0)->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['promotion_id']);
            $table->dropColumn(['promotion_id', 'points_earned']);
        });
    }
};

