<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RoomType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price_per_year',
        'has_ac',
        'bathroom_type',
        'bed_size',
        'room_size',
        'facilities',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_per_year' => 'decimal:2',
            'room_size' => 'decimal:2',
            'has_ac' => 'boolean',
            'is_active' => 'boolean',
            'facilities' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($type) {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
        });
    }

    // === Relationships ===
    public function category()
    {
        return $this->belongsTo(RoomCategory::class, 'category_id');
    }

    public function photos()
    {
        return $this->hasMany(RoomTypePhoto::class)->orderBy('sort_order');
    }

    public function primaryPhoto()
    {
        return $this->hasOne(RoomTypePhoto::class)->where('is_primary', true);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // === Accessors ===
    public function getAvailableRoomsCountAttribute(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    public function getTotalRoomsCountAttribute(): int
    {
        return $this->rooms()->count();
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price_per_year, 0, ',', '.');
    }
}
