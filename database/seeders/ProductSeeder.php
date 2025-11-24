<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\Categories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah sudah ada data untuk menghindari duplikasi
        if (Products::count() > 0) {
            $this->command->info('Products already exist. Skipping...');
            return;
        }

        // Ambil kategori yang sudah ada
        $categories = Categories::all()->keyBy('name');

        $products = [
            // Makanan Ringan
            [
                'category_id' => $categories['Makanan Ringan']->id ?? 1,
                'name' => 'Sedaap Mie Instan Goreng',
                'slug' => 'sedaap-mie-instan-goreng',
                'description' => 'Mie instan lezat dengan rasa goreng yang nikmat. Cocok untuk cemilan atau makanan cepat saji.',
                'price' => 3500,
                'stock' => 100,
                'weight' => 77,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Makanan Ringan']->id ?? 1,
                'name' => 'Chitato Snack Potato Chips',
                'slug' => 'chitato-snack-potato-chips',
                'description' => 'Keripik kentang renyah dengan berbagai pilihan rasa lezat.',
                'price' => 8000,
                'stock' => 75,
                'weight' => 60,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Makanan Ringan']->id ?? 1,
                'name' => 'Kopiko Coklat Powder',
                'slug' => 'kopiko-coklat-powder',
                'description' => 'Minuman coklat instan berkualitas premium dengan rasa coklat yang kaya.',
                'price' => 12000,
                'stock' => 50,
                'weight' => 250,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],

            // Minuman
            [
                'category_id' => $categories['Minuman']->id ?? 2,
                'name' => 'Coca-Cola Botol 1.5L',
                'slug' => 'coca-cola-botol-1.5l',
                'description' => 'Minuman soda cola yang segar dan berenergi dalam kemasan botol 1.5 liter.',
                'price' => 15000,
                'stock' => 60,
                'weight' => 1500,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Minuman']->id ?? 2,
                'name' => 'Sprite Lemon 1.5L',
                'slug' => 'sprite-lemon-1.5l',
                'description' => 'Minuman lemon yang menyegarkan dan berenergi dalam kemasan botol 1.5 liter.',
                'price' => 15000,
                'stock' => 55,
                'weight' => 1500,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Minuman']->id ?? 2,
                'name' => 'Aqua Mineral Water 1.5L',
                'slug' => 'aqua-mineral-water-1.5l',
                'description' => 'Air mineral murni yang segar dan berkualitas premium untuk kebutuhan sehari-hari.',
                'price' => 6000,
                'stock' => 200,
                'weight' => 1500,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],

            // Kopi & Teh
            [
                'category_id' => $categories['Kopi & Teh']->id ?? 3,
                'name' => 'Nescafe Black Coffee Premium',
                'slug' => 'nescafe-black-coffee-premium',
                'description' => 'Kopi instan premium dari Nescafe dengan rasa yang kaya dan aromatic.',
                'price' => 50000,
                'stock' => 40,
                'weight' => 200,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Kopi & Teh']->id ?? 3,
                'name' => 'Teh Botol Sosro',
                'slug' => 'teh-botol-sosro',
                'description' => 'Teh hitam asli dalam kemasan botol dengan cita rasa autentik dan menyegarkan.',
                'price' => 5000,
                'stock' => 150,
                'weight' => 350,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Kopi & Teh']->id ?? 3,
                'name' => 'Sariwangi Teh Celup Premium',
                'slug' => 'sariwangi-teh-celup-premium',
                'description' => 'Teh premium dalam bentuk celup yang praktis dan berkualitas tinggi.',
                'price' => 25000,
                'stock' => 80,
                'weight' => 150,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],

            // Noodles & Pasta
            [
                'category_id' => $categories['Noodles & Pasta']->id ?? 4,
                'name' => 'Indomie Goreng Original',
                'slug' => 'indomie-goreng-original',
                'description' => 'Mie goreng instan dengan rasa original yang telah disukai jutaan orang.',
                'price' => 3000,
                'stock' => 200,
                'weight' => 80,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Noodles & Pasta']->id ?? 4,
                'name' => 'Mie Telor Kancing Mentai',
                'slug' => 'mie-telor-kancing-mentai',
                'description' => 'Mie telor dengan telur yang lezat dan cita rasa mentai yang unik.',
                'price' => 4500,
                'stock' => 90,
                'weight' => 75,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Noodles & Pasta']->id ?? 4,
                'name' => 'Barilla Pasta Spaghetti',
                'slug' => 'barilla-pasta-spaghetti',
                'description' => 'Pasta premium dari Italia dengan kualitas terbaik dan rasa yang sempurna.',
                'price' => 22000,
                'stock' => 45,
                'weight' => 500,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],

            // Permen & Coklat
            [
                'category_id' => $categories['Permen & Coklat']->id ?? 5,
                'name' => 'Ricola Permen Herbal',
                'slug' => 'ricola-permen-herbal',
                'description' => 'Permen herbal yang menyegarkan dengan ekstrak tumbuhan alami.',
                'price' => 7000,
                'stock' => 120,
                'weight' => 50,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Permen & Coklat']->id ?? 5,
                'name' => 'Cadbury Coklat Milk',
                'slug' => 'cadbury-coklat-milk',
                'description' => 'Coklat susu premium dari Cadbury dengan cita rasa yang lezat dan creamy.',
                'price' => 15000,
                'stock' => 85,
                'weight' => 100,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Permen & Coklat']->id ?? 5,
                'name' => 'Mentos Fruity Candy',
                'slug' => 'mentos-fruity-candy',
                'description' => 'Permen buah dengan tekstur yang unik dan rasa yang segar.',
                'price' => 8000,
                'stock' => 110,
                'weight' => 38,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],

            // Beras & Tepung
            [
                'category_id' => $categories['Beras & Tepung']->id ?? 6,
                'name' => 'Beras Putih Premium 5kg',
                'slug' => 'beras-putih-premium-5kg',
                'description' => 'Beras putih pilihan dengan kualitas premium dan butir yang panjang.',
                'price' => 75000,
                'stock' => 30,
                'weight' => 5000,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Beras & Tepung']->id ?? 6,
                'name' => 'Tepung Terigu Cakra Kembar',
                'slug' => 'tepung-terigu-cakra-kembar',
                'description' => 'Tepung terigu berkualitas tinggi cocok untuk membuat berbagai jenis kue dan roti.',
                'price' => 35000,
                'stock' => 50,
                'weight' => 1000,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
            [
                'category_id' => $categories['Beras & Tepung']->id ?? 6,
                'name' => 'Gula Pasir 1kg',
                'slug' => 'gula-pasir-1kg',
                'description' => 'Gula pasir putih berkualitas premium untuk berbagai keperluan masak-memasak.',
                'price' => 12000,
                'stock' => 150,
                'weight' => 1000,
                'images' => 'products/contohproduk.png',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Products::create($product);
            $this->command->line("Product '{$product['name']}' created successfully.");
        }

        $this->command->info('All products seeded successfully! Total: ' . count($products));
    }
}
