<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';
    protected $fillable = [
        'id',
        'quantity',
        'price',
        'subtotal',
        'order_id',
        'product_id',
        'product_name',          // Snapshot
        'product_description'    // Snapshot
    ];

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    /**
     * Accessor untuk mendapatkan nama produk
     * Prioritas: snapshot > relasi products > fallback
     */
    public function getProductNameAttribute($value)
    {
        // Jika ada snapshot product_name, gunakan itu
        if (!empty($value)) {
            return $value;
        }

        // Jika relasi products masih ada, gunakan nama dari products
        if ($this->products) {
            return $this->products->name;
        }

        // Fallback jika produk sudah dihapus dan tidak ada snapshot
        return 'Produk Tidak Tersedia';
    }
}
