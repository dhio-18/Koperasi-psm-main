@extends('layouts.layout')

@section('title')
<title>Keranjang</title>
@endsection

@section('main')

@if ($cartItems->isEmpty())
<div class="min-h-screen flex items-center justify-center">
    <x-empty-cart />
</div>
@else
<form id="cart-form" action="{{ route('cart.checkout') }}" method="POST">
    @csrf
    @method('POST')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">

            <!-- Cart Header (Desktop) -->
            <div class="bg-green-500 text-white rounded-t-lg hidden md:block">
                <div class="grid grid-cols-12 gap-4 px-6 py-4 font-semibold">
                    <div class="col-span-1 flex items-center">
                        <input id="select-all" type="checkbox"
                            class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-200 focus:ring-2">
                        <label for="select-all" class="ml-2 text-sm">SEMUA</label>
                    </div>
                    <div class="col-span-2 text-center">PRODUK</div>
                    <div class="col-span-3 text-left">NAMA</div>
                    <div class="col-span-2 text-center">HARGA</div>
                    <div class="col-span-2 text-center">JUMLAH</div>
                    <div class="col-span-1 text-center">AKSI</div>
                    <div class="col-span-1 text-right pr-2">TOTAL</div>
                </div>
            </div>

            <!-- Cart Header (Mobile) -->
            <div class="bg-green-500 text-white rounded-t-lg md:hidden">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center">
                        <input id="select-all-sm" type="checkbox"
                            class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-200 focus:ring-2">
                        <label for="select-all-sm" class="ml-2 text-sm font-semibold">Pilih Semua</label>
                    </div>
                    <span class="text-sm opacity-90">Keranjang</span>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="divide-y divide-gray-200 bg-white rounded-b-lg">
                @foreach ($cartItems as $item)
                <div class="px-4 md:px-6 py-4 md:py-6">
                    <!-- GRID: mobile 6 cols, desktop 12 cols -->
                    <div class="grid grid-cols-6 md:grid-cols-12 gap-3 md:gap-4 items-center">

                        <!-- Checkbox -->
                        <div class="col-span-1 flex md:block">
                            <input type="checkbox" name="selected_items[]" value="{{ $item['id'] }}"
                                class="item-checkbox w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-200 focus:ring-2"
                                data-price="{{ $item->product->price }}" data-quantity="{{ $item->quantity }}"
                                data-item-id="{{ $item->id }}">
                        </div>

                        <!-- Image -->
                        <div class="col-span-2 md:col-span-2 flex justify-center">
                            @if ($item->product->images)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                onerror="this.onerror=null; this.src='{{ asset('produk/contohproduk.png') }}';"
                                class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg border border-gray-200">
                            @else
                            <div
                                class="w-16 h-16 md:w-20 md:h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-7 h-7 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        <!-- Name -->
                        <div class="col-span-3 md:col-span-3">
                            <h3 class="font-medium text-gray-900 leading-snug">
                                {!! html_entity_decode($item->product->name) !!}
                            </h3>
                            <!-- Harga (Mobile) -->
                            <div class="mt-1 md:hidden text-sm text-gray-600">
                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Price (Desktop) -->
                        <input type="hidden" name="prices[{{ $item->id }}]" value="{{ $item->product->price }}">
                        <div class="hidden md:flex md:col-span-2 justify-center">
                            <span class="text-gray-600">
                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Quantity + Stock -->
                        <div class="col-span-3 md:col-span-2 flex flex-col items-start md:items-center">
                            <div class="text-xs text-gray-500 italic mb-1">
                                Stok: {{ $item->product->stock }}
                            </div>
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <button type="button"
                                    class="px-3 py-1 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition"
                                    onclick="decreaseQuantity({{ $item['id'] }})">−</button>

                                <input type="number" id="quantity-{{ $item->id }}" name="quantities[{{ $item->id }}]"
                                    value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                    class="w-14 md:w-16 px-2 py-1 text-center border-0 focus:ring-0 focus:outline-none text-sm"
                                    data-item-id="{{ $item->id }}" data-price="{{ $item->product->price }}"
                                    onchange="updateQuantity({{ $item->id }}, this.value)">

                                <button type="button"
                                    class="px-3 py-1 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition"
                                    onclick="increaseQuantity({{ $item['id'] }}, {{ $item->product->stock }})">+</button>
                            </div>
                        </div>

                        <!-- Delete -->
                        <div class="col-span-1 md:order-none order-last flex justify-center md:justify-center">
                            <button type="button" onclick="deleteItem('{{ route('cart.destroy', $item->id) }}')"
                                class="text-gray-400 hover:text-red-500 transition-colors" aria-label="Hapus item">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>

                        <!-- Total -->
                        <div class="col-span-2 md:col-span-1 text-right md:text-right">
                            <span class="font-semibold text-gray-900 block md:inline" id="total-{{ $item->id }}">
                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Batch Actions -->
            <div class="mt-6 flex gap-4">
                <button type="submit" name="action" value="delete"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200"
                    onclick="return confirm('Apakah Anda yakin ingin menghapus item yang dipilih?')">
                    Hapus Terpilih
                </button>

                <button type="submit" name="action" value="update"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
                    Update Terpilih
                </button>
            </div>

            <!-- Cart Summary -->
            <div class="mt-8 flex justify-end">
                <div class="bg-white rounded-lg shadow-sm p-6 w-full max-w-md">
                    <div class="border-t pt-4">
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Total</span>
                            <span>Rp<span id="total-selected">0</span></span>
                        </div>
                    </div>

                    <button id="checkout-button" type="submit" name="action" value="checkout"
                        class="w-full mt-6 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Form Hapus -->
