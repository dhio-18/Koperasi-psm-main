<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Auto-confirm orders setiap jam 21:00 WIB
        $schedule->command('orders:auto-confirm')
            ->dailyAt('21:00')  // Jam 21:00
            ->timezone('Asia/Jakarta')  // Timezone WIB
            ->name('auto-confirm-orders')
            ->description('Auto-confirm orders yang belum dikonfirmasi setelah jam 21:00 WIB');

        // Optional: Jalankan setiap 30 menit juga untuk backup (jika scheduler background tidak berjalan)
        $schedule->command('orders:auto-confirm')
            ->everyThirtyMinutes()
            ->timezone('Asia/Jakarta')
            ->name('auto-confirm-orders-every-30-min')
            ->description('Fallback: Auto-confirm orders setiap 30 menit');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
