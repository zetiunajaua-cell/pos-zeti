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
            $table->foreignId('payment_method_id')->nullable()->after('payment_method')->constrained('payment_methods')->nullOnDelete();
            $table->string('order_type', 20)->default('dine_in')->after('status'); // dine_in, take_away
            $table->text('note')->nullable()->after('order_type');
            
            // Void audit columns
            $table->text('void_reason')->nullable()->after('note');
            $table->foreignId('voided_by')->nullable()->after('void_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('voided_at')->nullable()->after('voided_by');

            // Refund audit columns
            $table->text('refund_reason')->nullable()->after('voided_at');
            $table->decimal('refund_amount', 12, 2)->nullable()->after('refund_reason');
            $table->foreignId('refunded_by')->nullable()->after('refund_amount')->constrained('users')->nullOnDelete();
            $table->timestamp('refunded_at')->nullable()->after('refunded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropForeign(['voided_by']);
            $table->dropForeign(['refunded_by']);
            $table->dropColumn([
                'payment_method_id',
                'order_type',
                'note',
                'void_reason',
                'voided_by',
                'voided_at',
                'refund_reason',
                'refund_amount',
                'refunded_by',
                'refunded_at',
            ]);
        });
    }
};

