<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Schedule Configuration
|--------------------------------------------------------------------------
*/

// Check for overdue borrow records daily at 8:00 AM
Schedule::command('borrow:check-overdue')->dailyAt('08:00');

// Send return reminders daily at 7:00 AM (1 day before due)
Schedule::command('borrow:send-reminders --days=1')->dailyAt('07:00');

// Check for low stock equipment weekly on Monday at 9:00 AM
Schedule::command('equipment:check-low-stock')->weeklyOn(1, '09:00');

// Clean up old AI chat logs monthly
Schedule::command('ai:cleanup-logs --days=90')->monthly();
