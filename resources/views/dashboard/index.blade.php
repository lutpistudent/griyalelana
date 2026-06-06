@extends('layouts.app')

@section('title', 'Dashboard — Griya Lelana')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Flash messages --}}
    @if(session('success'))
        <div data-flash class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 flex items-center gap-3 animate-fade-in" style="transition: opacity 0.3s, transform 0.3s">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium flex-1">{{ session('success') }}</span>
            <button data-dismiss class="text-green-500 hover:text-green-700">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div data-flash class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 flex items-center gap-3 animate-fade-in" style="transition: opacity 0.3s, transform 0.3s">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium flex-1">{{ session('error') }}</span>
            <button data-dismiss class="text-red-500 hover:text-red-700">&times;</button>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-theme">Dashboard</h1>
            <p class="text-theme-secondary mt-1">Selamat datang, <span class="font-semibold text-accent">{{ $user->name }}</span></p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-outline text-sm px-4 py-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </div>

    {{-- === STATE: EMPTY — Fresh User === --}}
    @if($state === 'empty')
        <div class="card p-12 text-center shadow-theme">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-[var(--accent)]/10 flex items-center justify-center">
                <svg class="w-10 h-10 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-theme mb-3">Belum Ada Booking</h2>
            <p class="text-theme-secondary max-w-md mx-auto mb-8">Anda belum melakukan booking kamar. Mulai cari kamar yang sesuai untuk Anda!</p>
            <a href="{{ route('landing') }}#katalog" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Lihat Katalog Kamar
            </a>
        </div>

        {{-- Booking History --}}
        @if(isset($bookingHistory) && $bookingHistory->count() > 0)
        <div class="mt-8">
            <h3 class="font-bold text-theme mb-4">Riwayat Booking</h3>
            <div class="space-y-3">
                @foreach($bookingHistory as $bk)
                <div class="card p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-theme-muted flex items-center justify-center text-sm font-bold text-theme-secondary">
                            {{ $bk->room->room_number ?? '?' }}
                        </div>
                        <div>
                            <p class="font-semibold text-theme text-sm">{{ $bk->room->roomType->name ?? '-' }}</p>
                            <p class="text-xs text-theme-muted">{{ $bk->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full
                        {{ $bk->status === 'rejected' ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : '' }}
                        {{ $bk->status === 'cancelled' ? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' : '' }}
                        {{ $bk->status === 'expired' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                    ">
                        {{ $bk->status === 'rejected' ? 'Ditolak' : ($bk->status === 'cancelled' ? 'Dibatalkan' : 'Kedaluwarsa') }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    {{-- === STATE: PENDING BOOKING === --}}
    @elseif($state === 'pending')
        <div class="card shadow-theme overflow-hidden">
            <div class="bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800 px-6 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-amber-800 dark:text-amber-300">⏳ Menunggu Konfirmasi Owner</p>
                    <p class="text-sm text-amber-600 dark:text-amber-400">Kami akan menghubungi Anda segera setelah owner mengkonfirmasi</p>
                </div>
            </div>
            <div class="p-6">
                <h3 class="font-bold text-theme mb-4">Detail Booking</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-theme-muted/30">
                        <svg class="w-5 h-5 text-accent mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <div>
                            <p class="text-xs text-theme-muted font-medium uppercase tracking-wide">Kamar</p>
                            <p class="font-bold text-theme">{{ $pendingBooking->room->room_number }}</p>
                            <p class="text-sm text-theme-secondary">{{ $pendingBooking->room->roomType->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-theme-muted/30">
                        <svg class="w-5 h-5 text-accent mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div>
                            <p class="text-xs text-theme-muted font-medium uppercase tracking-wide">Check-in</p>
                            <p class="font-bold text-theme">{{ \Carbon\Carbon::parse($pendingBooking->check_in_date)->format('d M Y') }}</p>
                            <p class="text-sm text-theme-secondary">{{ $pendingBooking->duration_years }} tahun</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-theme-muted/30">
                        <svg class="w-5 h-5 text-accent mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <div>
                            <p class="text-xs text-theme-muted font-medium uppercase tracking-wide">Total</p>
                            <p class="font-bold text-theme">Rp {{ number_format($pendingBooking->total_amount, 0, ',', '.') }}</p>
                            <p class="text-sm text-theme-secondary">{{ $pendingBooking->payment_option === 'with_dp' ? 'DP 30%' : 'Direct Check-in' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-theme-muted/30">
                        <svg class="w-5 h-5 text-accent mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-xs text-theme-muted font-medium uppercase tracking-wide">Diajukan</p>
                            <p class="font-bold text-theme">{{ $pendingBooking->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-theme">
                    <form method="POST" action="{{ route('booking.cancel', $pendingBooking->id) }}" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-outline text-sm px-5 py-2.5 text-red-500 border-red-300 hover:bg-red-50 dark:hover:bg-red-900/20">
                            Batalkan Booking
                        </button>
                    </form>
                </div>
            </div>
        </div>

    {{-- === STATE: APPROVED (waiting DP) === --}}
    @elseif($state === 'approved')
        <div class="card shadow-theme overflow-hidden">
            <div class="bg-green-50 dark:bg-green-900/20 border-b border-green-200 dark:border-green-800 px-6 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-green-800 dark:text-green-300">✅ Booking Disetujui!</p>
                    <p class="text-sm text-green-600 dark:text-green-400">
                        @if($approvedBooking->payment_option === 'with_dp' && $approvedBooking->dp_expires_at)
                            Silakan lakukan pembayaran DP sebelum
                            <strong>{{ \Carbon\Carbon::parse($approvedBooking->dp_expires_at)->format('d M Y H:i') }}</strong>
                        @else
                            Silakan hubungi owner untuk jadwal check-in
                        @endif
                    </p>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="p-4 rounded-xl bg-theme-muted/30">
                        <p class="text-xs text-theme-muted font-medium uppercase tracking-wide mb-1">Kamar</p>
                        <p class="font-bold text-theme">{{ $approvedBooking->room->room_number }} — {{ $approvedBooking->room->roomType->name }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-theme-muted/30">
                        <p class="text-xs text-theme-muted font-medium uppercase tracking-wide mb-1">DP yang harus dibayar</p>
                        <p class="font-bold text-accent text-lg">Rp {{ number_format($approvedBooking->dp_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($approvedBooking->payment_option === 'with_dp' && $approvedBooking->dp_expires_at)
                    {{-- Countdown timer --}}
                    <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 mb-6">
                        <p class="text-sm text-amber-700 dark:text-amber-400 font-medium mb-2">⏰ Sisa waktu pembayaran DP:</p>
                        <div id="dp-countdown" class="text-2xl font-extrabold text-amber-800 dark:text-amber-300 font-mono" data-expires="{{ $approvedBooking->dp_expires_at->toIso8601String() }}">
                            Menghitung...
                        </div>
                    </div>
                    <script>
                    (function() {
                        const el = document.getElementById('dp-countdown');
                        const expires = new Date(el.dataset.expires);
                        function update() {
                            const now = new Date();
                            const diff = expires - now;
                            if (diff <= 0) { el.textContent = 'Waktu habis!'; el.classList.add('text-red-600'); return; }
                            const h = Math.floor(diff / 3600000);
                            const m = Math.floor((diff % 3600000) / 60000);
                            const s = Math.floor((diff % 60000) / 1000);
                            el.textContent = `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
                            requestAnimationFrame(update);
                        }
                        update();
                    })();
                    </script>
                @endif

                @if($approvedBooking->payment_option === 'with_dp' && ! $approvedBooking->isExpired())
                    <form method="POST" action="{{ route('payments.booking-dp', $approvedBooking) }}">
                        @csrf
                        <button type="submit" class="btn-primary w-full sm:w-auto">
                            Bayar DP Sekarang
                        </button>
                    </form>
                    <p class="text-xs text-theme-muted mt-2">Anda akan diarahkan ke halaman pembayaran Xendit.</p>
                @else
                    <a href="{{ route('landing') }}#kontak" class="btn-primary inline-flex w-full sm:w-auto justify-center">
                        Hubungi Owner
                    </a>
                @endif
            </div>
        </div>

    {{-- === STATE: ACTIVE CONTRACT === --}}
    @elseif($state === 'contract')
        <div class="space-y-6">
            {{-- Summary Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 stagger">
                <div class="card-static stat-card p-4 text-center shadow-theme animate-fade-in-up">
                    <p class="text-xs text-theme-muted font-medium uppercase tracking-wide mb-1">Kamar</p>
                    <p class="text-xl font-extrabold text-accent">{{ $activeContract->room->room_number }}</p>
                </div>
                <div class="card-static stat-card p-4 text-center shadow-theme animate-fade-in-up">
                    <p class="text-xs text-theme-muted font-medium uppercase tracking-wide mb-1">Sisa Hari</p>
                    <p class="text-xl font-extrabold text-theme">{{ $activeContract->daysRemaining() }}</p>
                </div>
                <div class="card-static stat-card p-4 text-center shadow-theme animate-fade-in-up">
                    <p class="text-xs text-theme-muted font-medium uppercase tracking-wide mb-1">Terbayar</p>
                    <p class="text-xl font-extrabold text-green-600">{{ $paidSchedules }}/{{ $totalSchedules }}</p>
                </div>
                <div class="card-static stat-card p-4 text-center shadow-theme animate-fade-in-up">
                    <p class="text-xs text-theme-muted font-medium uppercase tracking-wide mb-1">Total Bayar</p>
                    <p class="text-lg font-extrabold text-theme">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Next Payment Alert --}}
            @if($nextPayment)
            <div class="card shadow-theme overflow-hidden">
                <div class="bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800 px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="font-bold text-amber-800 dark:text-amber-300 text-sm">Tagihan Berikutnya</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $nextPayment->isOverdue() ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-700' }}">
                        {{ $nextPayment->isOverdue() ? 'Terlambat!' : 'Jatuh Tempo ' . $nextPayment->due_date->format('d M Y') }}
                    </span>
                </div>
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-theme-secondary">{{ ucwords(str_replace('_', ' ', $nextPayment->installment_type)) }}</p>
                        <p class="text-2xl font-extrabold text-accent">Rp {{ number_format($nextPayment->amount, 0, ',', '.') }}</p>
                    </div>
                    <form method="POST" action="{{ route('payments.schedule', $nextPayment) }}">
                        @csrf
                        <button type="submit" class="btn-primary text-sm">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Contract Info --}}
            <div class="card shadow-theme p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-theme flex items-center gap-2">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Kontrak Aktif
                    </h3>
                    @if($activeContract->contract_pdf_url)
                    <a href="{{ route('dashboard.contract.download') }}" class="btn-outline text-sm px-4 py-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Unduh PDF
                    </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="p-4 rounded-xl bg-theme-muted/30">
                        <p class="text-xs text-theme-muted uppercase tracking-wide mb-1">No. Kontrak</p>
                        <p class="font-bold text-theme">{{ $activeContract->contract_number }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-theme-muted/30">
                        <p class="text-xs text-theme-muted uppercase tracking-wide mb-1">Tipe Kamar</p>
                        <p class="font-bold text-theme">{{ $activeContract->room->roomType->name }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-theme-muted/30">
                        <p class="text-xs text-theme-muted uppercase tracking-wide mb-1">Masa Sewa</p>
                        <p class="font-semibold text-theme">{{ $activeContract->start_date->format('d M Y') }} — {{ $activeContract->end_date->format('d M Y') }}</p>
                        <p class="text-xs text-theme-secondary">{{ $activeContract->duration_years }} tahun</p>
                    </div>
                    <div class="p-4 rounded-xl bg-theme-muted/30">
                        <p class="text-xs text-theme-muted uppercase tracking-wide mb-1">Total Kontrak</p>
                        <p class="font-bold text-accent text-lg">Rp {{ number_format($activeContract->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Payment Schedule Table --}}
            <div class="card shadow-theme p-6">
                <h3 class="font-bold text-theme mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Jadwal Pembayaran
                </h3>
                <div class="overflow-x-auto -mx-6 px-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-theme">
                                <th class="text-left py-3 px-2 text-theme-muted font-semibold text-xs uppercase tracking-wide">#</th>
                                <th class="text-left py-3 px-2 text-theme-muted font-semibold text-xs uppercase tracking-wide">Jenis</th>
                                <th class="text-right py-3 px-2 text-theme-muted font-semibold text-xs uppercase tracking-wide">Jumlah</th>
                                <th class="text-left py-3 px-2 text-theme-muted font-semibold text-xs uppercase tracking-wide">Jatuh Tempo</th>
                                <th class="text-center py-3 px-2 text-theme-muted font-semibold text-xs uppercase tracking-wide">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeContract->paymentSchedules as $schedule)
                            <tr class="border-b border-theme/50 {{ $schedule->isOverdue() ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                <td class="py-3 px-2 font-medium text-theme-secondary">{{ $schedule->installment_number }}</td>
                                <td class="py-3 px-2 text-theme">{{ ucwords(str_replace('_', ' ', $schedule->installment_type)) }}</td>
                                <td class="py-3 px-2 text-right font-semibold text-theme">Rp {{ number_format($schedule->amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-2 text-theme-secondary">{{ $schedule->due_date->format('d M Y') }}</td>
                                <td class="py-3 px-2 text-center">
                                    @if($schedule->status === 'paid')
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400">
                                            ✓ Lunas
                                        </span>
                                    @elseif($schedule->isOverdue())
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400">
                                            ! Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400">
                                            Menunggu
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-theme p-6">
                <h3 class="font-bold text-theme mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Aksi Cepat
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('complaints.index') }}" class="flex items-center gap-3 p-4 rounded-xl bg-theme-muted/30 hover:bg-theme-muted/60 transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-theme text-sm group-hover:text-accent transition-colors">Keluhan</p>
                            <p class="text-xs text-theme-muted">Sampaikan masalah</p>
                        </div>
                    </a>
                    @if($activeContract->contract_pdf_url)
                    <a href="{{ route('dashboard.contract.download') }}" class="flex items-center gap-3 p-4 rounded-xl bg-theme-muted/30 hover:bg-theme-muted/60 transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-theme text-sm group-hover:text-accent transition-colors">Unduh Kontrak</p>
                            <p class="text-xs text-theme-muted">File PDF</p>
                        </div>
                    </a>
                    @endif
                    <a href="{{ route('landing') }}#kontak" class="flex items-center gap-3 p-4 rounded-xl bg-theme-muted/30 hover:bg-theme-muted/60 transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-theme text-sm group-hover:text-accent transition-colors">Hubungi Owner</p>
                            <p class="text-xs text-theme-muted">Via WhatsApp</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
