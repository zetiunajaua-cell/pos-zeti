<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_purchase' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Cek apakah promo valid untuk subtotal tertentu
     */
    public function isValidFor(float $subtotal): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->start_date && today()->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && today()->gt($this->end_date)) {
            return false;
        }

        if ($subtotal < (float)$this->min_purchase) {
            return false;
        }

        return true;
    }

    /**
     * Hitung nominal potongan diskon
     */
    public function calculateDiscount(float $subtotal): float
    {
        if (!$this->isValidFor($subtotal)) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = $subtotal * ((float)$this->value / 100);
            if ($this->max_discount && $this->max_discount > 0) {
                $discount = min($discount, (float)$this->max_discount);
            }
            return round($discount);
        }

        // Fixed / Nominal
        return min((float)$this->value, $subtotal);
    }
}

