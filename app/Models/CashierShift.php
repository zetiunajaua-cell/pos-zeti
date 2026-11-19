<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashierShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch',
        'starting_cash',
        'expected_cash',
        'actual_cash',
        'difference',
        'total_cash_sales',
        'total_non_cash_sales',
        'total_transactions',
        'notes',
        'status',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'starting_cash' => 'float',
        'expected_cash' => 'float',
        'actual_cash' => 'float',
        'difference' => 'float',
        'total_cash_sales' => 'float',
        'total_non_cash_sales' => 'float',
        'total_transactions' => 'integer',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk shift yang sedang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Hitung ringkasan penjualan saat shift ini berlangsung
     */
    public function calculateSalesSummary(): array
    {
        $startTime = $this->opened_at;
        $endTime = $this->closed_at ?? now();

        $transactions = Transaction::where('user_id', $this->user_id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startTime, $endTime])
            ->get();

        $cashSales = 0;
        $nonCashSales = 0;
        $totalTransactions = $transactions->count();

        foreach ($transactions as $trx) {
            $pm = strtolower($trx->paymentMethod->type ?? ($trx->paymentMethod->name ?? ($trx->payment_method ?? 'cash')));
            if (str_contains($pm, 'cash') || str_contains($pm, 'tunai')) {
                $cashSales += (float)$trx->total_amount;
            } else {
                $nonCashSales += (float)$trx->total_amount;
            }
        }

        $expectedCash = (float)$this->starting_cash + $cashSales;

        return [
            'transactions_count' => $totalTransactions,
            'cash_sales' => $cashSales,
            'non_cash_sales' => $nonCashSales,
            'expected_cash' => $expectedCash,
        ];
    }
}
