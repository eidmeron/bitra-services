<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class);

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
    Route::post('bookings/{booking}/change-status', [BookingController::class, 'changeStatus'])->name('bookings.change-status');
    Route::post('bookings/{booking}/reassign-company', [BookingController::class, 'reassignCompany'])->name('bookings.reassign-company');
    Route::post('bookings/{booking}/send-message', [BookingController::class, 'sendMessage'])->name('bookings.send-message');
    Route::post('bookings/{booking}/send-email', [BookingController::class, 'sendEmail'])->name('bookings.send-email');
    
    // Contact Messages
    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::post('contact-messages/{contactMessage}/reply', [ContactMessageController::class, 'reply'])->name('contact-messages.reply');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    // Platform Reviews
    Route::get('platform-reviews', [\App\Http\Controllers\Admin\PlatformReviewController::class, 'index'])->name('platform-reviews.index');
    Route::get('platform-reviews/{platformReview}', [\App\Http\Controllers\Admin\PlatformReviewController::class, 'show'])->name('platform-reviews.show');
    Route::post('platform-reviews/{platformReview}/approve', [\App\Http\Controllers\Admin\PlatformReviewController::class, 'approve'])->name('platform-reviews.approve');
    Route::post('platform-reviews/{platformReview}/reject', [\App\Http\Controllers\Admin\PlatformReviewController::class, 'reject'])->name('platform-reviews.reject');
    Route::post('platform-reviews/{platformReview}/toggle-featured', [\App\Http\Controllers\Admin\PlatformReviewController::class, 'toggleFeatured'])->name('platform-reviews.toggle-featured');
    Route::delete('platform-reviews/{platformReview}', [\App\Http\Controllers\Admin\PlatformReviewController::class, 'destroy'])->name('platform-reviews.destroy');

    // Company Reviews
    Route::get('reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('reviews.show');
    Route::post('reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Payouts
    Route::get('payouts', [PayoutController::class, 'index'])->name('payouts.index');
    Route::get('payouts/{payout}', [PayoutController::class, 'show'])->name('payouts.show');
    Route::post('payouts/{payout}/approve', [PayoutController::class, 'approve'])->name('payouts.approve');
    Route::post('payouts/{payout}/mark-as-paid', [PayoutController::class, 'markAsPaid'])->name('payouts.mark-as-paid');
    Route::post('payouts/bulk-approve', [PayoutController::class, 'bulkApprove'])->name('payouts.bulk-approve');
    Route::post('payouts/bulk-mark-as-paid', [PayoutController::class, 'bulkMarkAsPaid'])->name('payouts.bulk-mark-as-paid');
    Route::get('payouts/weekly-reports', [PayoutController::class, 'weeklyReports'])->name('payouts.weekly-reports');
    Route::post('payouts/generate-weekly-reports', [PayoutController::class, 'generateWeeklyReports'])->name('payouts.generate-weekly-reports');
    Route::post('payouts/send-weekly-reports', [PayoutController::class, 'sendWeeklyReports'])->name('payouts.send-weekly-reports');
    Route::get('companies/{company}/balance', [PayoutController::class, 'companyBalance'])->name('payouts.company-balance');
    Route::post('companies/{company}/approve-payouts', [PayoutController::class, 'approveCompanyPayouts'])->name('payouts.approve-company-payouts');
    Route::post('companies/{company}/mark-payouts-as-paid', [PayoutController::class, 'markCompanyPayoutsAsPaid'])->name('payouts.mark-company-payouts-as-paid');

    // Notifications
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Complaint routes
    Route::get('/complaints', [\App\Http\Controllers\Admin\ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [\App\Http\Controllers\Admin\ComplaintController::class, 'show'])->name('complaints.show');
    Route::put('/complaints/{complaint}/status', [\App\Http\Controllers\Admin\ComplaintController::class, 'updateStatus'])->name('complaints.update-status');
    Route::post('/complaints/{complaint}/send-message', [\App\Http\Controllers\Admin\ComplaintController::class, 'sendMessage'])->name('complaints.send-message');
    Route::post('/complaints/{complaint}/resolve', [\App\Http\Controllers\Admin\ComplaintController::class, 'resolve'])->name('complaints.resolve');
    Route::post('/complaints/{complaint}/close', [\App\Http\Controllers\Admin\ComplaintController::class, 'close'])->name('complaints.close');
    
    // SEO Pages removed as requested
    
    // Site Settings (Global configuration)
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    // Page Contents (Static page content management)
    Route::resource('pages', PageContentController::class);
    
    // Email Marketing
    Route::resource('email-marketing', \App\Http\Controllers\Admin\EmailMarketingController::class);
    Route::post('email-marketing/{campaign}/send', [\App\Http\Controllers\Admin\EmailMarketingController::class, 'send'])->name('email-marketing.send');
    Route::get('email-marketing/subscribers', [\App\Http\Controllers\Admin\EmailMarketingController::class, 'subscribers'])->name('email-marketing.subscribers');
    
    // Commission Settings
    Route::resource('commissions', \App\Http\Controllers\Admin\CommissionSettingController::class);
    Route::post('commissions/bulk-create', [\App\Http\Controllers\Admin\CommissionSettingController::class, 'bulkCreate'])->name('commissions.bulk-create');
    
    // Extra fees management
    Route::get('extra-fees', [\App\Http\Controllers\Admin\ExtraFeeController::class, 'index'])->name('extra-fees.index');
    Route::get('extra-fees/{extraFee}', [\App\Http\Controllers\Admin\ExtraFeeController::class, 'show'])->name('extra-fees.show');
    Route::post('extra-fees/{extraFee}/approve', [\App\Http\Controllers\Admin\ExtraFeeController::class, 'approve'])->name('extra-fees.approve');
    Route::post('extra-fees/{extraFee}/reject', [\App\Http\Controllers\Admin\ExtraFeeController::class, 'reject'])->name('extra-fees.reject');
    
    
});

