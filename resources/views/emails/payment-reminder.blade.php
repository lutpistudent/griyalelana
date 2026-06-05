@extends('emails.layout')

@section('title', $type === 'overdue' ? 'Pembayaran Terlambat' : 'Pengingat Pembayaran')

@section('body')
@if($type === 'overdue')
<h2>Pembayaran Terlambat ⚠️</h2>
<p class="subtitle">Pembayaran Anda telah melewati jatuh tempo. Segera lakukan pembayaran untuk menghindari denda.</p>
@else
<h2>Pengingat Pembayaran 📅</h2>
<p class="subtitle">Pembayaran Anda akan jatuh tempo dalam beberapa hari. Jangan sampai terlewat!</p>
@endif

<div class="info-box">
    <div class="info-row">
        <span class="info-label">Jenis</span>
        <span class="info-value">{{ ucwords(str_replace('_', ' ', $schedule->installment_type)) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Jumlah</span>
        <span class="info-value">Rp {{ number_format($schedule->amount, 0, ',', '.') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Jatuh Tempo</span>
        <span class="info-value" style="{{ $type === 'overdue' ? 'color: #e74c3c;' : '' }}">{{ $schedule->due_date->format('d M Y') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value">
            <span class="badge {{ $type === 'overdue' ? 'badge-danger' : 'badge-warning' }}">
                {{ $type === 'overdue' ? 'Terlambat' : 'Menunggu' }}
            </span>
        </span>
    </div>
</div>

<div style="text-align: center; margin-top: 24px;">
    <a href="{{ url('/dashboard') }}" class="btn {{ $type === 'overdue' ? 'btn-danger' : '' }}">
        {{ $type === 'overdue' ? 'Bayar Sekarang' : 'Lihat Dashboard' }}
    </a>
</div>
@endsection