<form id="delete-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endif

<!-- Script Interaktif -->
<script>
    /* Hapus item */
        function deleteItem(actionUrl) {
            if (confirm('Apakah Anda yakin ingin menghapus item yang dipilih ?')) {
                const form = document.getElementById('delete-form');
                form.action = actionUrl;
                form.submit();
            }
        }

        /* Kontrol Jumlah */
        function increaseQuantity(itemId, max) {
            const input = document.getElementById(`quantity-${itemId}`);
            const currentValue = parseInt(input.value);
            if (currentValue < max) {
                input.value = currentValue + 1;
                updateItemTotal(itemId);
                updateSelectedTotal();
            }
        }

        function decreaseQuantity(itemId) {
            const input = document.getElementById(`quantity-${itemId}`);
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateItemTotal(itemId);
                updateSelectedTotal();
            }
        }

        function updateQuantity(itemId, quantity) {
            if (quantity < 1) {
                document.getElementById(`quantity-${itemId}`).value = 1;
            }
            updateItemTotal(itemId);
            updateSelectedTotal();
        }

        /* Hitung total per item */
        function updateItemTotal(itemId) {
            const quantityInput = document.getElementById(`quantity-${itemId}`);
            const quantity = parseInt(quantityInput.value);
            const price = parseFloat(quantityInput.dataset.price);
            const total = price * quantity;

            document.getElementById(`total-${itemId}`).textContent = 'Rp' + total.toLocaleString('id-ID');

            const checkbox = document.querySelector(`input[data-item-id="${itemId}"]`);
            if (checkbox) checkbox.dataset.quantity = quantity;
        }

        /* Hitung total semua item yang dicentang */
        function updateSelectedTotal() {
            const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
            let total = 0;

            selectedCheckboxes.forEach(cb => {
                total += parseFloat(cb.dataset.price) * parseInt(cb.dataset.quantity);
            });

            document.getElementById('total-selected').textContent = total.toLocaleString('id-ID');
        }

        /* Sinkronisasi Pilih Semua (Desktop & Mobile) */
        const masterDesktop = document.getElementById('select-all');
        const masterMobile = document.getElementById('select-all-sm');

        function setAll(checked) {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = checked);
            updateSelectedTotal();
        }

        function syncMasters() {
            const all = document.querySelectorAll('.item-checkbox');
            const checked = document.querySelectorAll('.item-checkbox:checked');
            const allChecked = all.length > 0 && all.length === checked.length;
            if (masterDesktop) masterDesktop.checked = allChecked;
            if (masterMobile) masterMobile.checked = allChecked;
        }

        if (masterDesktop) {
            masterDesktop.addEventListener('change', e => {
                setAll(e.target.checked);
                if (masterMobile) masterMobile.checked = e.target.checked;
            });
        }

        if (masterMobile) {
            masterMobile.addEventListener('change', e => {
                setAll(e.target.checked);
                if (masterDesktop) masterDesktop.checked = e.target.checked;
            });
        }

        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.addEventListener('change', () => {
                updateSelectedTotal();
                syncMasters();
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            updateSelectedTotal();
            syncMasters();
        });
</script>
@endsection