<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan — Griya Lelana</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; line-height: 1.5; }
        .header { text-align: center; padding: 20px 0; border-bottom: 3px solid #d4a574; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #1a1612; margin-bottom: 4px; }
        .header p { color: #888; font-size: 10px; }
        .meta { margin-bottom: 20px; }
        .meta p { font-size: 10px; color: #666; }
        .stats { display: table; width: 100%; margin-bottom: 20px; }
        .stat-box { display: table-cell; width: 25%; text-align: center; padding: 12px 8px; border: 1px solid #eee; }
        .stat-box .value { font-size: 18px; font-weight: bold; color: #d4a574; }
        .stat-box .label { font-size: 8px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f7f2ed; color: #1a1612; font-weight: bold; text-align: left; padding: 6px 8px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #d4a574; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 9px; }
        tr:nth-child(even) { background-color: #fafafa; }
        .text-right { text-align: right; }
        .status-paid { color: #16a34a; font-weight: bold; }
        .status-pending { color: #d97706; font-weight: bold; }
        .status-overdue { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 9px; color: #999; }
        .summary-row td { font-weight: bold; background-color: #f7f2ed; border-top: 2px solid #d4a574; }
    </style>
</head>
<body>
    @php
        $monthNames = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    @endphp

    <div class="header">
        <h1>Griya Lelana</h1>
        <p>Laporan Keuangan — {{ $monthNames[$month] }} {{ $year }}</p>
    </div>

    <div class="meta">
        <p><strong>Periode:</strong> {{ $monthNames[$month] }} {{ $year }}</p>
        <p><strong>Dicetak:</strong> {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="value">{{ $collectionRate }}%</div>
            <div class="label">Tingkat Koleksi</div>
        </div>
        <div class="stat-box">
            <div class="value">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
            <div class="label">Terbayar</div>
        </div>
        <div class="stat-box">
            <div class="value">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
            <div class="label">Belum Bayar</div>
        </div>
        <div class="stat-box">
            <div class="value">Rp {{ number_format($totalExpected, 0, ',', '.') }}</div>
            <div class="label">Total Tagihan</div>
        </div>
    </div>

    @if($schedules->count() > 0)
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Penyewa</th>
                <th>Kamar</th>
                <th>Jenis</th>
                <th>Jatuh Tempo</th>
                <th class="text-right">Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $i => $s)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $s->contract->user->name ?? '-' }}</td>
                <td>{{ $s->contract->room->room_number ?? '-' }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $s->installment_type)) }}</td>
                <td>{{ $s->due_date->format('d M Y') }}</td>
                <td class="text-right">Rp {{ number_format($s->amount, 0, ',', '.') }}</td>
                <td class="{{ $s->status === 'paid' ? 'status-paid' : ($s->status === 'overdue' ? 'status-overdue' : 'status-pending') }}">
                    {{ $s->status === 'paid' ? 'Lunas' : ($s->status === 'overdue' ? 'Terlambat' : 'Menunggu') }}
                </td>
            </tr>
            @endforeach
            <tr class="summary-row">
                <td colspan="5">TOTAL</td>
                <td class="text-right">Rp {{ number_format($totalExpected, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @else
    <p style="text-align: center; padding: 40px; color: #999;">Tidak ada data pembayaran untuk periode ini.</p>
    @endif

    <div class="footer">
        <p>Dicetak otomatis oleh Sistem Griya Lelana — {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
