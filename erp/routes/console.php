<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule commands
Schedule::command('inventory:check-stock')
    ->hourly()
    ->description('Check for low stock, expired, and expiring inventory items');

Schedule::command('appointments:check-reminders')
    ->everyFifteenMinutes()
    ->description('Check for appointment reminders and upcoming appointments');
