<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItems extends Model
{
    protected $table = 'return_items';
    protected $fillable = ['id', 'quantity', 'return_id', 'order_item_id'];

    public function returns()
    {
        return $this->belongsTo(Returns::class, 'return_id');
    }

    public function orderItems()
    {
        return $this->belongsTo(OrderItems::class, 'order_item_id');
    }
}
