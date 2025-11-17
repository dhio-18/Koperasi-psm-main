<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'id',
        'order_number',
        'status',
        'total_amount',
        'shipping_cost',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'invoice_path',
        'notes',
        'user_id',
        'confirmed_at',
        'rejection_reason',
        'auto_confirmed',
        'auto_confirmed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payments::class, 'order_id');
    }

    public function shipment()
    {
        return $this->hasOne(Shipments::class, 'order_id')->latestOfMany();
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class, 'order_id')->latest('created_at');
    }

    public function returns()
    {
        return $this->hasMany(Returns::class, 'order_id');
    }

}
