<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['slug' => 'elektronik', 'image' => 'storage/app/public/categories/elektronik.jpg', 'name' => 'Elektronik', 'description' => 'Produk elektronik seperti HP, laptop, dll', 'is_active' => true],
            ['slug' => 'sembako', 'image' => 'storage/app/public/categories/sembako.jpg', 'name' => 'Sembako', 'description' => 'Berbagai jenis sembako dan kebutuhan sehari-hari', 'is_active' => true],
            ['slug' => 'umkm', 'image' => 'storage/app/public/categories/umkm.jpg', 'name' => 'UMKM', 'description' => 'Produk dari usaha mikro, kecil, dan menengah', 'is_active' => true],
            ['slug' => 'atk', 'image' => 'storage/app/public/categories/atk.jpg', 'name' => 'ATK', 'description' => 'Perlengkapan alat tulis kantor dan sekolah', 'is_active' => true],
            ['slug' => 'kesehatan', 'image' => 'storage/app/public/categories/kesehatan.jpg', 'name' => 'Kesehatan', 'description' => 'Produk kesehatan dan kebersihan', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            \App\Models\Categories::create($category);
        }
    }
}
