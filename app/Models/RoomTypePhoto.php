<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTypePhoto extends Model
{
    protected $fillable = [
        'room_type_id',
        'photo_url',
        'cloudinary_public_id',
        'caption',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
