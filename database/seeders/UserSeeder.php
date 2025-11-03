<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@koperasipsm.com',
            'password' => bcrypt('superadmin.psm'),
            'role' => 'super_admin'
        ]);

        User::create([
            'name' => 'Admin 1',
            'email' => 'admin1@koperasipsm.com',
            'password' => bcrypt('admin1.psm'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Admin 2',
            'email' => 'admin2@koperasipsm.com',
            'password' => bcrypt('admin2.psm'),
            'role' => 'admin'
        ]);
    }
}
