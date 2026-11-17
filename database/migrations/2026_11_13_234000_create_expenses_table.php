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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number', 50)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category', 100); // Pembelian Stok, Gaji Karyawan, Utilitas, Sewa Tempat, Operasional, Lainnya
            $table->string('title', 255);
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->text('note')->nullable();
            $table->string('receipt_image', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

