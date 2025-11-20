@extends('layouts.layout')

@section('title')
    <title>Koperasi PSM</title>
@endsection

@section('main')
    <div class="w-full max-w-7xl mx-auto flex flex-col gap-y-4 px-4 md:px-6 py-4">

        <!-- Hero Modern & Compact -->
        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-4 md:gap-6">
            <div class="space-y-3 order-2 md:order-1">
                <div class="inline-block px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full mb-2">
                    Produk Berkualitas
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight">
                    Selamat Datang di
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">
                        Koperasi PSM
                    </span>
                </h1>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                    Menyediakan berbagai produk berkualitas untuk memenuhi kebutuhan sehari-hari Anda dengan harga
                    terjangkau.
                </p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-semibold rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Belanja Sekarang
                    </a>
                    <a href="{{ route('about-us') }}"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-white border-2 border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:border-green-500 hover:text-green-600 transition-all duration-200">
                        Tentang Kami
                    </a>
                </div>
            </div>

            <!-- Carousel Modern & Sleek -->
            <div class="order-1 md:order-2" x-data="{
                images: [
                    @foreach ($carouselImages as $carousel)
                            '{{ asset('storage/' . $carousel->image_path) }}', @endforeach
                ],
                active: 0,
                next() { this.active = (this.active + 1) % this.images.length },
                prev() { this.active = (this.active - 1 + this.images.length) % this.images.length },
                start() { if (this.images.length > 1) { setInterval(() => this.next(), 5000) } }
            }" x-init="start()"
                class="relative w-full overflow-hidden rounded-2xl shadow-xl group">
                <!-- Container gambar dengan overlay gradient -->
                <div class="relative w-full h-48 sm:h-64 md:h-80 lg:h-96">
                    <template x-for="(image, index) in images" :key="index">
                        <div x-show="active === index" x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute inset-0">
                            <img :src="image" alt="Carousel Slide" class="w-full h-full object-cover rounded-2xl">
                            <!-- Gradient overlay untuk depth -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent rounded-2xl">
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Tombol Navigasi Modern -->
                <button @click="prev"
                    class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm hover:bg-white rounded-full p-2 shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                    <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button @click="next"
                    class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm hover:bg-white rounded-full p-2 shadow-lg opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110">
                    <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Progress Bar & Dots -->
                <div class="absolute bottom-4 inset-x-0 px-4">
                    <div class="flex justify-center gap-2">
                        <template x-for="(image, index) in images" :key="'dot-' + index">
                            <button @click="active = index" :class="active === index ? 'w-8 bg-white' : 'w-2 bg-white/50'"
                                class="h-2 rounded-full transition-all duration-300 hover:bg-white/80">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori Modern & Compact -->
        <div class="flex flex-col gap-3">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">Kategori Produk</h2>
                <p class="text-sm text-gray-500 mt-0.5">Temukan produk berdasarkan kategori</p>
            </div>

            <div
                class="relative rounded-2xl bg-gradient-to-br from-gray-50 to-white shadow-sm ring-1 ring-gray-100 p-4 md:p-5">
                <!-- Grid Kategori dengan hover effect -->
                <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-10 gap-3 md:gap-4">
                    @foreach ($categories as $item)
                        <x-kategori name="{{ $item['name'] }}" image="{{ $item['image'] }}" slug="{{ $item['slug'] }}" />
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Feature Cards Modern -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 py-2">
            <!-- Fast Delivery -->
            <div
                class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm ring-1 ring-gray-100 hover:shadow-md transition-shadow">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Pengiriman Cepat</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Proses pengiriman yang efisien</p>
                </div>
            </div>

            <!-- Quality Products -->
            <div
                class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm ring-1 ring-gray-100 hover:shadow-md transition-shadow">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Produk Berkualitas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Terjamin kualitas terbaik</p>
                </div>
            </div>

            <!-- Best Price -->
            <div
                class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm ring-1 ring-gray-100 hover:shadow-md transition-shadow">
                <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Harga Terjangkau</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Harga bersaing di pasaran</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Simpan token untuk digunakan di frontend -->
    <script>
        window.apiToken = "{{ session('api_token') }}";
    </script>
@endsection
