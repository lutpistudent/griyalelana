<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'related_model',
        'related_id',
        'channel',
        'read_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    // === Relationships ===
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // === Helper Methods ===
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    // === Scopes ===
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
