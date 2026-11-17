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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('member_code', 50)->unique()->nullable()->after('id');
            $table->string('email', 100)->nullable()->after('phone');
            $table->text('address')->nullable()->after('email');
            $table->string('membership_tier', 30)->default('Silver')->after('address'); // Silver, Gold, Platinum
            $table->decimal('discount_percent', 5, 2)->default(5.00)->after('membership_tier');
            $table->integer('points')->default(0)->after('discount_percent');
            $table->boolean('is_active')->default(true)->after('points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'member_code',
                'email',
                'address',
                'membership_tier',
                'discount_percent',
                'points',
                'is_active',
            ]);
        });
    }
};

