@extends('layouts.app')

@section('title', 'Profil Saya — Griya Lelana')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-theme">Profil Saya</h1>
            <p class="text-theme-secondary mt-1">Kelola data pribadi dan keamanan akun</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-outline text-sm px-4 py-2 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Dashboard
        </a>
    </div>

    {{-- Profile Info --}}
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')
        <div class="card shadow-theme p-6 mb-6">
            <h3 class="font-bold text-theme mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Data Pribadi
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-theme mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input w-full" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-theme mb-1.5">Email</label>
                    <input type="email" value="{{ $user->email }}" class="form-input w-full bg-theme-muted/30 cursor-not-allowed" disabled>
                    <p class="text-xs text-theme-muted mt-1">Email tidak dapat diubah</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-theme mb-1.5">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input w-full" placeholder="08xxxxxxxxxx">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="border-t border-theme mt-6 pt-6">
                <h4 class="font-semibold text-theme mb-3 text-sm">Kontak Darurat / Wali</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-theme-secondary mb-1.5">Nama Wali</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $user->guardian_name) }}" class="form-input w-full" placeholder="Nama orang tua / kerabat">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-theme-secondary mb-1.5">No. HP Wali</label>
                        <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $user->guardian_phone) }}" class="form-input w-full" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-theme-secondary mb-1.5">Hubungan</label>
                        <select name="guardian_relation" class="form-input w-full">
                            <option value="">— Pilih —</option>
                            @foreach(['Orang Tua', 'Saudara', 'Kerabat', 'Teman'] as $r)
                                <option value="{{ $r }}" {{ old('guardian_relation', $user->guardian_relation) === $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn-primary px-6 py-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    {{-- Change Password --}}
    <form method="POST" action="{{ route('profile.password') }}">
        @csrf
        @method('PUT')
        <div class="card shadow-theme p-6">
            <h3 class="font-bold text-theme mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Ubah Password
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-theme mb-1.5">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-input w-full" required>
                    @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-theme mb-1.5">Password Baru</label>
                    <input type="password" name="password" class="form-input w-full" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-theme mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-input w-full" required>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="btn-primary px-6 py-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Ubah Password
                </button>
            </div>
        </div>
    </form>

    {{-- Logout --}}
    <div class="card shadow-theme p-6 mt-6 border-red-200 dark:border-red-800/50">
        <h3 class="font-bold text-theme mb-2 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar dari Akun
        </h3>
        <p class="text-theme-secondary text-sm mb-4">Anda akan keluar dari sesi saat ini dan kembali ke halaman utama.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm text-white bg-red-500 hover:bg-red-600 transition-all hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
