<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'status', 'coupon_id', 'customer_id',
        'paid_at', 'total_amount', 'subtotal', 'discount_amount', 'discount_percentage', 'shipping_amount',
        'shipping_name', 'shipping_address', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country', 'shipping_phone',
        'payment_method', 'payment_status', 'tracking_number', 'notes'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (!$order->code) {
                $order->code = 'ORD-' . strtoupper(uniqid());
            }
        });
    }
}
