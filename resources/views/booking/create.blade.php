@extends('layouts.app')

@section('title', 'Booking Kamar ' . $room->room_number . ' — Griya Lelana')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-theme-secondary mb-6">
        <a href="{{ route('landing') }}" class="hover:text-accent transition-colors">Beranda</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span>Booking</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-accent font-medium">{{ $room->room_number }}</span>
    </nav>

    <h1 class="text-2xl md:text-3xl font-extrabold text-theme mb-2">Booking Kamar</h1>
    <p class="text-theme-secondary mb-8">Lengkapi formulir berikut untuk mengajukan booking</p>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('booking.store') }}">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->id }}">

        <div class="space-y-6">

            {{-- Room Summary Card --}}
            <div class="card shadow-theme p-6">
                <h3 class="font-bold text-theme mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Kamar yang Dipilih
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-theme-muted/30 text-center">
                        <p class="text-xs text-theme-muted uppercase tracking-wide mb-1">No. Kamar</p>
                        <p class="text-xl font-extrabold text-accent">{{ $room->room_number }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-theme-muted/30 text-center">
                        <p class="text-xs text-theme-muted uppercase tracking-wide mb-1">Tipe / Lantai</p>
                        <p class="font-bold text-theme">{{ $room->roomType->name }}</p>
                        <p class="text-sm text-theme-secondary">Lantai {{ $room->floor }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-theme-muted/30 text-center">
                        <p class="text-xs text-theme-muted uppercase tracking-wide mb-1">Harga / Tahun</p>
                        <p class="text-xl font-extrabold text-accent">Rp {{ number_format($room->roomType->price_per_year, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Booking Details --}}
            <div class="card shadow-theme p-6">
                <h3 class="font-bold text-theme mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Detail Booking
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="check_in_date" class="block text-sm font-semibold text-theme mb-1.5">Tanggal Check-in <span class="text-red-500">*</span></label>
                        <input type="date" name="check_in_date" id="check_in_date"
                               value="{{ old('check_in_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="form-input w-full" required>
                    </div>
                    <div>
                        <label for="duration_years" class="block text-sm font-semibold text-theme mb-1.5">Durasi Sewa <span class="text-red-500">*</span></label>
                        <select name="duration_years" id="duration_years" class="form-input w-full" required>
                            <option value="1" {{ old('duration_years') == 1 ? 'selected' : '' }}>1 Tahun</option>
                            <option value="2" {{ old('duration_years') == 2 ? 'selected' : '' }}>2 Tahun</option>
                            <option value="3" {{ old('duration_years') == 3 ? 'selected' : '' }}>3 Tahun</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Payment Option --}}
            <div class="card shadow-theme p-6">
                <h3 class="font-bold text-theme mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Opsi Pembayaran
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="cursor-pointer group">
                        <input type="radio" name="payment_option" value="with_dp" class="peer sr-only" {{ old('payment_option', 'with_dp') === 'with_dp' ? 'checked' : '' }} required>
                        <div class="p-5 rounded-xl border-2 border-theme peer-checked:border-[var(--accent)] peer-checked:bg-[var(--accent)]/5 transition-all group-hover:border-[var(--accent)]/50">
                            <p class="font-bold text-theme mb-1">Opsi A — Booking + DP 30%</p>
                            <p class="text-sm text-theme-secondary">Reservasi kamar, bayar DP 30%, pelunasan saat check-in</p>
                            <p class="text-xs text-amber-600 dark:text-amber-400 mt-2">⚠️ Batas bayar DP: 12 jam setelah disetujui</p>
                        </div>
                    </label>
                    <label class="cursor-pointer group">
                        <input type="radio" name="payment_option" value="direct_checkin" class="peer sr-only" {{ old('payment_option') === 'direct_checkin' ? 'checked' : '' }}>
                        <div class="p-5 rounded-xl border-2 border-theme peer-checked:border-[var(--accent)] peer-checked:bg-[var(--accent)]/5 transition-all group-hover:border-[var(--accent)]/50">
                            <p class="font-bold text-theme mb-1">Opsi B — Direct Check-in</p>
                            <p class="text-sm text-theme-secondary">Bayar 50% saat check-in, pelunasan bulan ke-2</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Identity --}}
            <div class="card shadow-theme p-6">
                <h3 class="font-bold text-theme mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                    Data Identitas
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="identity_type" class="block text-sm font-semibold text-theme mb-1.5">Jenis Identitas <span class="text-red-500">*</span></label>
                        <select name="identity_type" id="identity_type" class="form-input w-full" required>
                            <option value="ktp" {{ old('identity_type') === 'ktp' ? 'selected' : '' }}>KTP</option>
                            <option value="ktm" {{ old('identity_type') === 'ktm' ? 'selected' : '' }}>KTM</option>
                            <option value="other" {{ old('identity_type') === 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label for="identity_number" class="block text-sm font-semibold text-theme mb-1.5">Nomor Identitas <span class="text-red-500">*</span></label>
                        <input type="text" name="identity_number" id="identity_number"
                               value="{{ old('identity_number') }}"
                               class="form-input w-full" placeholder="Nomor KTP / KTM" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="emergency_contact" class="block text-sm font-semibold text-theme mb-1.5">Kontak Darurat</label>
                        <input type="tel" name="emergency_contact" id="emergency_contact"
                               value="{{ old('emergency_contact') }}"
                               class="form-input w-full" placeholder="Nomor telepon darurat (opsional)">
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <button type="submit" class="btn-primary w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-3"
                        onclick="return confirm('Apakah Anda yakin ingin mengajukan booking ini?')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Konfirmasi Booking
                </button>
                <a href="{{ url()->previous() }}" class="btn-outline w-full sm:w-auto text-center px-8 py-3">Kembali</a>
            </div>

        </div>
    </form>
</div>
@endsection
