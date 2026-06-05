<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hunian — Griya Lelana</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .header { text-align: center; padding: 20px 0; border-bottom: 3px solid #d4a574; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #1a1612; margin-bottom: 4px; }
        .header p { color: #888; font-size: 10px; }
        .meta { margin-bottom: 20px; }
        .meta p { font-size: 10px; color: #666; }
        .stats { display: table; width: 100%; margin-bottom: 20px; }
        .stat-box { display: table-cell; width: 25%; text-align: center; padding: 12px 8px; border: 1px solid #eee; }
        .stat-box .value { font-size: 24px; font-weight: bold; color: #d4a574; }
        .stat-box .label { font-size: 9px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f7f2ed; color: #1a1612; font-weight: bold; text-align: left; padding: 8px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #d4a574; }
        td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) { background-color: #fafafa; }
        .status-available { color: #16a34a; font-weight: bold; }
        .status-occupied { color: #dc2626; font-weight: bold; }
        .status-maintenance { color: #d97706; font-weight: bold; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Griya Lelana</h1>
        <p>Laporan Hunian Kamar</p>
    </div>

    <div class="meta">
        <p><strong>Tanggal:</strong> {{ now()->format('d F Y') }}</p>
        <p><strong>Total Kamar:</strong> {{ $totalRooms }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="value">{{ $occupancyRate }}%</div>
            <div class="label">Tingkat Hunian</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $occupied }}</div>
            <div class="label">Terisi</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $available }}</div>
            <div class="label">Tersedia</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $maintenance }}</div>
            <div class="label">Perbaikan</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Kamar</th>
                <th>Tipe</th>
                <th>Lantai</th>
                <th>Status</th>
                <th>Penyewa</th>
                <th>Kontrak s/d</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
            <tr>
                <td><strong>{{ $room->room_number }}</strong></td>
                <td>{{ $room->roomType->name ?? '-' }}</td>
                <td>{{ $room->floor }}</td>
                <td class="{{ $room->status === 'available' ? 'status-available' : ($room->status === 'occupied' ? 'status-occupied' : 'status-maintenance') }}">
                    {{ $room->status === 'available' ? 'Tersedia' : ($room->status === 'occupied' ? 'Terisi' : 'Perbaikan') }}
                </td>
                <td>{{ $room->currentContract->user->name ?? '—' }}</td>
                <td>{{ $room->currentContract ? $room->currentContract->end_date->format('d M Y') : '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak otomatis oleh Sistem Griya Lelana — {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
