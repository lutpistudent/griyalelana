<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'guardian_name',
        'guardian_phone',
        'guardian_relation',
        'two_factor_secret',
        'two_factor_enabled',
        'theme_preference',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
        ];
    }

    // === Filament Access ===
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'owner';
    }

    // === Relationships ===
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // === Helper Methods (Business Rules) ===
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isTenant(): bool
    {
        return $this->role === 'tenant';
    }

    public function hasPendingBooking(): bool
    {
        return $this->bookings()->where('status', 'pending')->exists();
    }

    public function hasActiveContract(): bool
    {
        return $this->contracts()->where('status', 'active')->exists();
    }

    public function canBookRoom(): bool
    {
        return !$this->hasPendingBooking()
            && !$this->hasActiveContract()
            && !$this->bookings()->where('status', 'approved')->exists();
    }

    public function activeContract()
    {
        return $this->contracts()->where('status', 'active')->first();
    }

    public function pendingBooking()
    {
        return $this->bookings()->where('status', 'pending')->first();
    }
}
