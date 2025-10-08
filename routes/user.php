<?php

declare(strict_types=1);

use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', UserMiddleware::class])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::resource('bookings', BookingController::class)->only(['index', 'show']);
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/review', [BookingController::class, 'review'])->name('bookings.review');
});

