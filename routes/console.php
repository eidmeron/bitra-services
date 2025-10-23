<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule weekly payout reports generation every Monday at 9:00 AM
Schedule::command('payouts:generate-weekly-reports --send')
    ->weeklyOn(1, '09:00')
    ->name('generate-weekly-payout-reports')
    ->withoutOverlapping();
