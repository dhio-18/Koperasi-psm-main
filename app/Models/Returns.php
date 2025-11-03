<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'id',
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

    protected $casts = [
        'images' => 'array',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
