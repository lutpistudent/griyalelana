<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'check_in_date',
        'duration_years',
        'payment_option',
        'total_amount',
        'dp_amount',
        'identity_type',
        'identity_number',
        'emergency_contact',
        'status',
        'dp_expires_at',
        'approved_at',
        'rejected_reason',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'total_amount' => 'decimal:2',
            'dp_amount' => 'decimal:2',
            'dp_expires_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    // === Relationships ===
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    // === Helper Methods ===
    public function isExpired(): bool
    {
        return $this->status === 'expired'
            || ($this->status === 'approved'
                && $this->dp_expires_at
                && now()->greaterThan($this->dp_expires_at));
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    // === Scopes ===
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeExpiredDp($query)
    {
        return $query->where('status', 'approved')
            ->whereNotNull('dp_expires_at')
            ->where('dp_expires_at', '<', now());
    }
}
