@extends('layouts.layout')

@section('title')
    <title>Product Detail</title>
@endsection

@section('main')
    <div class="container mx-auto px-4 py-8" x-data="{
        qty: 1,
        min: 1,
        max: {{ (int) ($product->stock ?? 0) ?: 'Infinity' }},
        inc() { if (this.qty < this.max) this.qty++ },
        dec() { if (this.qty > this.min) this.qty-- }
    }">

        <!-- Breadcrumb -->
       <x-breadcrumb :product="$product ?? null" :category="$product->category ?? null" />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

            <!-- Product Image Section -->
            <div class="space-y-4">
                <div class="aspect-square bg-black rounded-2xl overflow-hidden">
                    @if (isset($product) && $product->images)
                        <img src="{{ asset($product->images) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2z">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Product Info (Mobile/Tablet) -->
                <div class="lg:hidden space-y-2">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Kategori:</span>
                        {{ $product->category->name }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Stok Tersedia:</span>
                        <span class="font-semibold text-gray-900">{{ $product->stock }}</span>
                    </p>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight mb-4">
                        {{ $product->name }}
                    </h1>

                    <div class="mb-6">
                        <span class="text-3xl lg:text-4xl font-bold text-gray-900">
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Description Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                    <div class="w-16 h-0.5 bg-gray-300 mb-4"></div>

                    <div class="text-gray-700 leading-relaxed space-y-4">
                        <div id="description-content">
                            @if (isset($product) && $product->description)
                                {!! nl2br(e($product->description)) !!}
                            @endif
                        </div>

                        <button type="button" id="toggle-description"
                            class="text-green-600 hover:text-green-700 font-medium transition-colors duration-200"
                            onclick="toggleDescription()">
                            Lihat Selengkapnya
                        </button>
                    </div>
                </div>

                <!-- Product Info (Desktop) -->
                <div class="hidden lg:block space-y-3 pt-4 border-t border-gray-200">
                    <p class="text-gray-600">
                        <span class="font-medium">Kategori:</span>
                        <span class="text-gray-900">{{ $product->category->name }}</span>
                    </p>
                    <p class="text-gray-600">
                        <span class="font-medium">Stok Tersedia:</span>
                        <span class="font-semibold text-gray-900">{{ $product->stock }}</span>
                    </p>
                </div>

                <!-- Qty + Action Buttons -->
                <div class="pt-4 space-y-4">

                    <!-- Stepper Qty -->
                    <div class="inline-flex items-center gap-2">
                        <button type="button" @click="dec"
                            class="h-10 w-10 inline-flex items-center justify-center rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">
                            −
                        </button>

                        <input x-model.number="qty" inputmode="numeric" pattern="[0-9]*"
                            @change="if(qty < min) qty = min; if(isFinite(max) && qty > max) qty = max;"
                            class="w-16 text-center h-10 rounded-md border border-gray-300" aria-label="Jumlah" />

                        <button type="button" @click="inc"
                            class="h-10 w-10 inline-flex items-center justify-center rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">
                            +
                        </button>

                        <span class="ml-2 text-sm text-gray-500">Stok: {{ $product->stock }}</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">

                        {{-- Beli Sekarang --}}
                        <form action="{{ route('checkout') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="qty" x-model="qty"> {{-- KUNCI: kirim qty --}}
                            <button type="submit"
                                class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                                Beli Sekarang
                            </button>
                        </form>

                        {{-- Tambah ke Keranjang --}}
                        <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="price" value="{{ $product->price }}">
                            <input type="hidden" name="qty" x-model="qty"> {{-- KUNCI: kirim qty --}}
                            <button type="submit"
                                class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
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
