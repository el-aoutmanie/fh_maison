<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookedMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'status', 'date', 'time',
        'first_name', 'last_name', 'phone_number', 'email', 'message'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
