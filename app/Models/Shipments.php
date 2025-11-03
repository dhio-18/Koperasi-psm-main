<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipments extends Model
{
    protected $table = 'shipments';

    protected $fillable = [
        'id',
        'tracking_number',
        'carrier',
        'status',
        'shipped_at',
        'delivered_at',
        'notes',
        'order_id',
    ];

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
