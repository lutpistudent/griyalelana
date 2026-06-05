<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_schedule_id',
        'user_id',
        'contract_id',
        'amount',
        'payment_method',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'status',
        'paid_at',
        'receipt_url',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    // === Relationships ===
    public function paymentSchedule()
    {
        return $this->belongsTo(PaymentSchedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    // === Scopes ===
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }
}
