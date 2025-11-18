<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * 
     * Note: In Laravel 12+, schedules are defined in routes/console.php
     * using the Schedule facade, not here in the Kernel.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedules are now defined in routes/console.php
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
