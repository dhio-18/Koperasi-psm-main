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
        @else
            <div
                class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-5 gap-x-4 gap-y-8 mt-2">
                @foreach ($products as $product)
                    <x-produk.produk id="{{ $product['id'] }}" name="{{ $product['name'] }}"
                        image="{{ $product['images'] }}" price="{{ $product['price'] }}" slug="{{ $product->slug }}" />
                @endforeach
            </div>
        @endif

        <!-- Pagination -->
        @php($p = $products->appends(request()->except('page')))
        <div class="my-6 flex justify-center">
            <nav class="flex items-center space-x-2" aria-label="Pagination">
                @if ($p->onFirstPage())
                    <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">Prev</span>
                @else
                    <a href="{{ $p->previousPageUrl() }}"
                        class="px-3 py-1 rounded-lg bg-white border text-green-600 hover:bg-green-50">
                        Prev
                    </a>
                @endif

                @foreach ($p->getUrlRange(1, $p->lastPage()) as $page => $url)
                    @if ($page == $p->currentPage())
                        <span class="px-3 py-1 rounded-lg bg-green-600 text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            class="px-3 py-1 rounded-lg bg-white border text-green-600 hover:bg-green-50">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($p->hasMorePages())
                    <a href="{{ $p->nextPageUrl() }}"
                        class="px-3 py-1 rounded-lg bg-white border text-green-600 hover:bg-green-50">Next</a>
                @else
                    <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
                @endif
            </nav>
        </div>
    </div>
@endsection
