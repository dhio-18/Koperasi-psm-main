@extends('layouts.layout')

@section('title')
    <title>Produk</title>
@endsection

@section('main')
    <div class="min-h-screen w-full flex flex-col gap-y-6 p-6">
        <!-- Breadcrumb -->
        @if (request()->routeIs('products.index'))
            <x-breadcrumb />
        @elseif (request()->routeIs('products.category'))
            <x-breadcrumb :category="$category ?? null" />
        @else
            <x-breadcrumb :product="$product ?? null" :category="$product->category ?? null" />
        @endif


        <!-- Dropdown Urutkan -->
        <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
            <label for="sort" class="text-xs sm:text-sm text-gray-600 whitespace-nowrap">Urutkan:</label>

            <div class="relative">
                <select id="sort" name="sort"
                    class="appearance-none w-36 sm:w-48 md:w-56
                   bg-white border border-gray-300 rounded-md
                   px-3 py-2
                   text-xs sm:text-sm md:text-sm leading-tight text-gray-700
                   focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                   min-h-[40px]"
                    onchange="this.form.submit()">
                    @php($currentSort = request('sort', 'newest'))

                    <!-- Note: class di <option> tetap ditambahkan untuk jaga-jaga -->
                    <option value="newest" {{ $currentSort === 'newest' ? 'selected' : '' }} class="text-xs sm:text-sm">
                        Terbaru</option>
                    <option value="oldest" {{ $currentSort === 'oldest' ? 'selected' : '' }} class="text-xs sm:text-sm">
                        Terlama</option>
                    <option value="price_desc" {{ $currentSort === 'price_desc' ? 'selected' : '' }}
                        class="text-xs sm:text-sm">Harga Tertinggi</option>
                    <option value="price_asc" {{ $currentSort === 'price_asc' ? 'selected' : '' }}
                        class="text-xs sm:text-sm">Harga Terendah</option>
                    <option value="name_asc" {{ $currentSort === 'name_asc' ? 'selected' : '' }} class="text-xs sm:text-sm">
                        Nama A–Z</option>
                    <option value="name_desc" {{ $currentSort === 'name_desc' ? 'selected' : '' }}
                        class="text-xs sm:text-sm">Nama Z–A</option>
                </select>

                <!-- Chevron (opsional, biar lebih jelas tombol dropdown-nya) -->
                <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-500"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                </svg>
            </div>
        </form>


        <!-- Pertahankan query lain jika ada -->
        @foreach (request()->except(['page', 'sort']) as $key => $val)
            @if (is_array($val))
                @foreach ($val as $v)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endif
        @endforeach
        </form>

        <!-- Daftar Produk -->
        @if (isset($q) && count($products) == 0)
            <x-produk.empty-search />
        @elseif(isset($category) && count($products) == 0)
            <!-- Empty Category State -->
            <div class="flex items-center justify-center min-h-[80vh] px-4 text-center">
                <div>
                    <!-- Icon -->
                    <div class="flex justify-center mb-6">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-orange-100 to-red-50 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                    </div>

                    <!-- Title -->
                    <h3 class="text-3xl font-bold text-gray-900 mb-3">
                        Kategori Kosong
                    </h3>

                    <!-- Description -->
                    <p class="text-gray-600 mb-2">
                        Kategori <span class="font-semibold text-gray-900">"{{ $category->name }}"</span> belum memiliki
                        produk.
                    </p>
                    <p class="text-sm text-gray-500 mb-8 max-w-md mx-auto">
                        Produk akan segera ditambahkan. Silakan coba kategori lain atau kembali nanti.
                    </p>

                    <!-- Actions -->
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

    </div>
    </div>
@else
    <div
        class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-5 gap-x-4 gap-y-8 mt-2">
        @foreach ($products as $product)
            <x-produk.produk :product="$product" />
        @endforeach
    </div>
    @endif

    <!-- Pagination -->
    @php($p = $products->appends(request()->except('page')))
    <div class="my-6 flex justify-center">
        <nav class="flex flex-col items-center gap-3" aria-label="Pagination">
            <!-- Info Halaman (Di Atas) -->
            <div class="text-xs sm:text-sm text-gray-600 text-center">
                Halaman <span class="font-semibold text-gray-900">{{ $p->currentPage() }}</span>
                dari <span class="font-semibold text-gray-900">{{ $p->lastPage() }}</span>
            </div>

            <!-- Tombol Navigasi (Di Bawah) -->
            <div class="flex items-center gap-2">
                <!-- Tombol Sebelumnya -->
                @if ($p->onFirstPage())
                    <span
                        class="px-3 sm:px-4 py-2 text-xs sm:text-sm rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                        Sebelumnya
                    </span>
                @else
                    <a href="{{ $p->previousPageUrl() }}"
                        class="px-3 sm:px-4 py-2 text-xs sm:text-sm rounded-lg bg-white border border-gray-300 text-green-600 hover:bg-green-50 hover:border-green-500 transition-all duration-200">
                        Sebelumnya
                    </a>
                @endif

                <!-- Tombol Selanjutnya -->
                @if ($p->hasMorePages())
                    <a href="{{ $p->nextPageUrl() }}"
                        class="px-3 sm:px-4 py-2 text-xs sm:text-sm rounded-lg bg-green-600 text-white hover:bg-green-700 transition-all duration-200">
                        Selanjutnya
                    </a>
                @else
                    <span
                        class="px-3 sm:px-4 py-2 text-xs sm:text-sm rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                        Selanjutnya
                    </span>
                @endif
            </div>
        </nav>
    </div>
    </div>
@endsection
