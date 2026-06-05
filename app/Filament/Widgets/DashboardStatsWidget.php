<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Contract;
use App\Models\PaymentSchedule;
use App\Models\Room;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRooms = Room::count();
        $occupied = Room::where('status', 'occupied')->count();
        $available = Room::where('status', 'available')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100, 1) : 0;

        $activeContracts = Contract::where('status', 'active')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $openComplaints = Complaint::where('status', 'open')->count();

        $monthlyRevenue = PaymentSchedule::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $overduePayments = PaymentSchedule::where('status', 'overdue')->count();

        return [
            Stat::make('Tingkat Hunian', $occupancyRate . '%')
                ->description("{$occupied}/{$totalRooms} kamar terisi")
                ->descriptionIcon('heroicon-o-building-office')
                ->color($occupancyRate >= 80 ? 'success' : ($occupancyRate >= 50 ? 'warning' : 'danger'))
                ->chart([$available, $occupied]),

            Stat::make('Kamar Tersedia', $available)
                ->description("Dari {$totalRooms} total kamar")
                ->descriptionIcon('heroicon-o-key')
                ->color('info'),

            Stat::make('Kontrak Aktif', $activeContracts)
                ->description('Penyewa saat ini')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('success'),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description(now()->format('F Y'))
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Booking Menunggu', $pendingBookings)
                ->description('Perlu konfirmasi')
                ->descriptionIcon('heroicon-o-clock')
                ->color($pendingBookings > 0 ? 'warning' : 'gray'),

            Stat::make('Keluhan Baru', $openComplaints)
                ->description('Belum ditangani')
                ->descriptionIcon('heroicon-o-chat-bubble-left-right')
                ->color($openComplaints > 0 ? 'danger' : 'success'),
        ];
    }
}
