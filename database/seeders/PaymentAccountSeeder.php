<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentAccounts;

class PaymentAccountSeeder extends Seeder
{
    public function run(): void
    {
        PaymentAccounts::create([
            'bank_name'           => 'BCA',
            'account_number'      => '1234567890',
            'account_holder_name' => 'PT. Koperasi PSM',
            'is_active'           => true,
            'qr_code_path'        => null,
        ]);

        PaymentAccounts::create([
            'bank_name'           => 'QRIS',
            'account_number'      => 'QRIS-001',
            'account_holder_name' => 'PT. Koperasi PSM',
            'is_active'           => true,
            'qr_code_path'        => 'paymentqrcontoh/contohqrcode.png',
        ]);
    }
}
