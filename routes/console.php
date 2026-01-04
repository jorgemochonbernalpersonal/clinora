<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Clean unverified users daily at 2:00 AM
Schedule::command('users:clean-unverified')->dailyAt('02:00');

// Send weekly summary emails every Sunday at 9:00 AM
Schedule::command('send:weekly-summary')->weeklyOn(0, '09:00');
