@extends('layouts.layout')

@section('title')
    <title>Checkout</title>
@endsection

@section('main')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <h1 class="text-2xl font-bold text-gray-900 text-center mb-2">Checkout Formulir</h1>
                <div class="w-20 h-0.5 bg-gray-300 mx-auto mb-8"></div>

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

                                    <!-- QR (opsional) -->
                                    <div class="flex justify-center" x-show="current.qr">
                                        <img :src="current.qr" alt="QR"
                                            class="w-32 h-32 object-cover rounded border">
                                    </div>
                                </div>
                            </template>

                            <!-- Hidden input -->
                            <input type="hidden" name="payment_account_id" :value="selectedId">
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
                                    <div class="text-gray-700">{{ $item['name'] }}</div>
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
                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Bukti Transfer <span class="text-red-500">*</span>
                        </label>

                        <!-- Upload Area -->
                        <div id="upload-area"
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="payment_proof"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                        <span>Choose File</span>
                                        <input id="payment_proof" name="payment_proof" type="file" class="sr-only"
                                            accept="image/*" required onchange="updateFileName(this)">
                                    </label>
                                    <p class="pl-1" id="file-name">No File Chosen</p>
                                </div>
                                <p class="text-xs text-gray-500">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                            </div>
                        </div>

                        <!-- Preview Area -->
                        <div id="preview-area" class="mt-4 hidden">
                            <div class="flex justify-center items-center bg-white rounded-lg border-2 border-gray-200 p-4">
                                <div class="flex items-start gap-4">
                                    <img id="preview-image" src="" alt="Preview"
                                        class="w-60 h-auto object-cover rounded-lg border">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900 mb-1">File Dipilih:</h3>
                                        <p id="file-name" class="text-sm text-gray-600 mb-2"></p>
                                        <p id="file-size" class="text-xs text-gray-500 mb-3"></p>
                                        <button type="button" id="remove-file"
                                            class="px-3 py-1 text-sm text-red-600 hover:text-red-700 border border-red-300 rounded hover:border-red-400 transition-colors">
                                            Hapus File
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('payment_proof')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-4 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
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
         * Form validation
         *
         */
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = ['sender_name', 'address', 'payment_proof'];
            let isValid = true;

            requiredFields.forEach(function(fieldName) {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi.');
            }
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
         * Handler image upload
         *
         */
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'No File Chosen';
            document.getElementById('file-name').textContent = fileName;
        }

        const fileInput = document.getElementById('payment_proof');
        const uploadArea = document.getElementById('upload-area');
        const previewArea = document.getElementById('preview-area');
        const previewImage = document.getElementById('preview-image');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const removeBtn = document.getElementById('remove-file');

        // Handle file selection
        fileInput.addEventListener('change', handleFileSelect);

        // Handle drag & drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-green-400', 'bg-green-50');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-green-400', 'bg-green-50');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-green-400', 'bg-green-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect();
            }
        });

        // Handle file selection
        function handleFileSelect() {
            const file = fileInput.files[0];
            if (!file) return;

            // Validasi file
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar!');
                return;
            }

            if (file.size > 2 * 1024 * 1024) { // 2MB
                alert('File terlalu besar! Maksimal 2MB');
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);

                uploadArea.classList.add('hidden');
                previewArea.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        // Remove file
        removeBtn.addEventListener('click', () => {
            fileInput.value = '';
            previewImage.src = '';
            uploadArea.classList.remove('hidden');
            previewArea.classList.add('hidden');
        });

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
@endsection
