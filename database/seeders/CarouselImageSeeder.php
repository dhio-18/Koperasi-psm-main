<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarouselImage;

class CarouselImageSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah sudah ada data
        if (CarouselImage::count() > 0) {
            return;
        }

        // Tambahkan gambar carousel yang sudah ada
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
        }
    }
}
