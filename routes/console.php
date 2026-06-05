<?php

use Illuminate\Support\Facades\Schedule;

// Auto-expire bookings where DP payment deadline has passed (every 5 minutes)
Schedule::command('bookings:expire')->everyFiveMinutes();

// Send payment reminders daily at 8am
Schedule::command('payments:remind')->dailyAt('08:00');

// Weekly summary to owner every Monday at 7am
Schedule::command('summary:weekly')->weeklyOn(1, '07:00');
