@extends('layouts.layout')

@section('title')
<title>Product Detail</title>
@endsection

@section('main')
<div class="container mx-auto px-3 sm:px-4 md:px-6 py-4 sm:py-6 md:py-8" x-data="{
        qty: 1,
        min: 1,
        max: {{ (int) ($product->stock ?? 0) ?: 'Infinity' }},
        inc() { if (this.qty < this.max) this.qty++ },
        dec() { if (this.qty > this.min) this.qty-- }
    }">

    <!-- Breadcrumb -->
    <x-breadcrumb :product="$product ?? null" :category="$product->category ?? null" />

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 md:gap-8 lg:gap-10 xl:gap-12 mt-4 sm:mt-6 md:mt-8">

        <!-- Product Image Section -->
        <div class="space-y-3 sm:space-y-4">
            <div class="aspect-square p-5 rounded-lg sm:rounded-xl md:rounded-2xl overflow-hidden">
                @if (isset($product) && $product->images)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                    onerror="this.onerror=null; this.src='{{ asset('produk/contohproduk.png') }}';"
                    class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 sm:w-20 md:w-24 h-16 sm:h-20 md:h-24 text-gray-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2z">
                        </path>
                    </svg>
                </div>
                @endif
            </div>

            <!-- Product Info (Mobile/Tablet) -->
            <div class="lg:hidden space-y-1.5 sm:space-y-2 px-1 sm:px-2">
                <p class="text-xs sm:text-sm text-gray-600">
                    <span class="font-medium">Kategori:</span>
                    {{ $product->category->name }}
                </p>
                <p class="text-xs sm:text-sm text-gray-600">
                    <span class="font-medium">Stok Tersedia:</span>
                    <span class="font-semibold text-gray-900">{{ $product->stock }}</span>
                </p>
                @if($product->expired_date)
                <p class="text-xs sm:text-sm text-gray-600 mt-1">
                    <span class="font-medium">Kadaluarsa:</span>
                    <span
                        class="font-semibold {{ \Carbon\Carbon::parse($product->expired_date)->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                        {{ \Carbon\Carbon::parse($product->expired_date)->format('d M Y') }}
                    </span>
                </p>
                @endif
            </div>
        </div>

        <!-- Product Details Section -->
        <div class="space-y-4 sm:space-y-5 md:space-y-6">
            <div>
                <h1
                    class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-3 sm:mb-4">
                    {!! html_entity_decode($product->name) !!}
                </h1>

                <div class="mb-4 sm:mb-5 md:mb-6">
                    <span class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Description Section -->
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-3">Deskripsi</h3>
                <div class="w-12 sm:w-16 h-0.5 bg-gray-300 mb-3 sm:mb-4"></div>

                <div class="text-gray-700 leading-relaxed space-y-3 sm:space-y-4 text-sm sm:text-base">
                    <div id="description-content">
                        @if (isset($product) && $product->description)
                        {!! nl2br(e($product->description)) !!}
                        @endif
                    </div>

                    <button type="button" id="toggle-description"
                        class="text-green-600 hover:text-green-700 font-medium transition-colors duration-200 text-sm sm:text-base"
                        onclick="toggleDescription()">
                        Lihat Selengkapnya
                    </button>
                </div>
            </div>

            <!-- Product Info (Desktop) -->
            <div class="hidden lg:block space-y-2 sm:space-y-3 pt-4 sm:pt-5 md:pt-6 border-t border-gray-200">
                <p class="text-sm md:text-base text-gray-600">
                    <span class="font-medium">Kategori:</span>
                    <span class="text-gray-900">{{ $product->category->name }}</span>
                </p>
                <p class="text-sm md:text-base text-gray-600">
                    <span class="font-medium">Stok Tersedia:</span>
                    <span class="font-semibold text-gray-900">{{ $product->stock }}</span>
                </p>
                @if($product->expired_date)
                <p class="text-sm md:text-base text-gray-600">
                    <span class="font-medium">Kadaluarsa:</span>
                    <span
                        class="font-semibold {{ \Carbon\Carbon::parse($product->expired_date)->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                        {{ \Carbon\Carbon::parse($product->expired_date)->format('d M Y') }}
                    </span>
                </p>
                @endif
            </div>

            <!-- Qty + Action Buttons -->
            <div class="pt-3 sm:pt-4 md:pt-5 space-y-3 sm:space-y-4">

                <!-- Stepper Qty -->
                <div class="inline-flex items-center gap-1.5 sm:gap-2">
                    <button type="button" @click="dec"
                        class="h-8 sm:h-9 md:h-10 w-8 sm:w-9 md:w-10 inline-flex items-center justify-center rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs sm:text-sm md:text-base">
                        −
                    </button>

                    <input x-model.number="qty" inputmode="numeric" pattern="[0-9]*"
                        @change="if(qty < min) qty = min; if(isFinite(max) && qty > max) qty = max;"
                        class="w-12 sm:w-14 md:w-16 text-center h-8 sm:h-9 md:h-10 rounded-md border border-gray-300 text-xs sm:text-sm md:text-base"
                        aria-label="Jumlah" />

                    <button type="button" @click="inc"
                        class="h-8 sm:h-9 md:h-10 w-8 sm:w-9 md:w-10 inline-flex items-center justify-center rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs sm:text-sm md:text-base">
                        +
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 md:gap-4">

                    {{-- Beli Sekarang --}}
                    <form action="{{ route('checkout') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="qty" x-model="qty">
                        <button type="submit"
                            class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 sm:py-2.5 md:py-3 px-3 sm:px-4 md:px-6 rounded-lg text-sm sm:text-base transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            Beli Sekarang
                        </button>
                    </form>

                    {{-- Tambah ke Keranjang --}}
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="qty" x-model="qty">
                        <button type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 sm:py-2.5 md:py-3 px-3 sm:px-4 md:px-6 rounded-lg text-sm sm:text-base transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Tambah Ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let isDescriptionExpanded = false;
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.getElementById('description-content');
            if (content && content.scrollHeight > 96) {
                content.style.maxHeight = '6rem';
                content.style.overflow = 'hidden';
                document.getElementById('toggle-description').style.display = 'block';
            } else {
                document.getElementById('toggle-description').style.display = 'none';
            }
        });

        function toggleDescription() {
            const content = document.getElementById('description-content');
            const button = document.getElementById('toggle-description');
            if (!content) return;
            if (isDescriptionExpanded) {
                content.style.maxHeight = '6rem';
                content.style.overflow = 'hidden';
                button.textContent = 'Lihat Selengkapnya';
                isDescriptionExpanded = false;
            } else {
                content.style.maxHeight = 'none';
                content.style.overflow = 'visible';
                button.textContent = 'Lihat Lebih Sedikit';
                isDescriptionExpanded = true;
            }
        }
</script>
@endsection
