@extends('layouts.admin-layout')

@section('title')
    <title>Manajemen Produk</title>
@endsection

@section('main')
    <div x-data="productManager()" class="container mx-auto px-4 pt-8 flex flex-col min-h-screen">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Produk</h1>
        </div>

        <!-- Error Alert -->
        <x-admin.error-validation />

        <!-- Search and Filters -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[1fr_auto_auto_auto_auto] gap-4 mb-6">

            <!-- Search Input -->
            <div class="flex-1">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery" @input="filterProducts" placeholder="Cari produk..."
                        class="shadow-lg w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg
                 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-colors" />
                </div>
            </div>

            <!-- Status Filter -->
            <div class="relative w-full md:w-auto">
                <select id="statusFilter"
                    onchange="window.location.href='{{ route('admin.products') }}?status=' + this.value"
                    class="shadow-lg appearance-none bg-white border border-gray-300 rounded-lg
               px-4 py-3 pr-10 focus:ring-2 focus:ring-green-500 focus:border-green-500
               outline-none transition-colors min-w-48 w-full md:w-auto">
                    <option value="active" {{ request('status', 'active') === 'active' ? 'selected' : '' }}>Produk Aktif
                    </option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Produk Non-Aktif
                    </option>
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Produk</option>
                </select>
                <!-- caret -->
                <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="relative w-full md:w-auto">
                <select x-model="categoryFilter" @change="filterProducts"
                    class="shadow-lg appearance-none bg-white border border-gray-300 rounded-lg
               px-4 py-3 pr-10 focus:ring-2 focus:ring-green-500 focus:border-green-500
               outline-none transition-colors min-w-48 w-full md:w-auto">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <!-- caret -->
                <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Sort Filter -->
            <div class="relative w-full md:w-auto">
                <select x-model="sortFilter" @change="filterProducts"
                    class="shadow-lg appearance-none bg-white border border-gray-300 rounded-lg
               px-4 py-3 pr-10 focus:ring-2 focus:ring-green-500 focus:border-green-500
               outline-none transition-colors min-w-32 w-full md:w-auto">
                    <option value="terbaru">Terbaru</option>
                    <option value="terlama">Terlama</option>
                    <option value="harga_rendah">Harga Terendah</option>
                    <option value="harga_tinggi">Harga Tertinggi</option>
                    <option value="nama_az">Nama A-Z</option>
                    <option value="nama_za">Nama Z-A</option>
                </select>
                <!-- caret -->
                <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Add Button -->
            <button x-on:click="showAddModal = true"
                class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 shadow-lg rounded-lg
             flex items-center justify-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Produk
            </button>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 max-h-screen overflow-auto">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-green-50">
                        <tr class="text-gray-900">
                            <th class="px-6 py-4 text-left text-sm font-semibold">Gambar</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama Produk</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Harga</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Stok</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Kadaluarsa</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="product in paginatedProducts" :key="product.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Product Image -->
                                <td class="px-6 py-4">
                                    <img :src="baseUrl + product.images" alt="product image"
                                        class="w-20 h-20 object-cover rounded">
                                </td>

                                <!-- Product Name -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900" x-text="product.name"></div>
                                </td>

                                <!-- Price -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900" x-text="formatCurrency(product.price)"></div>
                                </td>

                                <!-- Stock -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900" x-text="product.stock"></div>
                                </td>

                                <!-- Expired Date -->
                                <td class="px-6 py-4">
                                    <template x-if="product.expired_date">
                                        <div class="text-sm"
                                             :class="new Date(product.expired_date) < new Date() ? 'text-red-600 font-semibold' : 'text-gray-900'"
                                             x-text="formatDate(product.expired_date)">
                                        </div>
                                    </template>
                                    <template x-if="!product.expired_date">
                                        <div class="text-sm text-gray-400">-</div>
                                    </template>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-6 py-4">
                                    <span x-show="product.is_active"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                    <span x-show="!product.is_active"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Non-Aktif
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <!-- Toggle Status Button (Eye Icon) -->
                                        <form :action="baseUrl + 'admin/product/' + product.id + '/toggle-status'"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                :title="product.is_active ? 'Nonaktifkan Produk' : 'Aktifkan Produk'"
                                                class="transition-colors p-1 rounded"
                                                :class="product.is_active ? 'text-green-600 hover:text-green-400' :
                                                    'text-gray-400 hover:text-gray-600'">
                                                <!-- Eye Icon (Active) -->
                                                <svg x-show="product.is_active" class="w-5 h-5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                <!-- Eye Off Icon (Inactive) -->
                                                <svg x-show="!product.is_active" class="w-5 h-5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Edit Button -->
                                        <button type="button" @click="openEditModal(product)"
                                            class="text-blue-600 hover:text-blue-400 transition-colors p-1 rounded"
                                            title="Edit Produk">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <form :action="baseUrl + 'admin/product/' + product.id" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus produk ini?');"
                                                class="text-red-600 hover:text-red-400 transition-colors p-1 rounded"
                                                title="Hapus Produk">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <x-admin.empty-table data="filteredProducts" />

        </div>

        <!-- Add Modal -->
        <x-admin.modal.product.product-modal show="showAddModal" categories="categories" />

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
            style="display: none; backdrop-filter: blur(4px);" @click.self="showEditModal = false;">
            <!-- Modal Content -->
            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-400 delay-75"
                x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-90 translate-y-8"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" @click.stop>
                <!-- Modal Header -->
                <div
                    class="flex justify-between items-center p-6 border-b border-gray-200 sticky top-0 bg-white rounded-t-lg">
                    <h2 class="text-lg font-semibold text-gray-900">Edit Produk</h2>
                    <button @click="showEditModal = false;"
                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form x-ref="productForm" :action="baseUrl + 'admin/product/' + editData.id" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <!-- Nama Produk -->
                        <div class="mb-4">
                            <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Produk
                            </label>
                            <input x-model="editData.name" type="text" id="nama_produk" name="name"
                                value="{{ old('name') }}" placeholder="Masukkan nama produk"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                required>

                            @error('name')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror

                        </div>

                        <!-- Kategori -->
                        <div class="mb-4">
                            <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori
                            </label>
                            <div class="">
                                <select id="kategori" name="category_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 appearance-none bg-white"
                                    required>
                                    <template x-for="category in categories" :key="category.id">

                                        <option :value="category.id" x-text="category.name"
                                            :selected="editData.category_id == category.id"></option>
                                    </template>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </div>
                            </div>

                            @error('category_id')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div class="mb-4">
                            <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input x-model="editData.price" type="number" id="harga" name="price"
                                    placeholder="0" step="any" min="0" value="{{ old('price') }}"
                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                    required>
                            </div>

                            @error('price')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stok -->
                        <div class="mb-4">
                            <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                                Stok
                            </label>
                            <input x-model="editData.stock" type="number" id="stok" name="stock"
                                placeholder="0" min="0" value="{{ old('stock') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                required>

                            @error('stock')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Expired (Optional) -->
                        <div class="mb-4">
                            <label for="expired_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Kadaluarsa <span class="text-gray-400 text-xs">(Opsional)</span>
                            </label>
                            <input x-model="editData.expired_date" type="date" id="expired_date" name="expired_date"
                                value="{{ old('expired_date') }}"
                                min="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">

                            @error('expired_date')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea x-model="editData.description" id="deskripsi" name="description" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 resize-none"
                                placeholder="Masukkan deskripsi produk...">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gambar Produk -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Produk
                            </label>

                            <!-- Image Preview Area -->
                            <div
                                class="w-full h-48 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex items-center justify-center relative overflow-hidden">
                                <!-- Image Preview -->
                                <div x-show="editData.imagePreview || editData.currentImage"
                                    class="w-full h-full flex justify-center items-center relative">
                                    <img :src="editData.imagePreview || getImageUrl()" alt="Preview"
                                        class="w-full h-full object-contain rounded">
                                </div>

                                <!-- Placeholder -->
                                <div x-show="!editData.imagePreview && !editData.currentImage" class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Product Image</p>
                                </div>
                            </div>

                            <!-- File Input (Hidden) -->
                            <input type="file" x-ref="imageInput" name="images" accept="image/*"
                                @change="handleImageUpload($event)" class="hidden">

                            <!-- Upload Buttons -->
                            <div class="flex gap-2 mt-3">
                                <button type="button" @click="removeImage()"
                                    class="px-4 py-2 text-sm border border-red-300 text-red-600 hover:bg-red-50 rounded-md transition-colors duration-200">
                                    Hapus Gambar
                                </button>
                                <button type="button" @click="$refs.imageInput.click()"
                                    class="px-4 py-2 text-sm border border-green-300 text-green-600 hover:bg-green-50 rounded-md transition-colors duration-200">
                                    Upload Gambar
                                </button>
                            </div>

                            @error('images')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror

                        </div>

                    </div>
                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 border-t border-gray-200 p-4">
                        <button type="button" @click="showEditModal = false; resetForm()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors duration-200">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-auto">
            <x-admin.pagination data="filteredProducts" />
        </div>
    </div>

    <script>
        function productManager() {
            return {
                baseUrl: '{{ asset('') }}',
                // data
                categories: @json($categories),
                products: @json($products),
                statusFilter: '{{ $statusFilter ?? 'active' }}',

                // filters
                searchQuery: '',
                categoryFilter: '',
                sortFilter: 'terbaru',

                // Pagination states
                currentPage: 1,
                itemsPerPage: 5,
                filteredProducts: [],

                // Modal states
                showAddModal: false,
                showEditModal: false,
                editData: {
                    id: '',
                    name: '',
                    category_id: '',
                    price: '',
                    stock: '',
                    expired_date: '',
                    description: '',
                    currentImage: null,
                    imagePreview: null,
                },


                validationErrors: @json($errors->toArray()),

                // pagination
                get init() {
                    this.filteredProducts = this.sortProducts(this.products);

                    // Restore pagination state from localStorage
                    const savedPage = localStorage.getItem('product_current_page');
                    if (savedPage && this.statusFilter === localStorage.getItem('product_status_filter')) {
                        this.currentPage = parseInt(savedPage);
                    } else {
                        this.currentPage = 1;
                    }

                    // Save current status filter
                    localStorage.setItem('product_status_filter', this.statusFilter);

                    // Check if there are validation errors and open the appropriate modal
                    if (Object.keys(this.validationErrors).length > 0) {
                        // Check session or old input to determine which modal to open
                        @if (session('_method') === 'PUT')
                            this.showEditModal = true;
                        @else
                            this.showAddModal = true;
                        @endif
                    }
                },

                get filterProducts() {
                    let filtered = this.products;

                    // Filter by search query
                    if (this.searchQuery) {
                        filtered = filtered.filter(product =>
                            product.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            product.price.toString().includes(this.searchQuery) ||
                            product.stock.toString().includes(this.searchQuery) ||
                            product.description.toLowerCase().includes(this.searchQuery.toLowerCase())
                        );
                    }

                    // Filter by category
                    if (this.categoryFilter) {
                        filtered = filtered.filter(product => product.category.name === this.categoryFilter);
                    }

                    this.filteredProducts = this.sortProducts(filtered);
                    this.currentPage = 1;
                },

                get paginatedProducts() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredProducts.slice(start, end);
                },

                sortProducts(products) {
                    const sorted = [...products];

                    switch (this.sortFilter) {
                        case 'terbaru':
                            return sorted.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                        case 'terlama':
                            return sorted.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                        case 'harga_rendah':
                            return sorted.sort((a, b) => a.price - b.price);
                        case 'harga_tinggi':
                            return sorted.sort((a, b) => b.price - a.price);
                        case 'nama_az':
                            return sorted.sort((a, b) => a.name.localeCompare(b.name));
                        case 'nama_za':
                            return sorted.sort((a, b) => b.name.localeCompare(a.name));
                        default:
                            return sorted;
                    }
                },
                get totalPages() {
                    return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
                },

                getPageNumbers() {
                    const pages = [];
                    const maxVisible = 5;
                    let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
                    let end = Math.min(this.totalPages, start + maxVisible - 1);

                    if (end - start + 1 < maxVisible) {
                        start = Math.max(1, end - maxVisible + 1);
                    }

                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }
                    return pages;
                },

                goToPage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                        localStorage.setItem('product_current_page', page);
                    }
                },

                previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        localStorage.setItem('product_current_page', this.currentPage);
                    }
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                        localStorage.setItem('product_current_page', this.currentPage);
                    }
                },

                formatCurrency(price) {
                    const numPrice = typeof price === 'string' ? parseFloat(price) : price;

                    return 'Rp ' + numPrice.toLocaleString('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                },

                formatDate(dateString) {
                    if (!dateString) return '-';

                    const date = new Date(dateString);
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                    const day = date.getDate();
                    const month = months[date.getMonth()];
                    const year = date.getFullYear();

                    return `${day} ${month} ${year}`;
                },

                getStatusClass(status) {
                    if (status == "Aktif") {
                        return 'bg-green-100 text-green-800';
                    } else if (status == "Habis") {
                        return 'bg-red-100 text-red-800';
                    } else if (status == "Nonaktif") {
                        return 'bg-gray-100 text-gray-800';
                    }
                },


                /**
                 *  Edit modal handlers.
                 */

                openEditModal(product) {
                    this.editData.id = product.id;
                    this.editData.name = product.name;
                    this.editData.category_id = product.category.id;
                    this.editData.price = product.price;
                    this.editData.stock = product.stock;
                    this.editData.expired_date = product.expired_date || '';
                    this.editData.description = product.description;
                    this.editData.currentImage = product.images;
                    this.editData.imagePreview = null;

                    this.showEditModal = true;
                },

                getImageUrl() {
                    return this.editData.currentImage ? this.baseUrl + this.editData.currentImage : null;
                },

                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.editData.imagePreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },
                removeImage() {
                    this.editData.imagePreview = null;
                    this.editData.currentImage = null;
                    this.$refs.imageInput.value = '';
                },
            }
        }
    </script>
@endsection
