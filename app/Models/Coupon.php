<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'start_date', 'end_date', 'discount_percentage', 'is_active'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now());
    }

    public function isValid()
    {
        return $this->is_active && 
               $this->start_date <= Carbon::now() && 
               $this->end_date >= Carbon::now();
    }
}
