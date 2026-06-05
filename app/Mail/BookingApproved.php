<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Anda Telah Disetujui — Griya Lelana',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-approved',
            with: [
                'booking' => $this->booking,
                'user' => $this->booking->user,
                'room' => $this->booking->room,
                'roomType' => $this->booking->room->roomType ?? null,
            ],
        );
    }
}
