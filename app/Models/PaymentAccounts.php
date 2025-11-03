<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAccounts extends Model
{
    protected $table = 'payment_accounts';

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder_name',
        'is_active',
        'qr_code_path', // <- baru
    ];

    public function payments()
    {
        // NOTE: relasi di sini mengikuti kode awalmu.
        // Kolom 'payment_method_id' pada tabel payments belum ada di migration payments.
        return $this->hasMany(Payments::class, 'payment_method_id');
    }
}
