<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table = 'order_histories';

    protected $fillable = ['order_id', 'user_id', 'action', 'description'];

    public $timestamps = true;

    const UPDATED_AT = null; // Hanya created_at yang dipakai

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

