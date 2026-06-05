<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Griya Lelana - Kost nyaman dan strategis untuk mahasiswa & karyawan. Booking online, pembayaran mudah.')">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#d4a574">
    <meta name="author" content="Griya Lelana">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Griya Lelana — Kost Nyaman & Strategis')">
    <meta property="og:description" content="@yield('meta_description', 'Kost nyaman dan strategis untuk mahasiswa & karyawan. Booking online, pembayaran mudah.')">
    <meta property="og:site_name" content="Griya Lelana">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Griya Lelana — Kost Nyaman & Strategis')">
    <meta name="twitter:description" content="@yield('meta_description', 'Kost nyaman dan strategis untuk mahasiswa & karyawan. Booking online, pembayaran mudah.')">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">

    <title>@yield('title', 'Griya Lelana — Kost Nyaman & Strategis')</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">

    {{-- Preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('/') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Structured Data --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LodgingBusiness",
        "name": "Griya Lelana",
        "description": "Kost nyaman dan strategis untuk mahasiswa & karyawan",
        "url": "{{ url('/') }}",
        "priceRange": "Rp 10.000.000 - Rp 20.000.000 / tahun"
    }
    </script>
</head>
<body class="bg-theme text-theme min-h-screen flex flex-col">

    {{-- Skip to content (Accessibility) --}}
    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

    {{-- Noscript fallback --}}
    <noscript>
        <div style="background: #d4a574; color: #1a1612; text-align: center; padding: 12px; font-weight: 600;">
            JavaScript diperlukan untuk pengalaman terbaik. Silakan aktifkan JavaScript di browser Anda.
        </div>
    </noscript>

    {{-- NAVBAR --}}
    <nav id="navbar" class="navbar sticky top-0 z-50" role="navigation" aria-label="Navigasi utama">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('landing') }}" class="flex items-center gap-2">
                    <span class="text-xl font-extrabold text-accent">Griya</span>
                    <span class="text-xl font-extrabold text-theme">Lelana</span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('landing') }}" class="text-theme-secondary hover:text-accent font-medium transition-colors">Beranda</a>
                    <a href="{{ route('landing') }}#katalog" class="text-theme-secondary hover:text-accent font-medium transition-colors">Kamar</a>
                    <a href="{{ route('landing') }}#tentang" class="text-theme-secondary hover:text-accent font-medium transition-colors">Tentang</a>
                    <a href="{{ route('landing') }}#kontak" class="text-theme-secondary hover:text-accent font-medium transition-colors">Kontak</a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="text-theme-secondary hover:text-accent font-medium transition-colors">Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="text-theme-secondary hover:text-accent font-medium transition-colors">Profil</a>
                        @if(auth()->user()->isOwner())
                            <a href="{{ url('/admin') }}" class="text-theme-secondary hover:text-accent font-medium transition-colors">Admin Panel</a>
                        @endif

                        {{-- Notification Bell --}}
                        <div class="relative" id="notif-bell-desktop">
                            <button onclick="toggleNotifDropdown()" class="p-2 rounded-lg border border-theme hover:bg-theme-muted transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center relative" aria-label="Notifikasi">
                                <svg class="w-5 h-5 text-theme-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span id="notif-badge" class="notif-badge hidden">0</span>
                            </button>
                            <div id="notif-dropdown" class="notif-dropdown hidden">
                                <div class="notif-dropdown-header">
                                    <span class="font-semibold text-theme text-sm">Notifikasi</span>
                                    <button onclick="markAllRead()" class="text-xs text-accent hover:underline">Tandai dibaca</button>
                                </div>
                                <div id="notif-list" class="notif-list">
                                    <p class="text-center text-theme-muted text-sm py-6">Memuat...</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline text-sm px-4 py-2">Masuk</a>
                    @endauth

                    {{-- Theme Toggle (Animated Sun/Moon) --}}
                    <button id="theme-toggle-desktop" onclick="toggleTheme()" class="theme-toggle-btn p-2 rounded-lg border border-theme hover:bg-theme-muted transition-all min-w-[44px] min-h-[44px] flex items-center justify-center" aria-label="Toggle theme">
                        <svg class="theme-icon-sun w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <svg class="theme-icon-moon w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                </div>

                {{-- Mobile menu button --}}
                <div class="md:hidden flex items-center gap-2">
                    @auth
                    {{-- Mobile Notification Bell --}}
                    <button onclick="toggleNotifDropdown()" class="p-2 rounded-lg border border-theme min-w-[44px] min-h-[44px] flex items-center justify-center relative" aria-label="Notifikasi">
                        <svg class="w-5 h-5 text-theme-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span id="notif-badge-mobile" class="notif-badge hidden">0</span>
                    </button>
                    @endauth
                    <button id="theme-toggle-mobile" onclick="toggleTheme()" class="theme-toggle-btn p-2 rounded-lg border border-theme min-w-[44px] min-h-[44px] flex items-center justify-center" aria-label="Toggle tema">
                        <svg class="theme-icon-sun w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <svg class="theme-icon-moon w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                    <button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="p-2 rounded-lg border border-theme min-w-[44px] min-h-[44px] flex items-center justify-center" aria-label="Menu" aria-expanded="false">
                        <svg class="w-6 h-6 text-theme-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu (animated) --}}
            <div id="mobile-menu" class="md:hidden pb-4 border-t border-theme mt-2 pt-4">
                <div class="flex flex-col gap-1">
                    <a href="{{ route('landing') }}" class="text-theme-secondary hover:text-accent hover:bg-theme-muted/50 font-medium py-3 px-3 rounded-lg transition-all">Beranda</a>
                    <a href="{{ route('landing') }}#katalog" class="text-theme-secondary hover:text-accent hover:bg-theme-muted/50 font-medium py-3 px-3 rounded-lg transition-all">Kamar</a>
                    <a href="{{ route('landing') }}#tentang" class="text-theme-secondary hover:text-accent hover:bg-theme-muted/50 font-medium py-3 px-3 rounded-lg transition-all">Tentang</a>
                    <a href="{{ route('landing') }}#kontak" class="text-theme-secondary hover:text-accent hover:bg-theme-muted/50 font-medium py-3 px-3 rounded-lg transition-all">Kontak</a>
                    @auth
                        <div class="border-t border-theme mt-2 pt-2">
                            <a href="{{ route('dashboard') }}" class="text-theme-secondary hover:text-accent hover:bg-theme-muted/50 font-medium py-3 px-3 rounded-lg transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}" class="text-theme-secondary hover:text-accent hover:bg-theme-muted/50 font-medium py-3 px-3 rounded-lg transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profil
                            </a>
                            @if(auth()->user()->isOwner())
                            <a href="{{ url('/admin') }}" class="text-theme-secondary hover:text-accent hover:bg-theme-muted/50 font-medium py-3 px-3 rounded-lg transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Admin Panel
                            </a>
                            @endif
                        </div>
                    @else
                        <div class="mt-3 pt-3 border-t border-theme">
                            <a href="{{ route('login') }}" class="btn-primary text-center w-full">Masuk</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main id="main-content" class="flex-1" role="main">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer id="kontak" class="bg-theme-card border-t border-theme" role="contentinfo">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-xl font-extrabold text-accent">Griya</span>
                        <span class="text-xl font-extrabold text-theme">Lelana</span>
                    </div>
                    <p class="text-theme-secondary text-sm leading-relaxed">Hunian nyaman dan strategis untuk mahasiswa & karyawan.</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="font-bold text-theme mb-4">Navigasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('landing') }}" class="text-theme-secondary hover:text-accent transition-colors">Beranda</a></li>
                        <li><a href="{{ route('landing') }}#katalog" class="text-theme-secondary hover:text-accent transition-colors">Katalog Kamar</a></li>
                        <li><a href="{{ route('landing') }}#tentang" class="text-theme-secondary hover:text-accent transition-colors">Tentang Kami</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="font-bold text-theme mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-theme-secondary">
                        @if($settings['whatsapp_number'] ?? false)
                            <li>
                                <a href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank" class="flex items-center gap-2 hover:text-accent transition-colors">
                                    <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    <span>WhatsApp</span>
                                </a>
                            </li>
                        @endif
                        @if($settings['email'] ?? false)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>{{ $settings['email'] }}</span>
                            </li>
                        @endif
                        @if($settings['kost_address'] ?? false)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $settings['kost_address'] }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="border-t border-theme mt-8 pt-8 text-center text-sm text-theme-muted">
                &copy; {{ date('Y') }} Griya Lelana. All rights reserved.
            </div>
        </div>
    </footer>

    {{-- Toast Container --}}
    <div id="toast-container" class="fixed top-20 right-4 z-[100] flex flex-col gap-3 pointer-events-none"></div>

</body>
</html>
