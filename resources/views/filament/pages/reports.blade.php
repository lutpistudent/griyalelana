<x-filament-panels::page>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem">
        {{-- Occupancy Report --}}
        <div style="background:#fff;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,.1);border:1px solid #e5e7eb">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                <div style="width:40px;height:40px;min-width:40px;background:#d1fae5;border-radius:8px;display:flex;align-items:center;justify-content:center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#059669" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:15px;color:#111">Laporan Hunian</div>
                    <div style="font-size:12px;color:#888">Status seluruh kamar</div>
                </div>
            </div>
            <p style="font-size:13px;color:#666;margin-bottom:16px;line-height:1.5">Daftar lengkap status kamar (tersedia, terisi, perbaikan) beserta nama penyewa aktif.</p>
            <a href="{{ route('export.occupancy') }}" style="display:inline-flex;align-items:center;gap:6px;background:#059669;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download PDF
            </a>
        </div>

        {{-- Financial Report --}}
        <div style="background:#fff;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,.1);border:1px solid #e5e7eb">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                <div style="width:40px;height:40px;min-width:40px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2563eb" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:15px;color:#111">Laporan Keuangan</div>
                    <div style="font-size:12px;color:#888">Per bulan</div>
                </div>
            </div>
            <p style="font-size:13px;color:#666;margin-bottom:16px;line-height:1.5">Ringkasan pembayaran: tagihan, terbayar, terlambat, dan tingkat koleksi.</p>
            <div style="display:flex;gap:8px;flex-wrap:wrap">
                <a href="{{ route('export.financial', ['month' => now()->month, 'year' => now()->year]) }}" style="display:inline-flex;align-items:center;gap:6px;background:#2563eb;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    Bulan Ini
                </a>
                @if(now()->month > 1)
                <a href="{{ route('export.financial', ['month' => now()->month - 1, 'year' => now()->year]) }}" style="display:inline-flex;align-items:center;padding:8px 14px;border-radius:8px;border:1px solid #d1d5db;color:#555;font-size:13px;font-weight:500;text-decoration:none">
                    Bulan Lalu
                </a>
                @endif
            </div>
        </div>

        {{-- Booking CSV --}}
        <div style="background:#fff;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,.1);border:1px solid #e5e7eb">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                <div style="width:40px;height:40px;min-width:40px;background:#fef3c7;border-radius:8px;display:flex;align-items:center;justify-content:center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d97706" style="width:20px;height:20px"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div>
                    <div style="font-weight:700;font-size:15px;color:#111">Riwayat Booking</div>
                    <div style="font-size:12px;color:#888">Format CSV / Excel</div>
                </div>
            </div>
            <p style="font-size:13px;color:#666;margin-bottom:16px;line-height:1.5">Seluruh data booking (penyewa, kamar, status, nominal) dalam format CSV.</p>
            <a href="{{ route('export.bookings') }}" style="display:inline-flex;align-items:center;gap:6px;background:#d97706;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download CSV
            </a>
        </div>
    </div>
</x-filament-panels::page>
