<?php

declare(strict_types=1);

use App\Http\Controllers\Company\BookingController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Middleware\CompanyMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', CompanyMiddleware::class])->prefix('company')->name('company.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Bookings
    Route::resource('bookings', BookingController::class)->only(['index', 'show']);
    Route::post('bookings/{booking}/accept', [BookingController::class, 'accept'])->name('bookings.accept');
    Route::post('bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::post('bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
});

