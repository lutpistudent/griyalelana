<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Contract;
use App\Models\PaymentSchedule;
use App\Models\Notification as AppNotification;
use App\Models\User;
use Illuminate\Console\Command;

class WeeklySummary extends Command
{
    protected $signature = 'summary:weekly';
    protected $description = 'Generate weekly summary notification for owner';

    public function handle(): int
    {
        $owner = User::where('role', 'owner')->first();
        if (!$owner) {
            $this->warn('No owner user found.');
            return Command::SUCCESS;
        }

        $now = now();
        $weekAgo = $now->copy()->subWeek();

        // Gather stats
        $newBookings = Booking::where('created_at', '>=', $weekAgo)->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $activeContracts = Contract::where('status', 'active')->count();
        $expiringContracts = Contract::where('status', 'active')
            ->where('end_date', '<=', $now->copy()->addDays(30))
            ->count();
        $overduePayments = PaymentSchedule::where('status', 'overdue')->count();
        $openComplaints = Complaint::where('status', 'open')->count();
        $weeklyRevenue = PaymentSchedule::where('status', 'paid')
            ->where('paid_at', '>=', $weekAgo)
            ->sum('amount');

        // Build summary message
        $lines = [];
        $lines[] = "📊 Ringkasan Minggu Ini ({$weekAgo->format('d M')} – {$now->format('d M Y')})";
        $lines[] = "";
        $lines[] = "📥 Booking baru: {$newBookings}";
        $lines[] = "⏳ Menunggu konfirmasi: {$pendingBookings}";
        $lines[] = "📋 Kontrak aktif: {$activeContracts}";

        if ($expiringContracts > 0) {
            $lines[] = "⚠️ Kontrak segera berakhir (30 hari): {$expiringContracts}";
        }
        if ($overduePayments > 0) {
            $lines[] = "🔴 Pembayaran terlambat: {$overduePayments}";
        }
        if ($openComplaints > 0) {
            $lines[] = "💬 Keluhan belum ditangani: {$openComplaints}";
        }

        $lines[] = "";
        $lines[] = "💰 Pendapatan minggu ini: Rp " . number_format($weeklyRevenue, 0, ',', '.');

        $message = implode("\n", $lines);

        AppNotification::create([
            'user_id' => $owner->id,
            'type' => 'weekly_summary',
            'title' => 'Ringkasan Mingguan',
            'message' => $message,
            'channel' => 'in_app',
            'sent_at' => now(),
        ]);

        $this->info("Weekly summary sent to owner.");
        $this->line($message);

        return Command::SUCCESS;
    }
}
