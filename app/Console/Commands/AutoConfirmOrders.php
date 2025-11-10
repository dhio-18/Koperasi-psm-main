<?php

namespace App\Console\Commands;

use App\Models\Orders;
use App\Models\OrderHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoConfirmOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-confirm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-confirm orders that have not been confirmed or returned within 8 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking orders for auto-confirmation...');

        // Ambil pesanan yang statusnya 'sending' dan sudah lebih dari 8 jam
        $cutoffTime = Carbon::now()->subHours(8);

        $orders = Orders::where('status', 'sending')
            ->where('updated_at', '<=', $cutoffTime)
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No orders to auto-confirm.');
            return 0;
        }

        $confirmedCount = 0;

        foreach ($orders as $order) {
            // Update status menjadi completed
            $order->update([
                'status' => 'completed',
                'confirmed_at' => now(),
            ]);

            // Tambah history
            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'action' => 'completed',
                'description' => 'Pesanan otomatis dikonfirmasi setelah 8 jam (sistem auto-confirm)',
            ]);

            $confirmedCount++;
            $this->line("Order #{$order->order_number} auto-confirmed");
        }

        $this->info("Total {$confirmedCount} orders auto-confirmed successfully!");

        return 0;
    }
}
