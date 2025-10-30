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
use App\Http\Controllers\Admin\SlotTimeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/cleanup-slots', [DashboardController::class, 'cleanupPastSlots'])->name('dashboard.cleanup-slots');

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

    // Slot Times
    Route::resource('slot-times', SlotTimeController::class);
    Route::get('slot-times/bulk/create', [SlotTimeController::class, 'bulkCreate'])->name('slot-times.bulk-create');
    Route::post('slot-times/bulk', [SlotTimeController::class, 'bulkStore'])->name('slot-times.bulk-store');
    Route::post('slot-times/{slotTime}/toggle-availability', [SlotTimeController::class, 'toggleAvailability'])->name('slot-times.toggle-availability');
    Route::post('slot-times/bulk-delete', [SlotTimeController::class, 'bulkDelete'])->name('slot-times.bulk-delete');

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

    // Unified Reviews Management (Company + Platform)
    Route::get('reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('reviews.show');
    
    // Company review actions
    Route::post('reviews/{review}/approve-company', [\App\Http\Controllers\Admin\ReviewController::class, 'approveCompany'])->name('reviews.approve-company');
    Route::post('reviews/{review}/reject-company', [\App\Http\Controllers\Admin\ReviewController::class, 'rejectCompany'])->name('reviews.reject-company');
    
    // Platform review actions
    Route::post('reviews/{review}/approve-platform', [\App\Http\Controllers\Admin\ReviewController::class, 'approvePlatform'])->name('reviews.approve-platform');
    Route::post('reviews/{review}/reject-platform', [\App\Http\Controllers\Admin\ReviewController::class, 'rejectPlatform'])->name('reviews.reject-platform');
    Route::post('reviews/{review}/toggle-featured', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleFeatured'])->name('reviews.toggle-featured');
    
    Route::delete('reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // SEO Pages Management
    Route::resource('seo-pages', \App\Http\Controllers\Admin\SeoPageController::class);
    Route::post('seo-pages/{seoPage}/toggle-status', [\App\Http\Controllers\Admin\SeoPageController::class, 'toggleStatus'])->name('seo-pages.toggle-status');

    // Deposits (replaces payouts)
    Route::get('deposits/weekly-reports', [\App\Http\Controllers\Admin\DepositController::class, 'weeklyReports'])->name('deposits.weekly-reports');
    Route::post('deposits/generate-weekly-reports', [\App\Http\Controllers\Admin\DepositController::class, 'generateWeeklyReports'])->name('deposits.generate-weekly-reports');
    Route::post('deposits/weekly-reports/{report}/send', [\App\Http\Controllers\Admin\DepositController::class, 'sendWeeklyReport'])->name('deposits.weekly-reports.send');
    Route::resource('deposits', \App\Http\Controllers\Admin\DepositController::class);
    Route::post('deposits/{deposit}/send', [\App\Http\Controllers\Admin\DepositController::class, 'send'])->name('deposits.send');
    Route::post('deposits/{deposit}/mark-paid', [\App\Http\Controllers\Admin\DepositController::class, 'markAsPaid'])->name('deposits.mark-paid');
    
// Invoices
Route::get('invoices/settings', [\App\Http\Controllers\Admin\InvoiceController::class, 'settings'])->name('invoices.settings');
Route::post('invoices/settings', [\App\Http\Controllers\Admin\InvoiceController::class, 'updateSettings'])->name('invoices.settings.update');
Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class);
Route::post('invoices/{invoice}/send', [\App\Http\Controllers\Admin\InvoiceController::class, 'send'])->name('invoices.send');
Route::post('invoices/{invoice}/mark-paid', [\App\Http\Controllers\Admin\InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
    
    // Loyalty Points
    Route::resource('loyalty-points', \App\Http\Controllers\Admin\LoyaltyPointController::class);
    Route::post('loyalty-points/expire-old', [\App\Http\Controllers\Admin\LoyaltyPointController::class, 'expireOldPoints'])->name('loyalty-points.expire-old');
    
    // Analytics
    Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/data', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getData'])->name('analytics.data');
    Route::get('analytics/city/{city}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getCityAnalytics'])->name('analytics.city');
    Route::get('analytics/seo', [\App\Http\Controllers\Admin\AnalyticsController::class, 'seo'])->name('analytics.seo');
    Route::get('analytics/visitors', [\App\Http\Controllers\Admin\AnalyticsController::class, 'visitors'])->name('analytics.visitors');
    Route::get('analytics/settings', [\App\Http\Controllers\Admin\AnalyticsController::class, 'settings'])->name('analytics.settings');
    Route::post('analytics/settings', [\App\Http\Controllers\Admin\AnalyticsController::class, 'updateSettings'])->name('analytics.settings.update');
    Route::post('analytics/test-tracking', [\App\Http\Controllers\Admin\AnalyticsController::class, 'testTracking'])->name('analytics.test-tracking');
    
        // Notification Settings
        Route::resource('notification-settings', \App\Http\Controllers\Admin\NotificationSettingsController::class);
        Route::post('notification-settings/{notificationSetting}/toggle', [\App\Http\Controllers\Admin\NotificationSettingsController::class, 'toggle'])->name('notification-settings.toggle');
        Route::post('notification-settings/preview', [\App\Http\Controllers\Admin\NotificationSettingsController::class, 'preview'])->name('notification-settings.preview');
        
        // Email Templates
        Route::get('email-templates', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'index'])->name('email-templates.index');
        Route::get('email-templates/{notificationSetting}/edit', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'edit'])->name('email-templates.edit');
        Route::put('email-templates/{notificationSetting}', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'update'])->name('email-templates.update');
        Route::get('email-templates/{notificationSetting}/preview', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'preview'])->name('email-templates.preview');
        Route::post('email-templates/{notificationSetting}/test', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'test'])->name('email-templates.test');

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

