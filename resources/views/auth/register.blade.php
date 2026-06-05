@extends('layouts.app')

@section('title', 'Daftar — Griya Lelana')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4">
    <div class="card-static p-8 sm:p-10 w-full max-w-lg shadow-theme animate-fade-in-up">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-accent/10 flex items-center justify-center">
                <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h1 class="text-2xl font-extrabold text-theme mb-1">Buat Akun Baru</h1>
            <p class="text-theme-secondary text-sm">Daftar untuk mulai booking kamar di <span class="text-accent font-semibold">Griya Lelana</span></p>
        </div>

        @if($errors->any())
            <div data-flash class="mb-4 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-sm animate-fade-in" style="transition: opacity 0.3s, transform 0.3s">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Data Diri --}}
            <div class="mb-5">
                <p class="text-xs font-bold text-accent uppercase tracking-widest mb-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Data Diri
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-theme mb-1.5">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            class="form-input w-full" placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-theme mb-1.5">No. Telepon</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                            class="form-input w-full" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-theme mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="form-input w-full" placeholder="nama@email.com">
            </div>

            {{-- Keamanan --}}
            <div class="mb-6">
                <p class="text-xs font-bold text-accent uppercase tracking-widest mb-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Keamanan
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-theme mb-1.5">Password</label>
                        <div class="relative">
                            <input type="password" id="reg-password" name="password" required
                                class="form-input w-full" style="padding-right: 2.75rem;" placeholder="Min. 8 karakter">
                            <button type="button" onclick="togglePasswordVisibility('reg-password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-theme-muted hover:text-theme-secondary transition-colors">
                                <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-theme mb-1.5">Konfirmasi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="form-input w-full" placeholder="Ulangi password">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full text-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Buat Akun
            </button>
        </form>

        <p class="text-center text-sm text-theme-secondary mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-accent font-semibold hover:underline">Masuk</a>
        </p>
    </div>
</div>

<script>
function togglePasswordVisibility(id, btn) {
    const input = document.getElementById(id);
    const eyeOpen = btn.querySelector('.eye-open');
    const eyeClosed = btn.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script>
@endsection
