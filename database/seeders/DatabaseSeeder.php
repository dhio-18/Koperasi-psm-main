<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan penting: User > Category > Product > PaymentAccount
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            PaymentAccountSeeder::class,
        ]);
    }
}
