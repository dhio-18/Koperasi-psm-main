<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAdresses extends Model
{
    protected $table = 'user_adresses';

    protected $fillable = [
        'id',
        'label',
        'phone',
        'recipient_name',
        'address',
        'full_address',
        'house_number',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
