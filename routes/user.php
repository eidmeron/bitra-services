<?php

declare(strict_types=1);

use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\ChatController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', UserMiddleware::class])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Bookings
    Route::resource('bookings', BookingController::class)->only(['index', 'show']);
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/review', [BookingController::class, 'review'])->name('bookings.review');
    
    // Chat
    Route::post('bookings/{booking}/chat', [ChatController::class, 'send'])->name('bookings.chat.send');
    
    // Notifications
    Route::post('notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    // Complaint routes
    Route::get('/complaints', [\App\Http\Controllers\ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [\App\Http\Controllers\ComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}/send-message', [\App\Http\Controllers\ComplaintController::class, 'sendMessage'])->name('complaints.send-message');
});

