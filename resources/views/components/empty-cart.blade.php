<div class="h-full flex flex-col items-center justify-center gap-4">
    <img src="{{ asset('svg/empty-cart.svg') }}" alt="empty cart" width="100" height="100">
    <p class="text-gray-500">Keranjang belanja Anda kosong</p>
    <a href="{{ route('products.index') }}"
        class="bg-white px-4 py-2 rounded-xl text-green-500 border border-green-500 hover:bg-green-500 hover:text-white transition-colors duration-200">
        Mulai Belanja
    </a>
</div>
