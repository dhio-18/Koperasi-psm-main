<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarouselImage;

class CarouselImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah sudah ada data untuk menghindari duplikasi
        if (CarouselImage::count() > 0) {
            $this->command->info('Carousel images already exist. Skipping...');
            return;
        }

        $carouselImages = [
            [
                'image_path' => 'images/slider1.jpg',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'image_path' => 'images/slider2.jpg',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'image_path' => 'images/slider3.jpg',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'image_path' => 'images/slider4.jpg',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($carouselImages as $carousel) {
            CarouselImage::create($carousel);
            $this->command->line("Carousel image '{$carousel['image_path']}' created successfully.");
        }

        $this->command->info('All carousel images seeded successfully!');
    }
}
