<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan kolom untuk snapshot produk jika belum ada
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('order_items', 'product_description')) {
                $table->text('product_description')->nullable()->after('product_name');
            }
        });

        // Drop foreign key jika ada
        try {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        } catch (\Exception $e) {
            // Foreign key mungkin sudah tidak ada, skip
        }

        // Ubah product_id menjadi nullable
        DB::statement('ALTER TABLE order_items MODIFY product_id BIGINT UNSIGNED NULL');

        // Tambahkan foreign key baru dengan set null
        try {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreign('product_id')
                      ->references('id')
                      ->on('products')
                      ->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Foreign key mungkin sudah ada, skip
        }

        // Isi data snapshot untuk order items yang sudah ada
        DB::statement('
            UPDATE order_items oi
            INNER JOIN products p ON oi.product_id = p.id
            SET oi.product_name = p.name,
                oi.product_description = p.description
            WHERE oi.product_name IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Hapus kolom snapshot
            $table->dropColumn(['product_name', 'product_description']);
            
            // Kembalikan foreign key ke cascade
            $table->dropForeign(['product_id']);
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
            
            // Kembalikan product_id ke not nullable
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
        });
    }
};
