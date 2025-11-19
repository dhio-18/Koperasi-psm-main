<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule auto-confirm orders setiap hari jam 21:00 WIB
Schedule::command('orders:auto-confirm')
    ->dailyAt('21:00')
    ->timezone('Asia/Jakarta')
    ->name('auto-confirm-orders')
    ->description('Auto-confirm orders yang belum dikonfirmasi setelah jam 21:00 WIB');
