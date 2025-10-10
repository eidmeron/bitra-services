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

    // Zones
    Route::resource('zones', \App\Http\Controllers\Admin\ZoneController::class);

    // Cities
    Route::resource('cities', CityController::class);

    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // Services
    Route::resource('services', ServiceController::class);

    // Forms
    Route::resource('forms', FormBuilderController::class);
    Route::get('forms/{form}/preview', [FormBuilderController::class, 'preview'])->name('forms.preview');
    Route::get('forms/{form}/shortcode', [FormBuilderController::class, 'shortcode'])->name('forms.shortcode');
    Route::post('forms/{form}/duplicate', [FormBuilderController::class, 'duplicate'])->name('forms.duplicate');
    Route::post('forms/{form}/fields', [FormBuilderController::class, 'addField'])->name('forms.fields.add');
    Route::put('forms/{form}/fields/{field}', [FormBuilderController::class, 'updateField'])->name('forms.fields.update');
    Route::delete('forms/{form}/fields/{field}', [FormBuilderController::class, 'deleteField'])->name('forms.fields.delete');
    Route::post('forms/{form}/fields/reorder', [FormBuilderController::class, 'reorderFields'])->name('forms.fields.reorder');

    // Bookings
    Route::resource('bookings', BookingController::class)->only(['index', 'show', 'destroy']);
    Route::post('bookings/{booking}/assign', [BookingController::class, 'assign'])->name('bookings.assign');
});

