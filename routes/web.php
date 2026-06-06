<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/kamar/{slug}', [PageController::class, 'categoryDetail'])->name('rooms.category');
Route::get('/kamar/tipe/{slug}', [PageController::class, 'typeDetail'])->name('rooms.type');

// Auth-required routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Booking
    Route::get('/booking/{room}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::patch('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Contract
    Route::get('/dashboard/kontrak', [DashboardController::class, 'contractDetail'])->name('dashboard.contract');
    Route::get('/dashboard/kontrak/download', [DashboardController::class, 'downloadContract'])->name('dashboard.contract.download');

    // Payments
    Route::post('/payments/booking/{booking}/dp', [PaymentController::class, 'payBookingDp'])
        ->name('payments.booking-dp');
    Route::post('/payments/schedules/{schedule}', [PaymentController::class, 'paySchedule'])
        ->name('payments.schedule');

    // Complaints
    Route::get('/keluhan', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/keluhan/buat', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/keluhan', [ComplaintController::class, 'store'])->name('complaints.store');

    // Profile
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Export Reports (owner only — protected by middleware)
    Route::prefix('export')->name('export.')->middleware('owner')->group(function () {
        Route::get('/hunian', [\App\Http\Controllers\Admin\ExportController::class, 'occupancyPdf'])->name('occupancy');
        Route::get('/keuangan', [\App\Http\Controllers\Admin\ExportController::class, 'financialPdf'])->name('financial');
        Route::get('/booking-csv', [\App\Http\Controllers\Admin\ExportController::class, 'bookingsCsv'])->name('bookings');
    });

    // Notifications API (for bell polling)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread');
        Route::get('/recent', [NotificationController::class, 'recent'])->name('recent');
        Route::post('/mark-read', [NotificationController::class, 'markAllRead'])->name('markRead');
    });

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
