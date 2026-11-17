<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'purchase_price',
        'sell_price',
        'stock',
        'min_stock',
        'unit',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'sell_price' => 'decimal:2',
            'stock' => 'integer',
            'min_stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= ($this->min_stock ?? 5);
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}