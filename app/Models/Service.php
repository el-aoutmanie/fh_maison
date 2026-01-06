<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['title', 'slug', 'description', 'price', 'duration', 'features', 'is_active'];

    protected $casts = [
        'title' => 'array',
        'slug' => 'array',
        'description' => 'array',
        'features' => 'array',
        'price' => 'decimal:2',
        'duration' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bookedMeetings()
    {
        return $this->hasMany(BookedMeeting::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
