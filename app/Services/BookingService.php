<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Contract;
use App\Models\PaymentSchedule;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Owner approves a booking:
     * 1. Set status to approved
     * 2. Set dp_expires_at (12 hours from now) for Opsi A
     * 3. Lock the room (status = occupied)
     */
    public function approve(Booking $booking): Booking
    {
        $now = now();

        $booking->update([
            'status' => 'approved',
            'approved_at' => $now,
            'dp_expires_at' => $booking->payment_option === 'with_dp'
                ? $now->copy()->addHours(12)
                : null,
        ]);

        // Lock the room
        $booking->room->update(['status' => 'occupied']);

        return $booking;
    }

    /**
     * Owner rejects a booking with a reason.
     */
    public function reject(Booking $booking, string $reason): Booking
    {
        $booking->update([
            'status' => 'rejected',
            'rejected_reason' => $reason,
        ]);

        return $booking;
    }

    /**
     * Create a contract after DP payment is confirmed (Opsi A)
     * or immediately for Opsi B (direct check-in).
     */
    public function createContract(Booking $booking): Contract
    {
        $startDate = Carbon::parse($booking->check_in_date);
        $endDate = $startDate->copy()->addYears($booking->duration_years);
        $contractNumber = 'KON-' . date('Ymd') . '-' . Str::upper(Str::random(5));

        $contract = Contract::create([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'room_id' => $booking->room_id,
            'contract_number' => $contractNumber,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration_years' => $booking->duration_years,
            'total_amount' => $booking->total_amount,
            'payment_option' => $booking->payment_option,
            'status' => 'active',
        ]);

        // Generate payment schedules
        $this->generatePaymentSchedules($contract, $booking);

        return $contract;
    }

    /**
     * Generate payment schedule entries based on payment option:
     *
     * Opsi A (with_dp): DP 30% → Pelunasan 70% saat check-in
     * Opsi B (direct_checkin): 50% saat check-in → 50% bulan ke-2
     * Tahun 2+: 3x cicilan per tahun
     */
    protected function generatePaymentSchedules(Contract $contract, Booking $booking): void
    {
        $total = $contract->total_amount;
        $pricePerYear = $total / $contract->duration_years;
        $startDate = Carbon::parse($contract->start_date);
        $installment = 1;

        if ($contract->duration_years >= 1) {
            if ($booking->payment_option === 'with_dp') {
                // Opsi A — DP 30%
                $dpAmount = round($pricePerYear * 0.3, 0);
                $checkInAmount = $pricePerYear - $dpAmount;

                PaymentSchedule::create([
                    'contract_id' => $contract->id,
                    'installment_number' => $installment++,
                    'installment_type' => 'dp',
                    'amount' => $dpAmount,
                    'due_date' => now()->addHours(12), // Same as dp_expires_at
                    'status' => 'pending',
                ]);

                PaymentSchedule::create([
                    'contract_id' => $contract->id,
                    'installment_number' => $installment++,
                    'installment_type' => 'pelunasan_checkin',
                    'amount' => $checkInAmount,
                    'due_date' => $startDate,
                    'status' => 'pending',
                ]);
            } else {
                // Opsi B — 50% check-in + 50% bulan ke-2
                $firstPayment = round($pricePerYear * 0.5, 0);
                $secondPayment = $pricePerYear - $firstPayment;

                PaymentSchedule::create([
                    'contract_id' => $contract->id,
                    'installment_number' => $installment++,
                    'installment_type' => 'checkin_50',
                    'amount' => $firstPayment,
                    'due_date' => $startDate,
                    'status' => 'pending',
                ]);

                PaymentSchedule::create([
                    'contract_id' => $contract->id,
                    'installment_number' => $installment++,
                    'installment_type' => 'pelunasan_bulan2',
                    'amount' => $secondPayment,
                    'due_date' => $startDate->copy()->addDays(30),
                    'status' => 'pending',
                ]);
            }
        }

        // Year 2+: 3 installments per year
        for ($year = 2; $year <= $contract->duration_years; $year++) {
            $yearStart = $startDate->copy()->addYears($year - 1);
            $cicilan = round($pricePerYear / 3, 0);
            $lastCicilan = $pricePerYear - ($cicilan * 2); // Avoid rounding issues

            PaymentSchedule::create([
                'contract_id' => $contract->id,
                'installment_number' => $installment++,
                'installment_type' => "cicilan_tahun{$year}_1",
                'amount' => $cicilan,
                'due_date' => $yearStart,
                'status' => 'pending',
            ]);

            PaymentSchedule::create([
                'contract_id' => $contract->id,
                'installment_number' => $installment++,
                'installment_type' => "cicilan_tahun{$year}_2",
                'amount' => $cicilan,
                'due_date' => $yearStart->copy()->addMonths(4),
                'status' => 'pending',
            ]);

            PaymentSchedule::create([
                'contract_id' => $contract->id,
                'installment_number' => $installment++,
                'installment_type' => "cicilan_tahun{$year}_3",
                'amount' => $lastCicilan,
                'due_date' => $yearStart->copy()->addMonths(8),
                'status' => 'pending',
            ]);
        }
    }
}
