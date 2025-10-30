<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule weekly commission reports generation every Monday at 9:00 AM
Schedule::command('reports:generate-weekly-commission --send')
    ->weeklyOn(1, '09:00')
    ->name('generate-weekly-commission-reports')
    ->withoutOverlapping();

// Schedule daily cleanup of past slot times at 2:00 AM
Schedule::command('slots:cleanup-past')
    ->dailyAt('02:00')
    ->name('cleanup-past-slot-times')
    ->withoutOverlapping();
