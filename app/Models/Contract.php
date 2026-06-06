<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_id',
        'user_id',
        'room_id',
        'contract_number',
        'start_date',
        'end_date',
        'duration_years',
        'total_amount',
        'payment_option',
        'contract_pdf_url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_amount' => 'decimal:2',
        ];
    }

    // === Relationships ===
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function paymentSchedules()
    {
        return $this->hasMany(PaymentSchedule::class)->orderBy('installment_number');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // === Helper Methods ===
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function daysRemaining(): int
    {
        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function nextPaymentDue()
    {
        return $this->paymentSchedules()
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('due_date')
            ->first();
    }

    // === Scopes ===
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('status', 'active')
            ->where('end_date', '<=', now()->addDays($days));
    }
}
