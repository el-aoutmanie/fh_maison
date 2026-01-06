<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name', 'sku', 'price', 'compare_at_price', 'quantity', 'stock', 'discount_amount', 'image_url', 'is_active'];

    protected $casts = [
        'name' => 'array',
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function options()
    {
        return $this->hasMany(VariantOption::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }
}
