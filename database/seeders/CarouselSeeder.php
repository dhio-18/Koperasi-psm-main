<?php

namespace Database\Seeders;

use App\Models\CarouselImage;
use Illuminate\Database\Seeder;

class CarouselSeeder extends Seeder
{
      /**
       * Run the database seeds.
       */
      public function run(): void
      {
            // Hapus data lama jika ada
            CarouselImage::truncate();

            // Data carousel
            $carousels = [
                  [
                        'image_path' => 'carousel/slider1.jpg',
                        'order' => 1,
                        'is_active' => true,
                  ],
                  [
                        'image_path' => 'carousel/slider2.jpg',
                        'order' => 2,
                        'is_active' => true,
                  ],
                  [
                        'image_path' => 'carousel/slider3.jpg',
                        'order' => 3,
                        'is_active' => true,
                  ],
                  [
                        'image_path' => 'carousel/slider4.jpg',
                        'order' => 4,
                        'is_active' => true,
                  ],
            ];

            foreach ($carousels as $carousel) {
                  CarouselImage::create($carousel);
            }

            $this->command->info('Carousel images seeded successfully!');
      }
}
