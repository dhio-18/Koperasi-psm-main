{{-- resources/views/components/product-card.blade.php --}}
@props(['id', 'slug', 'name', 'image', 'price', 'stock' => null])

<div x-data="{
    qty: 1,
    min: 1,
    max() { return {{ $stock ? (int) $stock : 'Infinity' }}; },
    inc() { if (this.qty < this.max()) this.qty++ },
    dec() { if (this.qty > this.min) this.qty-- }
}"
    class="h-full rounded-xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition flex flex-col">

    {{-- Link ke detail --}}
    <a href="{{ route('products.show', ['slug' => $slug]) }}" class="block">
        <div class="aspect-[4/5] w-full overflow-hidden rounded-t-xl">
            {{-- ✅ BENAR - Langsung pakai $image tanpa cek Str::startsWith --}}
            <img src="{{ $image }}"
                alt="{!! html_entity_decode($name) !!}" 
                onerror="this.onerror=null; this.src='{{ asset('produk/contohproduk.png') }}';"
                loading="lazy" 
                class="h-full w-full object-cover">
        </div>
    </a>

    {{-- Info --}}
    <div class="flex-1 p-3 flex flex-col">
        <a href="{{ route('products.show', ['slug' => $slug]) }}" class="block">
            <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 min-h-[2.5rem]">
                {!! html_entity_decode($name) !!}
            </h3>
        </a>

        <div class="mt-1 text-green-700 font-bold">
            Rp {{ number_format($price, 0, ',', '.') }}
        </div>

        {{-- Stepper Qty --}}
        <div class="mt-3 inline-flex items-center gap-2">
            <button type="button" @click="dec"
                class="h-9 w-9 md:h-8 md:w-8 inline-flex items-center justify-center rounded-md
                           border border-gray-300 text-gray-700 hover:bg-gray-50">
                −
            </button>

            <input x-model.number="qty" inputmode="numeric" pattern="[0-9]*"
                @change="if(qty < min) qty = min; if(isFinite(max()) && qty > max()) qty = max();"
                class="w-14 text-center h-9 md:h-8 rounded-md border border-gray-300" aria-label="Jumlah" />

            <button type="button" @click="inc"
                class="h-9 w-9 md:h-8 md:w-8 inline-flex items-center justify-center rounded-md
                           border border-gray-300 text-gray-700 hover:bg-gray-50">
                +
            </button>

            @if ($stock !== null)
                <span class="ml-2 text-xs text-gray-500">Stok: {{ $stock }}</span>
            @endif
        </div>

        {{-- Aksi --}}
        <div class="mt-auto pt-3 flex items-center justify-end gap-2">

            {{-- Beli sekarang (petir) --}}
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $id }}">
                <input type="hidden" name="price" value="{{ $price }}">
                <input type="hidden" name="qty" x-model="qty">
                <button type="submit" title="Beli sekarang" aria-label="Beli sekarang"
                    class="inline-flex items-center justify-center h-9 w-9 md:h-8 md:w-8 rounded-md
                               bg-gray-200 hover:bg-gray-300 text-gray-800 transition
                               focus:outline-none focus:ring-2 focus:ring-gray-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z" />
                    </svg>
                </button>
            </form>

            {{-- Tambah ke keranjang --}}
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $id }}">
                <input type="hidden" name="price" value="{{ $price }}">
                <input type="hidden" name="qty" x-model="qty">
                <button type="submit" title="Tambah ke keranjang" aria-label="Tambah ke keranjang"
                    class="inline-flex items-center justify-center h-9 w-9 md:h-8 md:w-8 rounded-md
                               bg-green-600 hover:bg-green-700 text-white transition
                               focus:outline-none focus:ring-2 focus:ring-green-500">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 12.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>