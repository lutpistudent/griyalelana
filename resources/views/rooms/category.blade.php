@extends('layouts.app')

@section('title', $category->name . ' — Griya Lelana')
@section('meta_description', $category->description)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-theme-secondary mb-8">
        <a href="{{ route('landing') }}" class="hover:text-accent transition-colors">Beranda</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('landing') }}#katalog" class="hover:text-accent transition-colors">Katalog</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-theme font-medium">{{ $category->name }}</span>
    </nav>

    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-3">{{ $category->name }}</h1>
        <p class="text-theme-secondary text-lg max-w-2xl mx-auto">{{ $category->description }}</p>
        <p class="text-theme-secondary mt-2">Pilih tipe kamar yang sesuai dengan kebutuhanmu</p>
    </div>

    {{-- Comparison Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        @foreach($types as $type)
        <div class="card overflow-hidden">
            {{-- Image --}}
            <div class="aspect-[4/3] bg-theme-muted relative overflow-hidden">
                @if($type->primaryPhoto)
                    <img src="{{ $type->primaryPhoto->photo_url }}" alt="{{ $type->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-theme-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                @endif
                <div class="absolute top-3 right-3">
                    <span class="badge {{ $type->rooms_count > 0 ? 'badge-available' : 'badge-occupied' }}">
                        {{ $type->rooms_count }} tersedia
                    </span>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6">
                <h2 class="text-xl font-bold mb-2">{{ $type->name }}</h2>
                <p class="text-accent font-extrabold text-2xl mb-4">
                    Rp {{ number_format($type->price_per_year, 0, ',', '.') }}<span class="text-theme-muted text-sm font-normal">/tahun</span>
                </p>

                {{-- Facilities --}}
                <div class="space-y-2 mb-6">
                    @if($type->has_ac)
                        <div class="flex items-center gap-2 text-sm text-theme-secondary">
                            <span class="text-accent">✓</span> AC
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-sm text-theme-secondary">
                            <span class="text-accent">✓</span> Ventilasi Alami
                        </div>
                    @endif
                    <div class="flex items-center gap-2 text-sm text-theme-secondary">
                        <span class="text-accent">✓</span> {{ $type->bathroom_type === 'inside' ? 'Kamar Mandi Dalam' : 'Kamar Mandi Luar' }}
                    </div>
                    @if($type->bed_size)
                    <div class="flex items-center gap-2 text-sm text-theme-secondary">
                        <span class="text-accent">✓</span> Kasur {{ $type->bed_size }}
                    </div>
                    @endif
                    @if($type->room_size)
                    <div class="flex items-center gap-2 text-sm text-theme-secondary">
                        <span class="text-accent">✓</span> Luas {{ $type->room_size }}m²
                    </div>
                    @endif
                    @if($type->facilities)
                        @foreach(array_slice($type->facilities, 0, 3) as $f)
                        <div class="flex items-center gap-2 text-sm text-theme-secondary">
                            <span class="text-accent">✓</span> {{ $f }}
                        </div>
                        @endforeach
                    @endif
                </div>

                <a href="{{ route('rooms.type', $type->slug) }}" class="btn-primary w-full text-center">
                    Lihat Kamar Tersedia
                </a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- WhatsApp CTA --}}
    @if($whatsapp)
    <div class="text-center mt-12">
        <p class="text-theme-secondary mb-4">Ingin survei langsung? Buat janji temu dengan owner</p>
        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Halo, saya tertarik dengan kamar kategori ' . $category->name . ' dan ingin membuat janji temu untuk survey. Apakah ada waktu yang tersedia?') }}" target="_blank" class="btn-whatsapp">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Buat Janji Temu
        </a>
    </div>
    @endif
</div>
@endsection
