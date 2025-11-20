<?php

namespace App\Console\Commands;

use App\Models\Orders;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderAutoConfirm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-confirm {--force : Ignore time conditions for testing}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Otomatis konfirmasi pesanan yang belum dikonfirmasi setelah jam 17.00 WIB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Timezone WIB (UTC+7)
            $now = Carbon::now('Asia/Jakarta');
            $this->info("Current time (WIB): " . $now->format('Y-m-d H:i:s'));

            // Cek apakah ada flag --force untuk testing
            $isForce = $this->option('force');

            if ($isForce) {
                $this->warn("⚠️  Mode FORCE: Mengabaikan kondisi waktu, auto-confirm semua pesanan status='sending'");
            }

            // Cari pesanan yang:
            // 1. Status 'sending' (sedang dikirim)
            // 2. Belum di-auto-confirm (auto_confirmed = false)
            // 3. Order masuk dalam hari yang sama
            // Auto-confirm akan dijalankan setiap hari jam 17:00 WIB untuk semua order hari itu

            $query = Orders::query()
                ->where('status', 'sending')
                ->where('auto_confirmed', false);

            // Kondisi: Pesanan dibuat pada hari yang sama
            // Ini memastikan order yang masuk (pukul 7 pagi sampai 5 sore) akan di-auto-confirm pada 17:00
            // Flag --force akan mengabaikan kondisi ini untuk testing
            if (!$isForce) {
                $query->whereDate('created_at', $now->toDateString());
            }

            $autoConfirmOrders = $query->get();

            if ($autoConfirmOrders->isEmpty()) {
                $this->info("Tidak ada pesanan yang perlu di-auto-confirm.");
                return 0;
            }

            // Update pesanan
            $count = 0;
            foreach ($autoConfirmOrders as $order) {
                DB::beginTransaction();
                try {
                    $order->update([
                        'status' => 'completed',
                        'auto_confirmed' => true,
                        'auto_confirmed_at' => $now,
                    ]);

                    // Log activity
                    \App\Models\OrderHistory::create([
                        'order_id' => $order->id,
                        'action' => 'order_auto_confirmed',
                        'description' => 'Pesanan otomatis dikonfirmasi karena belum dikonfirmasi hingga jam 17:00 WIB',
                        'created_at' => $now,
                    ]);

                    DB::commit();
                    $count++;

                    $this->info("✓ Order #{$order->order_number} auto-confirmed");
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->error("✗ Error confirming order #{$order->id}: " . $e->getMessage());
                }
            }

            $this->info("\nTotal pesanan yang di-auto-confirm: $count");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
