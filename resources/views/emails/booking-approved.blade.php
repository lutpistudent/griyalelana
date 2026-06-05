@extends('emails.layout')

@section('title', 'Booking Disetujui')

@section('body')
<h2>Booking Anda Disetujui! 🎉</h2>
<p class="subtitle">Halo {{ $user->name }}, booking kamar Anda telah dikonfirmasi oleh pengelola.</p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label">Kamar</span>
        <span class="info-value">{{ $room->room_number }}</span>
    </div>
    @if($roomType)
    <div class="info-row">
        <span class="info-label">Tipe</span>
        <span class="info-value">{{ $roomType->name }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Harga/Tahun</span>
        <span class="info-value">Rp {{ number_format($roomType->price_per_year, 0, ',', '.') }}</span>
    </div>
    @endif
    <div class="info-row">
        <span class="info-label">Check-in</span>
        <span class="info-value">{{ $booking->check_in_date->format('d M Y') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value"><span class="badge badge-success">Disetujui</span></span>
    </div>
    @if($booking->dp_expires_at)
    <div class="info-row">
        <span class="info-label">Batas Bayar DP</span>
        <span class="info-value" style="color: #e74c3c;">{{ $booking->dp_expires_at->format('d M Y H:i') }}</span>
    </div>
    @endif
</div>

<p style="font-size: 14px; color: #5a4d3e; margin-top: 16px;">
    Silakan segera lakukan pembayaran DP sebelum batas waktu yang ditentukan agar booking Anda tidak expired.
</p>

<div style="text-align: center; margin-top: 24px;">
    <a href="{{ url('/dashboard') }}" class="btn">Lihat Dashboard</a>
</div>
@endsection
