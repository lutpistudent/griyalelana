@extends('layouts.app')

@section('title', $settings['kost_name'] . ' — ' . $settings['kost_tagline'])
@section('meta_description', $settings['kost_description'])

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section class="hero-gradient relative overflow-hidden" aria-label="Hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
        <div class="max-w-2xl animate-fade-in-up">
            <h1 class="text-hero font-extrabold leading-tight mb-6">
                <span class="text-accent">{{ $settings['kost_name'] }}</span>
            </h1>
            <p class="text-lg md:text-xl text-theme-secondary mb-8 leading-relaxed">
                {{ $settings['kost_tagline'] }}
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="#katalog" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Lihat Kamar
                </a>
                @if($settings['whatsapp_number'])
                <a href="https://wa.me/{{ $settings['whatsapp_number'] }}?text={{ urlencode('Halo, saya tertarik dengan kost ' . $settings['kost_name'] . '. Bisa info lebih lanjut?') }}" target="_blank" class="btn-whatsapp">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Hubungi Kami
                </a>
                @endif
            </div>
        </div>
    </div>
    {{-- Decorative --}}
    <div class="absolute top-0 right-0 w-1/3 h-full opacity-10">
        <div class="w-full h-full" style="background: radial-gradient(circle at 70% 30%, var(--accent) 0%, transparent 60%);"></div>
    </div>
</section>

{{-- ===== TENTANG SECTION ===== --}}
<section id="tentang" class="py-16 md:py-24" aria-label="Tentang Kami">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-section font-extrabold mb-4">Tentang <span class="text-accent">Kami</span></h2>
            <p class="text-theme-secondary text-lg leading-relaxed">
                {{ $settings['kost_description'] ?: 'Griya Lelana menyediakan hunian kost yang nyaman, bersih, dan strategis. Dilengkapi dengan fasilitas modern untuk menunjang kebutuhan mahasiswa dan karyawan.' }}
            </p>
        </div>

        {{-- Features grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 stagger">
            <div class="card p-6 text-center reveal">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-accent/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-bold mb-2">Aman & Nyaman</h3>
                <p class="text-theme-secondary text-sm">Keamanan 24 jam dan lingkungan yang nyaman</p>
            </div>
            <div class="card p-6 text-center reveal">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-accent/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="font-bold mb-2">Lokasi Strategis</h3>
                <p class="text-theme-secondary text-sm">Dekat kampus, perkantoran, dan pusat kota</p>
            </div>
            <div class="card p-6 text-center reveal">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-accent/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="font-bold mb-2">Fasilitas Lengkap</h3>
                <p class="text-theme-secondary text-sm">WiFi, parkir, dapur bersama, dan laundry</p>
            </div>
            <div class="card p-6 text-center reveal">
                <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-accent/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-bold mb-2">Harga Terjangkau</h3>
                <p class="text-theme-secondary text-sm">Pembayaran fleksibel dan transparan</p>
            </div>
        </div>

        {{-- Google Maps --}}
        @if($settings['google_maps_embed'])
        <div class="mt-12 rounded-xl overflow-hidden border border-theme">
            <iframe src="{{ $settings['google_maps_embed'] }}" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
        @endif
    </div>
</section>

{{-- ===== KATALOG KAMAR SECTION ===== --}}
<section id="katalog" class="py-16 md:py-24 bg-theme-muted" aria-label="Katalog Kamar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <h2 class="text-section font-extrabold mb-4">Katalog <span class="text-accent">Kamar</span></h2>
            <p class="text-theme-secondary text-lg">Temukan kamar yang sesuai dengan kebutuhanmu</p>
        </div>

        {{-- Filter Chips --}}
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-8 sm:mb-10" role="group" aria-label="Filter kamar">
            <button onclick="filterCatalog('all', this)" class="filter-chip active">Semua</button>
            <button onclick="filterCatalog('ac', this)" class="filter-chip">AC</button>
            <button onclick="filterCatalog('non-ac', this)" class="filter-chip">Non-AC</button>
            <button onclick="filterCatalog('km-dalam', this)" class="filter-chip">KM Dalam</button>
            <button onclick="filterCatalog('km-luar', this)" class="filter-chip">KM Luar</button>
        </div>

        {{-- Room Type Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-5 stagger">
            @foreach($categories as $category)
                @php
                    $types = $category->roomTypes;
                @endphp

                @foreach($types as $type)
                <div data-category-card
                     data-has-ac="{{ $type->has_ac ? '1' : '0' }}"
                     data-bathroom="{{ $type->bathroom_type }}"
                     class="card group cursor-pointer"
                     style="transition: opacity 0.3s, transform 0.3s"
                     onclick="window.location='{{ route('rooms.type', $type->slug) }}'">

                    {{-- Image --}}
                    <div class="aspect-square sm:aspect-[4/3] bg-theme-muted relative overflow-hidden">
                        @if($type->primaryPhoto)
                            <img src="{{ $type->primaryPhoto->photo_url }}" alt="Foto kamar {{ $type->name }} — {{ $type->has_ac ? 'AC' : 'Non-AC' }}, {{ $type->bathroom_type === 'inside' ? 'KM Dalam' : 'KM Luar' }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-10 h-10 sm:w-14 sm:h-14 text-theme-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        @endif

                        {{-- Badge overlay --}}
                        <div class="absolute top-2 right-2">
                            <span class="text-[10px] sm:text-xs font-bold px-2 py-0.5 sm:py-1 rounded-full backdrop-blur-sm
                                {{ $type->rooms_count > 0
                                    ? 'bg-green-500/90 text-white'
                                    : 'bg-red-500/90 text-white' }}">
                                {{ $type->rooms_count }} kamar
                            </span>
                        </div>

                        {{-- AC/Non-AC indicator --}}
                        <div class="absolute top-2 left-2">
                            <span class="text-[10px] sm:text-xs font-semibold px-2 py-0.5 sm:py-1 rounded-full bg-black/50 text-white backdrop-blur-sm">
                                {{ $type->has_ac ? '❄️ AC' : '🌿 Non-AC' }}
                            </span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-3 sm:p-4">
                        <h3 class="text-sm sm:text-base font-bold text-theme leading-tight mb-1 line-clamp-1">{{ $type->name }}</h3>

                        {{-- Price --}}
                        <div class="mb-2">
                            <span class="text-accent font-extrabold text-sm sm:text-lg">Rp {{ number_format($type->price_per_year / 1000000, 1, ',', '.') }}jt</span>
                            <span class="text-theme-muted text-[10px] sm:text-xs">/tahun</span>
                        </div>

                        {{-- Compact specs --}}
                        <div class="flex flex-wrap gap-1 mb-3">
                            <span class="text-[10px] sm:text-xs bg-theme-muted text-theme-secondary px-1.5 py-0.5 rounded">
                                {{ $type->bathroom_type === 'inside' ? 'KM Dalam' : 'KM Luar' }}
                            </span>
                            @if($type->room_size)
                            <span class="text-[10px] sm:text-xs bg-theme-muted text-theme-secondary px-1.5 py-0.5 rounded">
                                {{ $type->room_size }}m²
                            </span>
                            @endif
                        </div>

                        {{-- CTA Button --}}
                        <a href="{{ route('rooms.type', $type->slug) }}"
                           class="block w-full text-center text-xs sm:text-sm font-semibold py-2 sm:py-2.5 rounded-lg bg-accent text-[#1a1612] hover:bg-accent-hover transition-all"
                           onclick="event.stopPropagation()">
                            Lihat Kamar
                        </a>
                    </div>
                </div>
                @endforeach
            @endforeach
        </div>
    </div>
</section>

@endsection
