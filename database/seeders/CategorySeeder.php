<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
/**
 * Run the database seeds.
 */
public function run(): void
{
    // Cek apakah sudah ada data untuk menghindari duplikasi
    if (Categories::count() > 0) {
        $this->command->info('Categories already exist. Skipping...');
        return;
    }

    $categories = [
        [
            'name' => 'Makanan Ringan',
            'slug' => 'makanan-ringan',
            'description' => 'Berbagai jenis makanan ringan dan snack berkualitas',
            'image' => 'category/contohcategory.png',
            'is_active' => true,
        ],
        [
            'name' => 'Minuman',
            'slug' => 'minuman',
            'description' => 'Koleksi minuman segar dan berkualitas',
            'image' => 'category/contohcategory.png',
            'is_active' => true,
        ],
        [
            'name' => 'Kopi & Teh',
            'slug' => 'kopi-teh',
            'description' => 'Kopi dan teh premium pilihan',
            'image' => 'category/contohcategory.png',
            'is_active' => true,
        ],
        [
            'name' => 'Noodles & Pasta',
            'slug' => 'noodles-pasta',
            'description' => 'Mie instan dan pasta berkualitas',
            'image' => 'category/contohcategory.png',
            'is_active' => true,
        ],
        [
            'name' => 'Permen & Coklat',
            'slug' => 'permen-coklat',
            'description' => 'Permen dan coklat favorit',
            'image' => 'category/contohcategory.png',
            'is_active' => true,
        ],
        [
            'name' => 'Beras & Tepung',
            'slug' => 'beras-tepung',
            'description' => 'Beras dan tepung berkualitas premium',
            'image' => 'category/contohcategory.png',
            'is_active' => true,
        ],
    ];

    foreach ($categories as $category) {
        Categories::create($category);
        $this->command->line("Category '{$category['name']}' created successfully.");
    }

    $this->command->info('All categories seeded successfully!');
}
}
