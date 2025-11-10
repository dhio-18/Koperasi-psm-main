@extends('layouts.layout')

@section('title')
    <title>Checkout</title>
@endsection

@section('main')
    <div class="min-h-screen bg-gray-50 py-4 sm:py-8">
        <div class="max-w-2xl mx-auto px-3 sm:px-4">
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 md:p-8">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 text-center mb-2">Checkout Formulir</h1>
                <div class="w-16 sm:w-20 h-0.5 bg-gray-300 mx-auto mb-6 sm:mb-8"></div>

                <!-- Error Messages from Server -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-red-800 font-semibold mb-2">Terdapat kesalahan:</h3>
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="mb-8">

                        <!-- Persiapan data akun untuk Alpine -->
                        @php
                            $accounts = collect($paymentAccounts ?? [])
                                ->map(function ($a) {
                                    return [
                                        'id' => $a->id,
                                        'bank_name' => $a->bank_name,
                                        'account_holder_name' => $a->account_holder_name,
                                        'account_number' => $a->account_number,
                                        'qr' => $a->qr_code_path ? asset('storage/' . $a->qr_code_path) : null,
                                    ];
                                })
                                ->values();
                        @endphp

                        <!-- Info Pembayaran -->
                        <div x-data='paymentDrop({ accounts: @json($accounts) })'
                            class="bg-green-50 border border-green-200 rounded-xl p-5 shadow-sm mb-6">
                            <div class="flex items-center mb-3">
                                <div class="rounded-full bg-green-100 p-2 mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 7a1 1 0 012 0v4a1 1 0 01-2 0V7zm1 8a1 1 0 100-2 1 1 0 000 2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <h3 class="text-green-800 font-semibold text-lg">Info Pembayaran</h3>
                            </div>

                            <!-- Dropdown pilih akun -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Akun Pembayaran</label>
                                <select
                                    class="block w-full pl-3 pr-10 py-2 border border-green-200 rounded-lg focus:ring-green-500 focus:border-green-500 bg-white"
                                    x-model="selectedId" @change="onSelect()">
                                    <option value="">-- Pilih akun --</option>
                                    <template x-for="acc in accounts" :key="acc.id">
                                        <option :value="acc.id"
                                            x-text="`${acc.bank_name} — ${acc.account_holder_name}`"></option>
                                    </template>
                                </select>
                                @error('payment_account_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Detail akun terpilih -->
                            <template x-if="current">
                                <div class="space-y-4 text-sm text-gray-700">
                                    <!-- Bank -->
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">Bank</span>
                                        <span class="px-2 py-1 rounded bg-white border text-green-700 font-semibold"
                                            x-text="current.bank_name"></span>
                                    </div>

                                    <!-- Atas Nama -->
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">Atas Nama</span>
                                        <span class="text-gray-900 font-medium" x-text="current.account_holder_name"></span>
                                    </div>

                                    <!-- No. Rekening -->
                                    <div>
                                        <span class="block font-medium mb-1">No. Rekening</span>
                                        <button type="button" @click="copy(current.account_number)"
                                            class="w-full flex items-center justify-between bg-white border border-green-200 rounded-lg px-4 py-3 font-mono text-lg text-gray-900 hover:bg-green-100 transition">
                                            <span x-text="current.account_number"></span>
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                        <p x-show="copied" class="text-xs text-green-600 mt-1">Nomor rekening disalin!</p>
                                    </div>

                                    <!-- QR (opsional) - Bisa diklik untuk zoom -->
                                    <div class="flex justify-center" x-show="current.qr">
                                        <div class="text-center">
                                            <p class="text-xs text-gray-600 mb-2">Klik gambar untuk memperbesar</p>
                                            <img :src="current.qr" alt="QR Code" @click="showQrModal = true"
                                                class="w-32 h-32 object-cover rounded border cursor-pointer hover:opacity-80 transition-opacity">
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Hidden input -->
                            <input type="hidden" name="payment_account_id" :value="selectedId">

                            <!-- QR Code Modal -->
                            <div x-show="showQrModal"
                                x-transition:enter="transition ease-out duration-400"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                @click="showQrModal = false"
                                @keydown.escape.window="showQrModal = false"
                                class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4"
                                style="display: none; backdrop-filter: blur(8px);"
                                x-cloak>

                                <div @click.stop
                                    x-show="showQrModal"
                                    x-transition:enter="transition ease-out duration-400 delay-100"
                                    x-transition:enter-start="opacity-0 scale-90"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-90"
                                    class="relative bg-white rounded-2xl p-6 max-w-lg w-full shadow-2xl">
                                    <!-- Close Button -->
                                    <button type="button" @click="showQrModal = false"
                                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 bg-white rounded-full p-2 shadow-md">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>

                                    <!-- QR Code Image -->
                                    <div class="text-center">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">QR Code Pembayaran</h3>
                                        <img :src="current?.qr" alt="QR Code" class="w-full h-auto max-w-md mx-auto rounded-lg shadow-lg">
                                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                            <p class="text-sm font-medium text-gray-700" x-text="current?.bank_name"></p>
                                            <p class="text-sm text-gray-600" x-text="current?.account_holder_name"></p>
                                            <p class="text-sm font-mono text-gray-900" x-text="current?.account_number"></p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-3">Scan QR code ini untuk melakukan pembayaran</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nama Pengirim -->
                        <div class="mb-6">
                            <label for="sender_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pengirim <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="sender_name" name="sender_name" value="{{ old('sender_name') }}"
                                    class="block w-full pl-10 pr-3 py-3 rounded-lg focus:ring-green-500 focus:border-green-500 @error('sender_name') border-red-500 @enderror"
                                    placeholder="Masukkan nama lengkap Anda" required>
                            </div>
                            @error('sender_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pilihan Alamat -->
                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Alamat <span class="text-red-500">*</span>
                            </label>
                            <select id="address" name="address"
                                class="block w-full pl-3 pr-3 py-3 border rounded-lg focus:ring-green-500 focus:border-green-500 @error('address') border-red-500 @enderror"
                                required onchange="previewAddress()">
                                <option disabled selected>-- Pilih alamat --</option>
                                @foreach ($address as $addr)
                                    <option value="{{ $addr->id }}" data-detail="{{ $addr->address }}">
                                        <strong>{{ $addr->label }}</strong> - {{ Str::words($addr->address, 6, '...') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview Alamat -->
                        <div id="addressPreview" class="p-4 border rounded-lg bg-gray-50 text-gray-700 hidden"></div>

                    </div>

                    <!-- Detail Produk Section -->
                    <input type="hidden" name="order_items" value='@json($orderItems)'>
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Detail Produk</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-3 gap-4 text-sm font-medium text-gray-600 mb-4">
                                <div>Nama Barang</div>
                                <div class="text-center">Jumlah Barang</div>
                                <div class="text-right">Total Harga</div>
                            </div>

                            @forelse($orderItems ?? [] as $item)
                                <div class="grid grid-cols-3 gap-4 py-3 border-t border-gray-200 first:border-t-0">
                                    <div class="text-gray-700">{!! html_entity_decode($item['name']) !!}</div>
                                    <div class="text-center text-gray-700">{{ $item['quantity'] }}</div>
                                    <div class="text-right text-gray-700">Rp
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">Tidak ada produk</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Total Pembayaran -->
                    <input type="hidden" name="total_amount"
                        value="{{ array_sum(
                            array_map(function ($item) {
                                return $item['price'] * $item['quantity'];
                            }, $orderItems ?? []),
                        ) }}">
                    <div class=" mb-8">
                        <div class="border border-gray-300 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium text-gray-700">Total Pembayaran:</span>
                                <span class="text-xl font-bold text-gray-900">
                                    Rp
                                    {{ number_format(
                                        array_sum(
                                            array_map(function ($item) {
                                                return $item['price'] * $item['quantity'];
                                            }, $orderItems ?? []),
                                        ),
                                        0,
                                        ',',
                                        '.',
                                    ) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Bukti Transfer -->
                    <div class="mb-8">
                        <label for="payment_proof" class="block text-sm sm:text-base font-medium text-gray-700 mb-2">
                            Upload Bukti Transfer <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs sm:text-sm text-gray-500 mb-3 leading-relaxed">
                            Upload screenshot atau foto bukti pembayaran Anda untuk verifikasi
                        </p>

                        <!-- Upload Area -->
                        <div id="upload-area"
                            class="mt-1 flex justify-center px-4 sm:px-6 pt-6 sm:pt-8 pb-6 sm:pb-8 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 hover:bg-green-50 active:border-green-500 transition-all duration-200 cursor-pointer bg-white touch-manipulation">
                            <div class="space-y-2 sm:space-y-3 text-center w-full">
                                <div class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-400 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 sm:h-8 sm:w-8" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="text-xs sm:text-sm text-gray-600 px-2">
                                    <label for="payment_proof"
                                        class="relative cursor-pointer rounded-md font-semibold text-green-600 hover:text-green-500 active:text-green-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500 transition-colors">
                                        <span class="text-sm sm:text-base">Klik untuk pilih file</span>
                                        <input id="payment_proof" name="payment_proof" type="file" class="sr-only"
                                            accept="image/jpeg,image/jpg,image/png" capture="environment" required>
                                    </label>
                                    <p class="mt-1 text-gray-500 hidden sm:block">atau seret dan lepas file ke sini</p>
                                    <p class="mt-1 text-gray-500 sm:hidden">atau ambil foto langsung</p>
                                </div>
                                <div class="flex flex-col sm:flex-row items-center justify-center sm:space-x-2 space-y-1 sm:space-y-0 text-xs text-gray-500 px-2">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-center">Format: JPG, JPEG, PNG</span>
                                    <span class="hidden sm:inline">|</span>
                                    <span class="text-center">Maksimal 2MB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Area -->
                        <div id="preview-area" class="mt-4 hidden">
                            <div class="bg-gradient-to-br from-green-50 to-white rounded-xl border-2 border-green-200 shadow-sm p-3 sm:p-5">
                                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-3 sm:gap-4">
                                    <!-- Image Preview -->
                                    <div class="flex-shrink-0 w-full sm:w-auto">
                                        <div class="relative group cursor-pointer" id="preview-image-container">
                                            <img id="preview-image" src="" alt="Preview Bukti Transfer"
                                                class="w-full sm:w-32 h-auto sm:h-32 max-h-48 sm:max-h-32 object-cover rounded-lg border-2 border-green-300 shadow-md mx-auto transition-transform group-hover:scale-105">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all rounded-lg flex items-center justify-center pointer-events-none">
                                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                </svg>
                                            </div>
                                            <p class="text-xs text-center text-gray-500 mt-2 group-hover:text-green-600 transition-colors pointer-events-none">Klik untuk memperbesar</p>
                                        </div>
                                    </div>

                                    <!-- File Details -->
                                    <div class="flex-1 w-full min-w-0">
                                        <div class="flex flex-col sm:flex-row items-center sm:items-start mb-3">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <h3 class="font-semibold text-green-800 text-sm sm:text-base text-center sm:text-left">
                                                    Bukti Transfer Berhasil!
                                                </h3>
                                            </div>
                                        </div>

                                        <div class="space-y-2 text-xs sm:text-sm">
                                            <div class="flex flex-col sm:flex-row sm:items-start">
                                                <div class="flex items-start mb-1 sm:mb-0">
                                                    <svg class="w-4 h-4 text-gray-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <span class="font-medium text-gray-700 mr-2">Nama File:</span>
                                                </div>
                                                <div class="flex-1 pl-6 sm:pl-0">
                                                    <p id="preview-file-name" class="text-gray-600 break-all"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-gray-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-medium text-gray-700 mr-2">Ukuran:</span>
                                                <span id="preview-file-size" class="text-gray-600"></span>
                                            </div>
                                        </div>

                                        <div class="mt-4 flex flex-col sm:flex-row gap-2">
                                            <button type="button" id="remove-file"
                                                class="w-full sm:w-auto inline-flex items-center justify-center px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-white bg-red-500 hover:bg-red-600 active:bg-red-700 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 touch-manipulation">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span class="hidden sm:inline">Hapus & Ganti File</span>
                                                <span class="sm:hidden">Hapus File</span>
                                            </button>
                                            <button type="button" id="change-file"
                                                class="w-full sm:w-auto inline-flex items-center justify-center px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-green-700 bg-white border border-green-500 hover:bg-green-50 active:bg-green-100 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 touch-manipulation">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                                Pilih File Lain
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Error Message -->
                        @error('payment_proof')
                            <div class="mt-2 flex items-start sm:items-center p-2 sm:p-3 text-xs sm:text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0 mt-0.5 sm:mt-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="leading-relaxed">{{ $message }}</span>
                            </div>
                        @enderror

                        <!-- Validation Message Area -->
                        <div id="upload-error" class="mt-2 hidden items-start sm:items-center p-2 sm:p-3 text-xs sm:text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0 mt-0.5 sm:mt-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span id="upload-error-message" class="leading-relaxed"></span>
                        </div>
                    </div>

                    <!-- Payment Proof Image Modal -->
                    <div id="payment-proof-modal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden items-center justify-center p-4" style="backdrop-filter: blur(8px);">
                        <div class="relative max-w-4xl max-h-[90vh] w-full">
                            <!-- Close Button -->
                            <button type="button" id="close-proof-modal-btn" class="absolute -top-12 right-0 text-white hover:text-gray-300 bg-black bg-opacity-50 rounded-full p-2 transition-colors">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            <!-- Image Container -->
                            <div class="bg-white rounded-2xl p-4 shadow-2xl">
                                <div class="text-center mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900">Bukti Transfer</h3>
                                </div>
                                <img id="modal-proof-image" src="" alt="Bukti Transfer" class="w-full h-auto max-h-[70vh] object-contain rounded-lg">
                                <div class="mt-3 text-center">
                                    <p id="modal-proof-file-name" class="text-sm text-gray-600"></p>
                                    <p id="modal-proof-file-size" class="text-xs text-gray-500"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 sm:mt-8">
                        <button type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 active:bg-green-700 text-white font-semibold py-3 sm:py-4 px-4 sm:px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm sm:text-base shadow-md hover:shadow-lg touch-manipulation">
                            Kirim Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Alpine helper untuk dropdown akun & copy
        function paymentDrop({
            accounts
        }) {
            return {

                accounts: accounts || [],
                selectedId: '',
                current: null,
                copied: false,
                showQrModal: false,
                onSelect() {
                    this.current = this.accounts.find(a => String(a.id) === String(this.selectedId)) || null;
                },
                copy(text) {
                    navigator.clipboard.writeText(text).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 1500);
                    });
                }
            }
        }
    </script>
    <script>
        /**
         * Form validation dengan detail error message
         *
         */
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessages = [];

            // Validasi Payment Account
            const paymentAccountId = document.querySelector('[name="payment_account_id"]');
            if (!paymentAccountId || !paymentAccountId.value || paymentAccountId.value.trim() === '') {
                isValid = false;
                errorMessages.push('- Pilih akun pembayaran');
                const paymentSelect = document.querySelector('[x-model="selectedId"]');
                if (paymentSelect) {
                    paymentSelect.classList.add('border-red-500');
                }
            }

            // Validasi Nama Pengirim
            const senderName = document.querySelector('[name="sender_name"]');
            if (!senderName || !senderName.value.trim()) {
                isValid = false;
                errorMessages.push('- Masukkan nama pengirim');
                if (senderName) senderName.classList.add('border-red-500');
            } else {
                if (senderName) senderName.classList.remove('border-red-500');
            }

            // Validasi Alamat
            const address = document.querySelector('[name="address"]');
            if (!address || !address.value || address.value === '-- Pilih alamat --') {
                isValid = false;
                errorMessages.push('- Pilih alamat pengiriman');
                if (address) address.classList.add('border-red-500');
            } else {
                if (address) address.classList.remove('border-red-500');
            }

            // Validasi Bukti Transfer
            const paymentProof = document.querySelector('[name="payment_proof"]');
            if (!paymentProof || !paymentProof.files || paymentProof.files.length === 0) {
                isValid = false;
                errorMessages.push('- Upload bukti transfer');
                const uploadArea = document.getElementById('upload-area');
                if (uploadArea) {
                    uploadArea.classList.add('border-red-500');
                    setTimeout(() => {
                        uploadArea.classList.remove('border-red-500');
                    }, 3000);
                }
            }

            if (!isValid) {
                e.preventDefault();

                // Log untuk debugging
                console.log('Form validation failed:', errorMessages);

                // Tampilkan alert dengan detail error
                let alertMessage = '⚠️ Mohon lengkapi data berikut:\n\n' + errorMessages.join('\n');
                alert(alertMessage);

                // Scroll ke element pertama yang error
                const firstError = document.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                return false;
            }

            // Log data yang akan dikirim
            const formData = new FormData(e.target);
            console.log('Form data yang akan dikirim:');
            for (let [key, value] of formData.entries()) {
                if (key === 'payment_proof') {
                    console.log(key + ':', value.name, '(', value.size, 'bytes)');
                } else {
                    console.log(key + ':', value);
                }
            }

            // Jika valid, tampilkan loading indicator
            const submitBtn = e.target.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<div class="flex items-center justify-center"><svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Mengirim Pesanan...</span></div>';

                // Re-enable button setelah 30 detik jika tidak ada response (timeout)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 30000);
            }

            console.log('Form submitted successfully!');
            return true;
        });

        /**
         * Copy rekening number
         */

        function copyRekening() {
            const rekening = document.getElementById("rekeningNumber").innerText;
            navigator.clipboard.writeText(rekening).then(() => {
                const msg = document.getElementById("copyMsg");
                msg.classList.remove("hidden");
                setTimeout(() => msg.classList.add("hidden"), 2000);
            });
        }

        /**
         * Preview address
         *
         */

        function previewAddress() {
            const select = document.getElementById('address');
            const preview = document.getElementById('addressPreview');

            const selectedOption = select.options[select.selectedIndex];
            const detail = selectedOption.getAttribute('data-detail');

            if (detail) {
                preview.textContent = detail;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
                preview.textContent = "";
            }
        }

        /**
         * Handler image upload dengan validasi lengkap
         *
         */
        const fileInput = document.getElementById('payment_proof');
        const uploadArea = document.getElementById('upload-area');
        const previewArea = document.getElementById('preview-area');
        const previewImage = document.getElementById('preview-image');
        const previewFileName = document.getElementById('preview-file-name');
        const previewFileSize = document.getElementById('preview-file-size');
        const removeBtn = document.getElementById('remove-file');
        const changeBtn = document.getElementById('change-file');
        const uploadError = document.getElementById('upload-error');
        const uploadErrorMessage = document.getElementById('upload-error-message');

        // Handle file selection
        fileInput.addEventListener('change', handleFileSelect);

        // Handle click on upload area
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        // Handle drag & drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-green-500', 'bg-green-100');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-green-500', 'bg-green-100');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-green-500', 'bg-green-100');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                // Manually set files to input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                fileInput.files = dataTransfer.files;
                handleFileSelect();
            }
        });

        // Handle file selection dengan validasi
        function handleFileSelect() {
            const file = fileInput.files[0];
            if (!file) return;

            // Hide previous error
            hideError();

            // Validasi file type - hanya JPG, JPEG, PNG
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type.toLowerCase())) {
                showError('❌ Format file tidak didukung! Hanya JPG, JPEG, dan PNG yang diperbolehkan.');
                fileInput.value = '';
                return;
            }

            // Validasi file size - maksimal 2MB
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            if (file.size > maxSize) {
                showError('❌ Ukuran file terlalu besar! Maksimal 2MB. File Anda: ' + formatFileSize(file.size));
                fileInput.value = '';
                return;
            }

            // Validasi file size minimal - minimal 10KB
            const minSize = 10 * 1024; // 10KB
            if (file.size < minSize) {
                showError('⚠️ Ukuran file terlalu kecil! Pastikan gambar bukti transfer jelas dan dapat dibaca.');
                fileInput.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                previewFileName.textContent = file.name;
                previewFileSize.textContent = formatFileSize(file.size);

                // Smooth transition
                uploadArea.classList.add('hidden');
                previewArea.classList.remove('hidden');

                // Scroll to preview
                previewArea.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            };
            reader.onerror = () => {
                showError('❌ Gagal membaca file! Silakan coba lagi.');
                fileInput.value = '';
            };
            reader.readAsDataURL(file);
        }

        // Remove file
        removeBtn.addEventListener('click', () => {
            resetUpload();
        });

        // Change file - open file picker
        changeBtn.addEventListener('click', () => {
            fileInput.click();
        });

        // Reset upload state
        function resetUpload() {
            fileInput.value = '';
            previewImage.src = '';
            previewFileName.textContent = '';
            previewFileSize.textContent = '';
            uploadArea.classList.remove('hidden');
            previewArea.classList.add('hidden');
            hideError();
        }

        // Show error message
        function showError(message) {
            uploadErrorMessage.textContent = message;
            uploadError.classList.remove('hidden');
            uploadError.classList.add('flex');

            // Auto hide after 5 seconds
            setTimeout(() => {
                hideError();
            }, 5000);
        }

        // Hide error message
        function hideError() {
            uploadError.classList.add('hidden');
            uploadError.classList.remove('flex');
            uploadErrorMessage.textContent = '';
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Payment Proof Modal Functions
        function openProofModal() {
            const previewImage = document.getElementById('preview-image');
            const modalProofImage = document.getElementById('modal-proof-image');
            const modalProofFileName = document.getElementById('modal-proof-file-name');
            const modalProofFileSize = document.getElementById('modal-proof-file-size');
            const paymentProofModal = document.getElementById('payment-proof-modal');

            if (previewImage.src) {
                modalProofImage.src = previewImage.src;
                modalProofFileName.textContent = previewFileName.textContent;
                modalProofFileSize.textContent = previewFileSize.textContent;
                paymentProofModal.classList.remove('hidden');
                paymentProofModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeProofModal() {
            const paymentProofModal = document.getElementById('payment-proof-modal');
            paymentProofModal.classList.add('hidden');
            paymentProofModal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Event listeners for Payment Proof Modal
        document.addEventListener('DOMContentLoaded', function() {
            // Open modal when clicking preview image
            const previewContainer = document.getElementById('preview-image-container');
            if (previewContainer) {
                previewContainer.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openProofModal();
                });
            }

            // Close modal when clicking close button
            const closeProofBtn = document.getElementById('close-proof-modal-btn');
            if (closeProofBtn) {
                closeProofBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeProofModal();
                });
            }

            // Close modal when clicking background
            const paymentProofModal = document.getElementById('payment-proof-modal');
            if (paymentProofModal) {
                paymentProofModal.addEventListener('click', function(e) {
                    if (e.target === paymentProofModal) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeProofModal();
                    }
                });
            }

            // Close modal with ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const paymentProofModal = document.getElementById('payment-proof-modal');
                    if (paymentProofModal && !paymentProofModal.classList.contains('hidden')) {
                        e.preventDefault();
                        closeProofModal();
                    }
                }
            });
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Mobile touch optimization */
        @media (max-width: 640px) {
            /* Larger touch targets for mobile */
            #upload-area {
                min-height: 160px;
            }

            /* Prevent zoom on input focus */
            input[type="file"] {
                font-size: 16px;
            }

            /* Better spacing for small screens */
            .space-y-2 > * + * {
                margin-top: 0.75rem;
            }

            /* Optimize button touch targets */
            button {
                min-height: 44px; /* iOS recommended touch target */
            }
        }

        /* Tablet optimization */
        @media (min-width: 641px) and (max-width: 1024px) {
            #preview-image {
                max-width: 150px;
                max-height: 150px;
            }
        }

        /* Smooth transitions for better UX */
        #upload-area,
        #preview-area {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Image preview loading state */
        #preview-image {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }

        /* Better focus states for accessibility */
        button:focus-visible,
        input:focus-visible {
            outline: 2px solid #10b981;
            outline-offset: 2px;
        }

        /* Prevent text selection on buttons */
        button {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
@endsection
