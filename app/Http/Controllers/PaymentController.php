<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentSchedule;
use App\Services\BookingService;
use App\Services\ContractPdfService;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function payBookingDp(
        Request $request,
        Booking $booking,
        BookingService $bookingService,
        ContractPdfService $pdfService,
        XenditService $xendit
    ) {
        $booking = $request->user()
            ->bookings()
            ->with('contract.paymentSchedules', 'room.roomType')
            ->whereKey($booking->id)
            ->firstOrFail();

        if ($booking->payment_option !== 'with_dp' || $booking->status !== 'approved') {
            return redirect()->route('dashboard')->with('error', 'Booking ini belum bisa dibayar.');
        }

        if ($booking->isExpired()) {
            return redirect()->route('dashboard')->with('error', 'Batas waktu pembayaran DP sudah habis.');
        }

        $contract = DB::transaction(function () use ($booking, $bookingService) {
            $lockedBooking = Booking::query()
                ->whereKey($booking->id)
                ->lockForUpdate()
                ->with('contract.paymentSchedules')
                ->firstOrFail();

            return $lockedBooking->contract ?: $bookingService->createContract($lockedBooking);
        });

        if (blank($contract->contract_pdf_url)) {
            try {
                $pdfService->generate($contract);
            } catch (\Throwable) {
                // PDF bisa digenerate ulang dari dashboard/admin jika gagal.
            }
        }

        $dpSchedule = $contract->paymentSchedules()
            ->where('installment_type', 'dp')
            ->whereIn('status', ['pending', 'overdue'])
            ->orderBy('installment_number')
            ->first();

        if (! $dpSchedule) {
            return redirect()->route('dashboard')->with('success', 'DP sudah tercatat lunas.');
        }

        return $this->redirectToInvoice($dpSchedule, $xendit);
    }

    public function paySchedule(Request $request, PaymentSchedule $schedule, XenditService $xendit)
    {
        $schedule->load('contract.user');

        abort_unless($schedule->contract && $schedule->contract->user_id === $request->user()->id, 403);

        if ($schedule->status === 'paid') {
            return redirect()->route('dashboard')->with('success', 'Tagihan ini sudah lunas.');
        }

        if (! in_array($schedule->status, ['pending', 'overdue'], true)) {
            return redirect()->route('dashboard')->with('error', 'Tagihan ini tidak bisa dibayar.');
        }

        return $this->redirectToInvoice($schedule, $xendit);
    }

    protected function redirectToInvoice(PaymentSchedule $schedule, XenditService $xendit)
    {
        try {
            $invoice = $xendit->createInvoice($schedule);
        } catch (\Throwable $e) {
            return redirect()->route('dashboard')->with('error', 'Gagal membuat invoice Xendit: ' . $e->getMessage());
        }

        if (blank($invoice['invoice_url'] ?? null)) {
            return redirect()->route('dashboard')->with('error', 'Invoice Xendit berhasil dibuat, tetapi URL pembayaran tidak tersedia.');
        }

        return redirect()->away($invoice['invoice_url']);
    }
}
