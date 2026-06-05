<?php

namespace App\Console\Commands;

use App\Mail\PaymentReminder;
use App\Models\PaymentSchedule;
use App\Models\Notification as AppNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminders extends Command
{
    protected $signature = 'payments:remind';
    protected $description = 'Send payment reminders for schedules due within 7 days and mark overdue payments';

    public function handle(): int
    {
        $remindersCount = 0;
        $overdueCount = 0;

        // 1. Mark overdue payments
        $overdue = PaymentSchedule::where('status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        foreach ($overdue as $schedule) {
            $schedule->update(['status' => 'overdue']);

            $contract = $schedule->contract;
            if ($contract) {
                AppNotification::create([
                    'user_id' => $contract->user_id,
                    'type' => 'payment_overdue',
                    'title' => 'Pembayaran Terlambat!',
                    'message' => ucwords(str_replace('_', ' ', $schedule->installment_type))
                        . ' sebesar Rp ' . number_format($schedule->amount, 0, ',', '.')
                        . ' telah melewati jatuh tempo.',
                    'related_model' => 'PaymentSchedule',
                    'related_id' => $schedule->id,
                    'channel' => 'in_app',
                    'sent_at' => now(),
                ]);

                // Send email
                if ($contract->user && $contract->user->email) {
                    try {
                        Mail::to($contract->user->email)->send(new PaymentReminder($schedule, 'overdue'));
                    } catch (\Throwable $e) {
                        $this->warn("Email failed for user {$contract->user_id}: {$e->getMessage()}");
                    }
                }
            }
            $overdueCount++;
        }

        // 2. Remind for payments due within 7 days
        $upcoming = PaymentSchedule::where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->get();

        foreach ($upcoming as $schedule) {
            $contract = $schedule->contract;
            if (!$contract) continue;

            // Check if already reminded today
            $alreadyReminded = AppNotification::where('user_id', $contract->user_id)
                ->where('type', 'payment_reminder')
                ->where('related_id', $schedule->id)
                ->where('sent_at', '>=', now()->startOfDay())
                ->exists();

            if ($alreadyReminded) continue;

            $daysUntilDue = now()->diffInDays($schedule->due_date);

            AppNotification::create([
                'user_id' => $contract->user_id,
                'type' => 'payment_reminder',
                'title' => 'Pengingat Pembayaran',
                'message' => ucwords(str_replace('_', ' ', $schedule->installment_type))
                    . ' sebesar Rp ' . number_format($schedule->amount, 0, ',', '.')
                    . ' jatuh tempo dalam ' . $daysUntilDue . ' hari ('
                    . $schedule->due_date->format('d M Y') . ').',
                'related_model' => 'PaymentSchedule',
                'related_id' => $schedule->id,
                'channel' => 'in_app',
                'sent_at' => now(),
            ]);

            // Send email
            if ($contract->user && $contract->user->email) {
                try {
                    Mail::to($contract->user->email)->send(new PaymentReminder($schedule, 'reminder'));
                } catch (\Throwable $e) {
                    $this->warn("Email failed for user {$contract->user_id}: {$e->getMessage()}");
                }
            }
            $remindersCount++;
        }

        $this->info("Sent {$remindersCount} reminder(s), marked {$overdueCount} overdue.");

        return Command::SUCCESS;
    }
}
