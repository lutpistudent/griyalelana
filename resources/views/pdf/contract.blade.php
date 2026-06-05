<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kontrak Sewa - {{ $contract->contract_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1612; line-height: 1.6; padding: 40px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #d4a574; padding-bottom: 20px; }
        .header h1 { font-size: 22px; color: #d4a574; margin-bottom: 4px; letter-spacing: 1px; }
        .header h2 { font-size: 16px; font-weight: normal; color: #5a4e42; }
        .header .contract-number { font-size: 12px; color: #8b7d6f; margin-top: 8px; }
        .section { margin-bottom: 20px; }
        .section h3 { font-size: 13px; font-weight: bold; color: #d4a574; margin-bottom: 10px; padding-bottom: 4px; border-bottom: 1px solid #e0d9cf; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.info td { padding: 4px 8px; vertical-align: top; }
        table.info td:first-child { width: 180px; font-weight: bold; color: #5a4e42; }
        table.schedule { border: 1px solid #e0d9cf; }
        table.schedule th { background-color: #f0ede8; padding: 8px 10px; text-align: left; font-size: 10px; border-bottom: 1px solid #e0d9cf; }
        table.schedule td { padding: 7px 10px; border-bottom: 1px solid #f0ede8; }
        table.schedule tr:last-child td { border-bottom: none; }
        .amount { text-align: right; font-family: 'DejaVu Sans Mono', monospace; }
        .total-row { font-weight: bold; background-color: #faf8f5; }
        .footer { margin-top: 40px; text-align: center; font-size: 9px; color: #8b7d6f; border-top: 1px solid #e0d9cf; padding-top: 15px; }
        .signature-area { margin-top: 40px; display: table; width: 100%; }
        .signature-col { display: table-cell; width: 50%; text-align: center; vertical-align: bottom; }
        .signature-line { border-top: 1px solid #1a1612; width: 180px; display: inline-block; margin-top: 80px; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 10px; font-weight: bold; }
        .badge-active { background-color: #dcfce7; color: #16a34a; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>GRIYA LELANA</h1>
        <h2>Surat Kontrak Sewa Kamar Kost</h2>
        <div class="contract-number">No: {{ $contract->contract_number }}</div>
    </div>

    {{-- Pihak Penyewa --}}
    <div class="section">
        <h3>Data Penyewa</h3>
        <table class="info">
            <tr><td>Nama Lengkap</td><td>: {{ $user->name }}</td></tr>
            <tr><td>Email</td><td>: {{ $user->email }}</td></tr>
            <tr><td>No. Telepon</td><td>: {{ $user->phone ?? '-' }}</td></tr>
            <tr><td>Jenis Identitas</td><td>: {{ strtoupper($booking->identity_type ?? '-') }}</td></tr>
            <tr><td>No. Identitas</td><td>: {{ $booking->identity_number ?? '-' }}</td></tr>
            @if($user->guardian_name)
            <tr><td>Wali / Kontak Darurat</td><td>: {{ $user->guardian_name }} ({{ $user->guardian_relation ?? '-' }}) — {{ $user->guardian_phone ?? '-' }}</td></tr>
            @endif
        </table>
    </div>

    {{-- Detail Kamar --}}
    <div class="section">
        <h3>Detail Kamar</h3>
        <table class="info">
            <tr><td>No. Kamar</td><td>: {{ $room->room_number }}</td></tr>
            <tr><td>Tipe Kamar</td><td>: {{ $roomType->name }}</td></tr>
            <tr><td>Lantai</td><td>: {{ $room->floor }}</td></tr>
            <tr><td>Harga per Tahun</td><td>: Rp {{ number_format($roomType->price_per_year, 0, ',', '.') }}</td></tr>
        </table>
    </div>

    {{-- Masa Kontrak --}}
    <div class="section">
        <h3>Masa Kontrak</h3>
        <table class="info">
            <tr><td>Tanggal Mulai</td><td>: {{ $contract->start_date->format('d F Y') }}</td></tr>
            <tr><td>Tanggal Berakhir</td><td>: {{ $contract->end_date->format('d F Y') }}</td></tr>
            <tr><td>Durasi</td><td>: {{ $contract->duration_years }} tahun</td></tr>
            <tr><td>Total Biaya</td><td>: <strong>Rp {{ number_format($contract->total_amount, 0, ',', '.') }}</strong></td></tr>
            <tr><td>Opsi Pembayaran</td><td>: {{ $contract->payment_option === 'with_dp' ? 'Opsi A — Booking + DP 30%' : 'Opsi B — Direct Check-in 50%' }}</td></tr>
        </table>
    </div>

    {{-- Jadwal Pembayaran --}}
    <div class="section">
        <h3>Jadwal Pembayaran</h3>
        <table class="schedule">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jenis</th>
                    <th>Jatuh Tempo</th>
                    <th class="amount">Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $totalScheduled = 0; @endphp
                @foreach($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->installment_number }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $schedule->installment_type)) }}</td>
                    <td>{{ $schedule->due_date->format('d M Y') }}</td>
                    <td class="amount">Rp {{ number_format($schedule->amount, 0, ',', '.') }}</td>
                    <td><span class="badge badge-active">{{ ucfirst($schedule->status) }}</span></td>
                </tr>
                @php $totalScheduled += $schedule->amount; @endphp
                @endforeach
                <tr class="total-row">
                    <td colspan="3"><strong>Total</strong></td>
                    <td class="amount"><strong>Rp {{ number_format($totalScheduled, 0, ',', '.') }}</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Ketentuan --}}
    <div class="section">
        <h3>Ketentuan</h3>
        <ol style="padding-left: 20px; font-size: 10px; color: #5a4e42;">
            <li>Penyewa wajib menjaga kebersihan dan ketertiban kamar serta lingkungan kost.</li>
            <li>Pembayaran yang melewati jatuh tempo akan dikenakan peringatan otomatis.</li>
            <li>Penyewa tidak diperbolehkan mengubah struktur atau fasilitas kamar tanpa izin.</li>
            <li>Kontrak ini berlaku sejak tanggal mulai dan berakhir pada tanggal yang tertera.</li>
            <li>Perpanjangan kontrak harus diajukan minimal 30 hari sebelum masa kontrak berakhir.</li>
        </ol>
    </div>

    {{-- Tanda Tangan --}}
    <div class="signature-area">
        <div class="signature-col">
            <p>Pemilik Kost</p>
            <div class="signature-line"></div>
            <p style="margin-top: 5px; font-weight: bold;">Owner Griya Lelana</p>
        </div>
        <div class="signature-col">
            <p>Penyewa</p>
            <div class="signature-line"></div>
            <p style="margin-top: 5px; font-weight: bold;">{{ $user->name }}</p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem Griya Lelana pada {{ now()->format('d F Y H:i') }} WIB</p>
        <p>{{ $contract->contract_number }}</p>
    </div>

</body>
</html>
