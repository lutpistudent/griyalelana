<?php

namespace App\Mail;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContractExpiring extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contract $contract) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kontrak Anda Segera Berakhir — Griya Lelana',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contract-expiring',
            with: [
                'contract' => $this->contract,
                'user' => $this->contract->user,
                'room' => $this->contract->room,
                'daysLeft' => now()->diffInDays($this->contract->end_date),
            ],
        );
    }
}
