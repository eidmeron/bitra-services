<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\CompanyRegistrationController;
use App\Http\Controllers\Company\BookingController;
use App\Http\Controllers\Company\ChatController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\MessageController;
use App\Http\Controllers\Company\PayoutController;
use App\Http\Controllers\Company\ProfileController;
use App\Http\Controllers\Company\SettingsController;
use App\Http\Middleware\CompanyMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', CompanyMiddleware::class])->prefix('company')->name('company.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.account');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::delete('/settings/delete', [SettingsController::class, 'deleteAccount'])->name('settings.delete');
    
    // Company Registration (multi-step)
    Route::get('/register', [CompanyRegistrationController::class, 'showRegistrationForm'])
        ->withoutMiddleware(['auth', CompanyMiddleware::class])
        ->name('register');
    
    Route::post('/register', [CompanyRegistrationController::class, 'register'])
        ->withoutMiddleware(['auth', CompanyMiddleware::class])
        ->name('register.submit');

    // Bookings
    Route::resource('bookings', BookingController::class)->only(['index', 'show']);
    Route::post('bookings/{booking}/accept', [BookingController::class, 'accept'])->name('bookings.accept');
    Route::post('bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::post('bookings/{booking}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    
    // Chat
    Route::get('bookings/{booking}/chat', [ChatController::class, 'show'])->name('bookings.chat');
    Route::post('bookings/{booking}/chat', [ChatController::class, 'send'])->name('bookings.chat.send');
    Route::post('bookings/{booking}/chat/json', [ChatController::class, 'store'])->name('bookings.chat.store');
    Route::get('bookings/{booking}/chat/fetch', [ChatController::class, 'fetch'])->name('bookings.chat.fetch');
    
    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    
    // Payouts
    Route::get('/payouts', [PayoutController::class, 'index'])->name('payouts.index');
    Route::get('/payouts/{payout}', [PayoutController::class, 'show'])->name('payouts.show');
    Route::get('/payouts/weekly-reports', [PayoutController::class, 'weeklyReports'])->name('payouts.weekly-reports');
    Route::get('/payouts/weekly-reports/{report}', [PayoutController::class, 'showWeeklyReport'])->name('payouts.weekly-report');
    Route::get('/payouts/balance', [PayoutController::class, 'balance'])->name('payouts.balance');
    Route::get('/payouts/tax-info', [PayoutController::class, 'taxInfo'])->name('payouts.tax-info');
    
    // Notifications
    Route::post('notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    // Complaint routes
    Route::get('/complaints', [\App\Http\Controllers\Company\ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [\App\Http\Controllers\Company\ComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}/send-message', [\App\Http\Controllers\Company\ComplaintController::class, 'sendMessage'])->name('complaints.send-message');
    
    // Extra fees routes
    Route::get('/bookings/{booking}/extra-fees/create', [\App\Http\Controllers\Company\ExtraFeeController::class, 'create'])->name('extra-fees.create');
    Route::post('/bookings/{booking}/extra-fees', [\App\Http\Controllers\Company\ExtraFeeController::class, 'store'])->name('extra-fees.store');
    Route::get('/extra-fees/{extraFee}/edit', [\App\Http\Controllers\Company\ExtraFeeController::class, 'edit'])->name('extra-fees.edit');
    Route::put('/extra-fees/{extraFee}', [\App\Http\Controllers\Company\ExtraFeeController::class, 'update'])->name('extra-fees.update');
    Route::delete('/extra-fees/{extraFee}', [\App\Http\Controllers\Company\ExtraFeeController::class, 'destroy'])->name('extra-fees.destroy');
    
    // Slot times routes
    Route::resource('slot-times', \App\Http\Controllers\Company\SlotTimeController::class);
    Route::get('/slot-times/bulk/create', [\App\Http\Controllers\Company\SlotTimeController::class, 'bulkCreate'])->name('slot-times.bulk.create');
    Route::post('/slot-times/bulk', [\App\Http\Controllers\Company\SlotTimeController::class, 'bulkStore'])->name('slot-times.bulk.store');
});

