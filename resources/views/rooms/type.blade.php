@extends('layouts.app')

@section('title', $type->name . ' — Griya Lelana')
@section('meta_description', $type->description)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex flex-wrap items-center gap-2 text-sm text-theme-secondary mb-8">
        <a href="{{ route('landing') }}" class="hover:text-accent transition-colors">Beranda</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('landing') }}#katalog" class="hover:text-accent transition-colors">Katalog</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('rooms.category', $type->category->slug) }}" class="hover:text-accent transition-colors">{{ $type->category->name }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-theme font-medium">{{ $type->has_ac ? 'dengan AC' : 'Non-AC' }}</span>
    </nav>

    {{-- Type Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        {{-- Photo Gallery --}}
        <div class="lg:col-span-2">
            @if($type->photos->count() > 0)
                <div class="aspect-[16/9] rounded-xl overflow-hidden bg-theme-muted mb-4">
                    <img src="{{ $type->photos->first()->photo_url }}" alt="{{ $type->name }}" class="w-full h-full object-cover" id="main-photo">
                </div>
                @if($type->photos->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($type->photos as $photo)
                    <button onclick="document.getElementById('main-photo').src='{{ $photo->photo_url }}'" class="aspect-square rounded-lg overflow-hidden bg-theme-muted border-2 border-transparent hover:border-accent transition-colors">
                        <img src="{{ $photo->photo_url }}" alt="{{ $photo->caption }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            @else
                <div class="aspect-[16/9] rounded-xl bg-theme-muted flex items-center justify-center">
                    <div class="text-center text-theme-muted">
                        <svg class="w-20 h-20 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p>Foto belum tersedia</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Type Details --}}
        <div class="card p-6 h-fit lg:sticky lg:top-24">
            <h1 class="text-2xl font-extrabold mb-2">{{ $type->name }}</h1>
            <p class="text-accent font-extrabold text-3xl mb-1">
                Rp {{ number_format($type->price_per_year, 0, ',', '.') }}
            </p>
            <p class="text-theme-muted text-sm mb-6">per tahun</p>

            @if($type->description)
                <p class="text-theme-secondary text-sm mb-6 leading-relaxed">{{ $type->description }}</p>
            @endif

            {{-- Specs --}}
            <div class="space-y-3 mb-6 pb-6 border-b border-theme">
                @if($type->room_size)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-theme-secondary">Luas</span>
                    <span class="font-semibold">{{ $type->room_size }} m²</span>
                </div>
                @endif
                <div class="flex items-center justify-between text-sm">
                    <span class="text-theme-secondary">AC</span>
                    <span class="font-semibold">{{ $type->has_ac ? 'Ya' : 'Tidak' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-theme-secondary">Kamar Mandi</span>
                    <span class="font-semibold">{{ $type->bathroom_type === 'inside' ? 'Dalam' : 'Luar' }}</span>
                </div>
                @if($type->bed_size)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-theme-secondary">Kasur</span>
                    <span class="font-semibold">{{ $type->bed_size }}</span>
                </div>
                @endif
            </div>

            {{-- Facilities --}}
            @if($type->facilities)
            <div class="mb-6">
                <h3 class="font-bold text-sm mb-3">Fasilitas</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($type->facilities as $facility)
                    <span class="text-xs bg-theme-muted px-3 py-1.5 rounded-full text-theme-secondary">{{ $facility }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- WhatsApp --}}
            @if($whatsapp)
            <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Halo, saya tertarik dengan ' . $type->name . ' dan ingin membuat janji temu untuk survey kamar. Apakah ada waktu yang tersedia?') }}" target="_blank" class="btn-whatsapp w-full text-center mb-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Buat Janji Temu
            </a>
            @endif
        </div>
    </div>

    {{-- Available Rooms List --}}
    <div>
        <h2 class="text-2xl font-extrabold mb-6">Daftar Kamar <span class="text-accent">Tersedia</span></h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($rooms as $room)
            <div class="card flex flex-col {{ $room->status !== 'available' ? 'opacity-50 pointer-events-none' : 'hover:shadow-lg hover:-translate-y-0.5' }} transition-all duration-200">
                {{-- Header --}}
                <div class="p-5 pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-extrabold text-theme tracking-tight">{{ $room->room_number }}</h3>
                        <p class="text-xs text-theme-muted font-medium mt-0.5">Lantai {{ $room->floor }}</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap
                        {{ $room->status === 'available'
                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                            : ($room->status === 'maintenance'
                                ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'
                                : 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400') }}">
                        @switch($room->status)
                            @case('available') Tersedia @break
                            @case('occupied') Ditempati @break
                            @case('maintenance') Perbaikan @break
                            @default Tidak Aktif
                        @endswitch
                    </span>
                </div>

                {{-- Separator --}}
                <div class="border-t border-theme mx-5"></div>

                {{-- Info --}}
                <div class="px-5 py-4 flex-1 space-y-2">
                    @if($room->position)
                    <div class="flex items-center gap-2 text-sm text-theme-secondary">
                        <svg class="w-4 h-4 text-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $room->position }}</span>
                    </div>
                    @endif
                    @if($room->notes)
                    <div class="flex items-start gap-2 text-sm text-theme-secondary">
                        <svg class="w-4 h-4 text-accent flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $room->notes }}</span>
                    </div>
                    @endif
                    @if(!$room->position && !$room->notes)
                    <p class="text-sm text-theme-muted italic">Tidak ada info tambahan</p>
                    @endif
                </div>

                {{-- Action --}}
                @if($room->status === 'available')
                <div class="px-5 pb-5">
                    <a href="{{ route('booking.create', $room->id) }}" class="btn-primary w-full text-center text-sm flex items-center justify-center gap-2 py-2.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Booking Sekarang
                    </a>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        @if($rooms->where('status', 'available')->count() === 0)
        <div class="text-center py-12">
            <p class="text-theme-muted text-lg">Semua kamar tipe ini sedang terisi.</p>
            <p class="text-theme-secondary mt-2">Hubungi kami via WhatsApp untuk info lebih lanjut.</p>
        </div>
        @endif
    </div>
</div>
@endsection
