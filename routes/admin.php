<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Companies
    Route::resource('companies', CompanyController::class);

    // Cities
    Route::resource('cities', CityController::class);

    // Services
    Route::resource('services', ServiceController::class);

    // Forms
    Route::resource('forms', FormBuilderController::class);
    Route::get('forms/{form}/preview', [FormBuilderController::class, 'preview'])->name('forms.preview');
    Route::get('forms/{form}/shortcode', [FormBuilderController::class, 'shortcode'])->name('forms.shortcode');

    // Bookings
    Route::resource('bookings', BookingController::class)->only(['index', 'show', 'destroy']);
    Route::post('bookings/{booking}/assign', [BookingController::class, 'assign'])->name('bookings.assign');
});

