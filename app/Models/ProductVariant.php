<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'additional_price',
        'stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'additional_price' => 'decimal:2',
            'is_active' => 'boolean',
            'stock' => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

