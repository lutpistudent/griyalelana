<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    protected $signature = 'bookings:expire';
    protected $description = 'Auto-expire approved bookings where DP payment deadline has passed (12 hours)';

    public function handle(): int
    {
        $expiredBookings = Booking::where('status', 'approved')
            ->whereNotNull('dp_expires_at')
            ->where('dp_expires_at', '<', now())
            ->with('contract.paymentSchedules', 'room')
            ->get();

        $count = 0;

        foreach ($expiredBookings as $booking) {
            $contract = $booking->contract;
            $dpPaid = $contract?->paymentSchedules
                ->where('installment_type', 'dp')
                ->where('status', 'paid')
                ->isNotEmpty();

            if ($dpPaid) {
                continue;
            }

            $booking->update(['status' => 'expired']);

            if ($contract) {
                $contract->paymentSchedules()
                    ->whereIn('status', ['pending', 'overdue'])
                    ->update(['status' => 'waived']);

                $contract->update(['status' => 'terminated']);
            }

            // Make the room available again
            if ($booking->room) {
                $booking->room->update(['status' => 'available']);
            }

            $count++;
        }

        $this->info("Expired {$count} booking(s).");

        return Command::SUCCESS;
    }
}
