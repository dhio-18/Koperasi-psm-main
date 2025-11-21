<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'comments',
        'images',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at',
        'order_id',
        'user_id',
    ];

    /**
     * Cast attributes to native types
     */
    protected $casts = [
        'images' => 'array', // PENTING: Cast JSON ke array
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship dengan Order
     */
    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    /**
     * Relationship dengan User (pembuat return)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship dengan Admin yang memproses
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}