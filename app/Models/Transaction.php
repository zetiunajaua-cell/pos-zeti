<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'queue_number',
        'user_id',
        'customer_id',
        'customer_name',
        'total_amount',
        'discount',
        'tax',
        'service_charge',
        'paid_amount',
        'change_amount',
        'payment_method',
        'payment_method_id',
        'promotion_id',
        'points_earned',
        'order_type',
        'note',
        'status',
        'void_reason',
        'voided_by',
        'voided_at',
        'refund_reason',
        'refund_amount',
        'refunded_by',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'voided_at' => 'datetime',
            'refunded_at' => 'datetime',
            'refund_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'service_charge' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    public function refundedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Format Nomor Antrean Pesanan Harian (e.g. #01, #02)
     */
    public function getFormattedQueueNumberAttribute(): string
    {
        $num = $this->queue_number;
        if (!$num) {
            // Fallback dari nomor urut invoice jika data lama
            if (preg_match('/-(\d+)$/', $this->invoice_number, $matches)) {
                $num = (int)$matches[1];
            } else {
                $num = $this->id;
            }
        }
        return '#' . str_pad($num, 2, '0', STR_PAD_LEFT);
    }
}