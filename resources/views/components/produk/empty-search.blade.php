@props([])

<!-- Content Area -->
<div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">
    <div class="text-center max-w-md mx-auto">
        {{-- Search Icon with X --}}
        <div class="mb-8">
            <div class="relative inline-block">
                <svg class="w-24 h-24 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                {{-- X mark overlay --}}
                <div class="absolute -top-2 -right-2 bg-red-100 rounded-full p-1">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Main Message --}}
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            Produk Tidak Ditemukan
        </h2>

        <p class="text-gray-600 mb-6 leading-relaxed">
            Maaf, kami tidak dapat menemukan produk yang Anda cari.
            Coba gunakan kata kunci yang berbeda atau jelajahi kategori produk kami.
        </p>

        {{-- Search Suggestions --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8 text-left">
            <h3 class="font-semibold text-blue-800 mb-3">Saran Pencarian:</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Periksa ejaan kata kunci</li>
                <li>• Gunakan kata kunci yang lebih umum</li>
                <li>• Coba sinonim atau kata serupa</li>
                <li>• Kurangi jumlah kata kunci</li>
            </ul>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200">
                Lihat Semua Produk
            </a>

            <a href="{{ route('home') }}"
                class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:border-green-500 hover:text-green-600 transition-all duration-200">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
