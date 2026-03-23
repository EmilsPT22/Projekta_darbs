<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send daily entry reminders on weekdays at 9:00 AM
Schedule::command('reminders:send-daily-entries')
    ->dailyAt('09:00')
    ->weekdays()
    ->withoutOverlapping();
