<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_number',
        'user_id',
        'category',
        'title',
        'amount',
        'expense_date',
        'note',
        'receipt_image',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getReceiptImageUrlAttribute(): ?string
    {
        if ($this->receipt_image) {
            return asset('storage/' . $this->receipt_image);
        }
        return null;
    }
}

