@extends('layouts.layout')

@section('title')
    <title>Koperasi PSM</title>
@endsection

@section('main')
    <div class="w-full max-w-7xl mx-auto flex flex-col gap-y-6 px-4 md:px-6">

        <!-- Hero (lebih padat) -->
        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-6 py-6">
            <div class="space-y-4">
                <h1 class="text-[28px] leading-tight md:text-4xl font-bold text-gray-800">
                    Selamat Datang di Koperasi PSM
                </h1>
                <p class="text-gray-600 text-[15px] md:text-base">
                    Koperasi PSM menyediakan berbagai produk berkualitas untuk memenuhi kebutuhan Anda.
                </p>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors">
                    Jelajahi Produk
                </a>
            </div>

            <!-- Carousel Gambar (tinggi & gap lebih ringkas) -->
            <div
                x-data="{
                    images: [
                        @foreach($carouselImages as $carousel)
                            '{{ asset('storage/' . $carousel->image_path) }}',
                        @endforeach
                    ],
                    active: 0,
                    next() { this.active = (this.active + 1) % this.images.length },
                    prev() { this.active = (this.active - 1 + this.images.length) % this.images.length },
                    start() { if(this.images.length > 1) { setInterval(() => this.next(), 4000) } }
                }"
                x-init="start()"
                class="relative w-full overflow-hidden rounded-xl shadow-md mt-4 md:mt-0"
            >
                <!-- Container gambar -->
                <div class="relative w-full h-44 sm:h-56 md:h-72 lg:h-80">
                    <template x-for="(image, index) in images" :key="index">
                        <img
                            x-show="active === index"
                            x-transition.opacity
                            :src="image"
                            alt="Slide"
                            loading="lazy"
                            class="absolute inset-0 w-full h-full object-cover"
                        >
                    </template>
                </div>

                <!-- Tombol Navigasi (lebih kecil) -->
                <button @click="prev"
                        class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-1.5 text-gray-700">
                    ‹
                </button>
                <button @click="next"
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-1.5 text-gray-700">
                    ›
                </button>

                <!-- Dots (lebih rapat) -->
                <div class="absolute bottom-2 inset-x-0 flex justify-center gap-1.5">
                    <template x-for="(image, index) in images" :key="'dot-' + index">
                        <button
                            @click="active = index"
                            :class="active === index ? 'bg-green-600' : 'bg-gray-300'"
                            class="w-2.5 h-2.5 rounded-full transition-colors"
                            aria-label="Slide indicator">
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Kategori (padding & gap dipadatkan) -->
        <div class="flex flex-col">
            <p class="font-bold text-lg md:text-xl mb-3">Kategori</p>

            <div class="rounded-xl bg-white shadow-sm ring-1 ring-black/5 p-3 md:p-4">
                <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-10 gap-2 sm:gap-3 md:gap-4 lg:gap-5">
                    @foreach ($categories as $item)
                        <x-kategori
                            name="{{ $item['name'] }}"
                            image="{{ $item['image'] }}"
                            slug="{{ $item['slug'] }}" />
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Spacer lebih pendek -->
        <div class="h-4 md:h-6"></div>
    </div>

    <!-- Simpan token untuk digunakan di frontend -->
    <script>
        window.apiToken = "{{ session('api_token') }}";
    </script>
@endsection
