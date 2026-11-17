<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_code',
        'name',
        'phone',
        'email',
        'address',
        'membership_tier',
        'discount_percent',
        'points',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_percent' => 'decimal:2',
            'points' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Hitung poin yang didapat dari total transaksi (misal: 1 poin per Rp 10.000)
     */
    public function calculatePointsEarned(float $totalAmount): int
    {
        return (int) floor($totalAmount / 10000);
    }
}
