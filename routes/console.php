<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Kirim reminder peminjaman terlambat & pending setiap hari jam 08:00
Schedule::command('artilia:send-reminders')->dailyAt('08:00');
