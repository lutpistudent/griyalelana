@extends('layouts.app')

@section('title', 'Keluhan Saya — Griya Lelana')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

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
            <h1 class="text-2xl md:text-3xl font-extrabold text-theme">Keluhan Saya</h1>
            <p class="text-theme-secondary mt-1">Kamar <span class="font-semibold text-accent">{{ $contract->room->room_number ?? '-' }}</span></p>
        </div>
        <a href="{{ route('complaints.create') }}" class="btn-primary text-sm px-5 py-2.5 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Keluhan
        </a>
    </div>

    {{-- Complaint List --}}
    @if($complaints->count() > 0)
    <div class="space-y-4">
        @foreach($complaints as $complaint)
        <div class="card shadow-theme p-5">
            <div class="flex items-start justify-between gap-4 mb-3">
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-theme text-base truncate">{{ $complaint->title }}</h3>
                    <p class="text-xs text-theme-muted mt-0.5">{{ $complaint->created_at->format('d M Y H:i') }}</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap flex-shrink-0
                    {{ $complaint->status === 'open' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                    {{ $complaint->status === 'in_progress' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                    {{ $complaint->status === 'resolved' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : '' }}
                    {{ $complaint->status === 'closed' ? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' : '' }}">
                    @switch($complaint->status)
                        @case('open') Menunggu @break
                        @case('in_progress') Diproses @break
                        @case('resolved') Selesai @break
                        @case('closed') Ditutup @break
                        @default {{ $complaint->status }}
                    @endswitch
                </span>
            </div>
            <p class="text-sm text-theme-secondary leading-relaxed">{{ Str::limit($complaint->description, 200) }}</p>

            @if($complaint->owner_notes)
            <div class="mt-4 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/15 border border-blue-200 dark:border-blue-800">
                <p class="text-xs font-semibold text-blue-700 dark:text-blue-400 mb-1">💬 Respons Owner:</p>
                <p class="text-sm text-blue-600 dark:text-blue-300">{{ $complaint->owner_notes }}</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $complaints->links() }}
    </div>
    @else
    <div class="card p-12 text-center shadow-theme">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="font-bold text-theme mb-2">Belum Ada Keluhan</h3>
        <p class="text-theme-secondary text-sm max-w-sm mx-auto">Semua berjalan baik! Jika ada masalah dengan kamar, silakan buat keluhan baru.</p>
    </div>
    @endif

    {{-- Back to Dashboard --}}
    <div class="mt-8">
        <a href="{{ route('dashboard') }}" class="btn-outline text-sm px-5 py-2.5 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
