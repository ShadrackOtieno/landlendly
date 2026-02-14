<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'type',
        'bedrooms',
        'bathrooms',
        'size',
        'price',
        'address',
        'city',
        'county',
        'latitude',
        'longitude',
        'status',
        'amenities',
        'images',
        'is_featured',
        'views'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'size' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'amenities' => 'array',
        'images' => 'array',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->title) . '-' . Str::random(6);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inquiries()
    {
        return $this->hasMany(PropertyInquiry::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
