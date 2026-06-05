<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RoomCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // === Relationships ===
    public function roomTypes()
    {
        return $this->hasMany(RoomType::class, 'category_id');
    }

    // === Accessors ===
    public function getMinPriceAttribute()
    {
        return $this->roomTypes()->where('is_active', true)->min('price_per_year');
    }

    public function getAvailableRoomsCountAttribute(): int
    {
        return Room::whereIn('room_type_id', $this->roomTypes()->pluck('id'))
            ->where('status', 'available')
            ->count();
    }
}
