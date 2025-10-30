<?php

namespace App\Providers;

use App\Listeners\LinkGuestBookingsToUser;
use App\Models\Booking;
use App\Models\User;
use App\Observers\BookingObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Link guest bookings to user account when they login or register
        Event::listen([
            Login::class,
            Registered::class,
        ], LinkGuestBookingsToUser::class);
        
        // Register model observers
        Booking::observe(BookingObserver::class);
        User::observe(\App\Observers\UserObserver::class);
    }
}
