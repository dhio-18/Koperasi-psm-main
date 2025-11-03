<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';
    protected $fillable = ['id', 'quantity', 'price', 'subtotal', 'order_id', 'product_id'];

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
