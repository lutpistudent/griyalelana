<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create(Request $request, Room $room)
    {
        $user = $request->user();
        $settings = Setting::getAllCached();

        // Check constraints
        if (!$user->canBookRoom()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda sudah memiliki booking aktif atau kontrak berjalan.');
        }

        // Check room availability
        if ($room->status !== 'available') {
            return redirect()->back()
                ->with('error', 'Kamar ini sudah tidak tersedia.');
        }

        $room->load('roomType.category');

        return view('booking.create', compact('user', 'room', 'settings'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Validate constraints again
        if (!$user->canBookRoom()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda sudah memiliki booking aktif atau kontrak berjalan.');
        }

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'duration_years' => 'required|integer|min:1',
            'payment_option' => 'required|in:with_dp,direct_checkin',
            'identity_type' => 'required|in:ktp,ktm,other',
            'identity_number' => 'required|string|max:50',
            'emergency_contact' => 'nullable|string|max:20',
        ]);

        try {
            $booking = DB::transaction(function () use ($validated, $user) {
                // Lock the room row to prevent race condition
                $room = Room::lockForUpdate()->findOrFail($validated['room_id']);

                // Double-check room is still available inside transaction
                if ($room->status !== 'available') {
                    throw new \RuntimeException('Kamar ini sudah tidak tersedia.');
                }

                $room->load('roomType');

                // Calculate amounts
                $pricePerYear = $room->roomType->price_per_year;
                $totalAmount = $pricePerYear * $validated['duration_years'];
                $dpAmount = $validated['payment_option'] === 'with_dp'
                    ? round($totalAmount * 0.3)
                    : round($totalAmount * 0.5);

                // Create booking
                return $user->bookings()->create([
                    'room_id' => $room->id,
                    'check_in_date' => $validated['check_in_date'],
                    'duration_years' => $validated['duration_years'],
                    'payment_option' => $validated['payment_option'],
                    'total_amount' => $totalAmount,
                    'dp_amount' => $dpAmount,
                    'identity_type' => $validated['identity_type'],
                    'identity_number' => $validated['identity_number'],
                    'emergency_contact' => $validated['emergency_contact'],
                    'status' => 'pending',
                ]);
            });

            return redirect()->route('dashboard')
                ->with('success', 'Booking berhasil diajukan! Menunggu konfirmasi owner.');
        } catch (\RuntimeException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function cancel(Request $request, $bookingId)
    {
        $user = $request->user();
        $booking = $user->bookings()->where('id', $bookingId)->firstOrFail();

        if (!in_array($booking->status, ['pending', 'approved'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Booking ini tidak bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('dashboard')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
