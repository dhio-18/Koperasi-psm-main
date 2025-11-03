<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'id',
        'amount',
        'payment_proof',
        'transfer_date',
        'sender_name',
        'status',
        'admin_notes',
        'verified_by',
        'verified_at',
        'order_id',
        'payment_method_id',
    ];


    public function orders()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function paymentAccount()
    {
        return $this->belongsTo(PaymentAccounts::class, 'payment_account_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
