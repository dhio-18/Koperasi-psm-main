<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Products;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class __TestProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // sudah ada 6 produk di DB, kita tambah 94 produk random
        for ($i = 0; $i < 94; $i++) {
            $name = $faker->words(2, true); // contoh "Super Gadget"
            $slug = Str::slug($name . '-' . Str::random(5));

            Products::create([
                'slug' => $slug,
                'name' => ucfirst($name),
                'images' => 'produk/produk.png', // bisa random kalau mau
                'price' => $faker->numberBetween(10000, 100000), // harga random
                'stock' => $faker->numberBetween(1, 50), // stok random
                'category_id' => $faker->numberBetween(1, 5), // kategori random
            ]);
        }
    }
}