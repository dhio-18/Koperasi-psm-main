<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Jika kolom belum ada, tambahkan
            if (!Schema::hasColumn('orders', 'auto_confirmed')) {
                $table->boolean('auto_confirmed')->default(false)->after('status')->comment('Status auto-confirm pesanan');
            }
            if (!Schema::hasColumn('orders', 'auto_confirmed_at')) {
                $table->timestamp('auto_confirmed_at')->nullable()->after('auto_confirmed')->comment('Waktu pesanan auto-confirmed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'auto_confirmed')) {
                $table->dropColumn('auto_confirmed');
            }
            if (Schema::hasColumn('orders', 'auto_confirmed_at')) {
                $table->dropColumn('auto_confirmed_at');
            }
        });
    }
};
