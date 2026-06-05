@extends('layouts.app')

@section('title', 'Buat Keluhan — Griya Lelana')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-theme-secondary mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-accent transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('complaints.index') }}" class="hover:text-accent transition-colors">Keluhan</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-accent font-medium">Buat Baru</span>
    </nav>

    <h1 class="text-2xl md:text-3xl font-extrabold text-theme mb-2">Buat Keluhan</h1>
    <p class="text-theme-secondary mb-8">Sampaikan keluhan Anda terkait kamar <strong class="text-accent">{{ $contract->room->room_number ?? '-' }}</strong></p>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('complaints.store') }}">
        @csrf
        <div class="space-y-6">
            {{-- Title --}}
            <div class="card shadow-theme p-6">
                <label for="title" class="block text-sm font-semibold text-theme mb-1.5">
                    Judul Keluhan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title"
                    value="{{ old('title') }}"
                    class="form-input w-full"
                    placeholder="Contoh: AC kamar tidak dingin"
                    maxlength="200" required>
                <p class="text-xs text-theme-muted mt-1.5">Tuliskan ringkasan masalah Anda (maks 200 karakter)</p>
            </div>

            {{-- Description --}}
            <div class="card shadow-theme p-6">
                <label for="description" class="block text-sm font-semibold text-theme mb-1.5">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="6"
                    class="form-input w-full resize-y"
                    placeholder="Jelaskan detail masalah yang Anda alami..."
                    maxlength="2000" required>{{ old('description') }}</textarea>
                <p class="text-xs text-theme-muted mt-1.5">Jelaskan secara detail agar owner dapat segera menangani (maks 2000 karakter)</p>
            </div>

            {{-- Submit --}}
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <button type="submit" class="btn-primary w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-3"
                        onclick="return confirm('Kirim keluhan ini?')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Keluhan
                </button>
                <a href="{{ route('complaints.index') }}" class="btn-outline w-full sm:w-auto text-center px-8 py-3">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
