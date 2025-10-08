<?php

declare(strict_types=1);

use App\Http\Controllers\BookingSubmissionController;
use App\Http\Controllers\PublicFormController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public form routes
Route::get('/form/{token}', [PublicFormController::class, 'show'])->name('public.form');
Route::post('/form/{token}', [BookingSubmissionController::class, 'store'])->name('booking.submit');
Route::get('/booking-success/{booking}', function () {
    return view('public.success');
})->name('public.form.success');

// API routes for AJAX
Route::post('/api/calculate-price', [BookingSubmissionController::class, 'calculatePrice'])->name('api.calculate-price');
Route::get('/api/public/form/{token}/html', [PublicFormController::class, 'html'])->name('api.public.form.html');

require __DIR__ . '/admin.php';
require __DIR__ . '/company.php';
require __DIR__ . '/user.php';
require __DIR__ . '/auth.php';
