<?php

namespace App\Mail;

use App\Models\PaymentSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PaymentSchedule $schedule,
        public string $type = 'reminder' // 'reminder' or 'overdue'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->type === 'overdue'
            ? 'Pembayaran Terlambat — Griya Lelana'
            : 'Pengingat Pembayaran — Griya Lelana';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-reminder',
            with: [
                'schedule' => $this->schedule,
                'contract' => $this->schedule->contract,
                'type' => $this->type,
            ],
        );
    }
}
