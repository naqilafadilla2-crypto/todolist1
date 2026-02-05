<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule device status check every minute
Schedule::command('devices:check-status')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();
