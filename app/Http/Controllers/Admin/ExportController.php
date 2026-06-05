<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Contract;
use App\Models\PaymentSchedule;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export occupancy report as PDF.
     */
    public function occupancyPdf()
    {
        $rooms = Room::with('roomType.category', 'currentContract.user')
            ->orderBy('room_number')
            ->get();

        $totalRooms = $rooms->count();
        $occupied = $rooms->where('status', 'occupied')->count();
        $available = $rooms->where('status', 'available')->count();
        $maintenance = $rooms->where('status', 'maintenance')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100, 1) : 0;

        $data = compact('rooms', 'totalRooms', 'occupied', 'available', 'maintenance', 'occupancyRate');

        $pdf = Pdf::loadView('pdf.report-occupancy', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Hunian-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export financial report as PDF.
     */
    public function financialPdf(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $schedules = PaymentSchedule::with('contract.user', 'contract.room')
            ->whereYear('due_date', $year)
            ->whereMonth('due_date', $month)
            ->orderBy('due_date')
            ->get();

        $totalExpected = $schedules->sum('amount');
        $totalPaid = $schedules->where('status', 'paid')->sum('amount');
        $totalPending = $schedules->whereIn('status', ['pending', 'overdue'])->sum('amount');
        $collectionRate = $totalExpected > 0 ? round(($totalPaid / $totalExpected) * 100, 1) : 0;

        $data = compact('schedules', 'month', 'year', 'totalExpected', 'totalPaid', 'totalPending', 'collectionRate');

        $pdf = Pdf::loadView('pdf.report-financial', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download("Laporan-Keuangan-{$year}-{$month}.pdf");
    }

    /**
     * Export booking history as CSV.
     */
    public function bookingsCsv(Request $request)
    {
        $bookings = Booking::with('user', 'room.roomType')
            ->latest()
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Riwayat-Booking-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['ID', 'Penyewa', 'Email', 'Kamar', 'Tipe', 'Check-in', 'Durasi (Tahun)', 'Total', 'DP', 'Opsi Bayar', 'Status', 'Dibuat']);

            foreach ($bookings as $b) {
                fputcsv($file, [
                    $b->id,
                    $b->user->name ?? '-',
                    $b->user->email ?? '-',
                    $b->room->room_number ?? '-',
                    $b->room->roomType->name ?? '-',
                    $b->check_in_date,
                    $b->duration_years,
                    $b->total_amount,
                    $b->dp_amount,
                    $b->payment_option === 'with_dp' ? 'DP 30%' : 'Direct Check-in',
                    $b->status,
                    $b->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
