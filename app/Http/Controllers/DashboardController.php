<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\ContractPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $settings = Setting::getAllCached();

        // Eager load both relations in a single pass to avoid multiple queries
        $user->load([
            'contracts' => fn ($q) => $q->where('status', 'active')->with('room.roomType', 'paymentSchedules', 'booking'),
            'bookings' => fn ($q) => $q->whereIn('status', ['pending', 'approved'])->with('room.roomType'),
        ]);

        // Determine dashboard state from already-loaded relations
        $activeContract = $user->contracts->first();
        $pendingBooking = $user->bookings->where('status', 'pending')->first();
        $approvedBooking = $user->bookings->where('status', 'approved')->first();

        // State 1: Has active contract
        if ($activeContract) {
            $nextPayment = $activeContract->nextPaymentDue();
            $paidSchedules = $activeContract->paymentSchedules->where('status', 'paid')->count();
            $totalSchedules = $activeContract->paymentSchedules->count();
            $totalPaid = $activeContract->paymentSchedules->where('status', 'paid')->sum('amount');

            $state = 'contract';

            return view('dashboard.index', compact(
                'user', 'settings', 'state', 'activeContract',
                'nextPayment', 'paidSchedules', 'totalSchedules', 'totalPaid'
            ));
        }

        // State 2: Has pending or approved booking
        if ($pendingBooking) {
            $state = 'pending';

            return view('dashboard.index', compact('user', 'settings', 'state', 'pendingBooking'));
        }

        if ($approvedBooking) {
            $state = 'approved';

            return view('dashboard.index', compact('user', 'settings', 'state', 'approvedBooking'));
        }

        // State 3: No booking — fresh user
        $state = 'empty';

        // Also show booking history if any
        $bookingHistory = $user->bookings()
            ->whereIn('status', ['rejected', 'cancelled', 'expired'])
            ->with('room.roomType')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('user', 'settings', 'state', 'bookingHistory'));
    }

    public function contractDetail(Request $request)
    {
        $user = $request->user();
        $contract = $user->contracts()->where('status', 'active')
            ->with('room.roomType', 'paymentSchedules', 'booking', 'user')
            ->first();

        if (! $contract) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada kontrak aktif.');
        }

        $settings = Setting::getAllCached();

        return view('dashboard.contract', compact('user', 'contract', 'settings'));
    }

    public function downloadContract(Request $request)
    {
        $user = $request->user();
        $contract = $user->contracts()->where('status', 'active')->first();

        if (! $contract || ! $contract->contract_pdf_url) {
            return redirect()->route('dashboard')->with('error', 'PDF kontrak belum tersedia.');
        }

        $path = ltrim(str_replace('/storage/', '', parse_url($contract->contract_pdf_url, PHP_URL_PATH) ?? ''), '/');

        if ($path === '') {
            $path = "contracts/{$contract->contract_number}.pdf";
        }

        if (! Storage::disk('public')->exists($path)) {
            app(ContractPdfService::class)->generate($contract);
            $path = "contracts/{$contract->contract_number}.pdf";
        }

        return Storage::disk('public')->download($path, "Kontrak-{$contract->contract_number}.pdf");
    }
}
