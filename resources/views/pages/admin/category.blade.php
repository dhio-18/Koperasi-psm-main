@extends('layouts.admin-layout')

@section('title')
    <title>Manajemen Kategori</title>
@endsection

@section('main')
    <div x-data="categoryManager()" class="container mx-auto px-4 pt-8 flex flex-col min-h-screen">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Kategori</h1>
        </div>

        <!-- Error Alert -->
        <x-admin.error-validation />

        <!-- Search Bar: non-sticky di mobile, sticky di desktop -->
        <div class="grid grid-cols-1 md:grid-cols-[1fr_auto] gap-4 mb-6">
            <!-- Input -->
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" x-model="searchQuery" @input="filterCategories" placeholder="Cari kategori..."
                    class="shadow-lg w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>

            <!-- Tombol -->
            <button type="button" x-on:click="openAddModal()"
                class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-3 shadow-lg rounded-lg
             flex items-center justify-center gap-2 w-full md:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Tambah Kategori</span>
            </button>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <!-- Table Header -->
                    <thead class="bg-green-50">
                        <tr class="text-gray-900">
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Icon</th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Nama Kategori</th>
                            <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider">Jumlah Produk
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider"></th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="category in paginatedCategories" :key="category.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img :src="baseUrl + 'storage/' + category.image" alt="image"
                                        class="w-10 h-10 object-cover rounded">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 font-medium" x-text="category.name"></span>
                                </td>
                                <td class="text-center px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 font-medium" x-text="category.product_count"></span>
                                </td>
                                <td class="text-center px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-3">
                                        <!-- Edit -->
                                        <button type="button" @click="openEditModal(category)"
                                            class="text-green-600 hover:text-green-500 transition-colors p-1 rounded"
                                            title="Edit Kategori" aria-label="Edit Kategori">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <!-- Delete -->
                                        <form :action="baseUrl + 'admin/category/' + category.id" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');"
                                                class="text-red-600 hover:text-red-500 transition-colors p-1 rounded"
                                                title="Hapus Kategori" aria-label="Hapus Kategori">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
        </div>


        <!-- Empty State -->
        <x-admin.empty-table data="filteredCategories" />

        <!-- Add Modal -->
        <x-admin.modal.category.category-modal show="showAddModal" :errors="$errors" />


        <!-- Edit Modal -->
        <div x-show="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="showEditModal = false" style="display: none;">
            <div x-show="showEditModal" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4" @click.stop>

                <!-- Header -->
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Edit Kategori</h2>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <form :action="baseUrl + 'admin/category/' + editData.id" method="POST" enctype="multipart/form-data"
                    @submit="validateEditForm($event)">
                    @csrf
                    @method('PUT')

                    <div class="p-6 max-h-[70vh] overflow-y-auto">

                        <!-- Icon -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>

                            <div
                                class="p-2 h-20 border border-dashed border-gray-300 rounded-md bg-gray-50
        flex items-center justify-center">

                                <!-- Preview -->
                                <div x-show="editData.imagePreview || editData.currentImage"
                                    class="w-full h-full flex items-center justify-center">
                                    <img :src="editData.imagePreview || getImageUrl()"
                                        class="max-w-full max-h-full object-contain rounded">
                                </div>

                                <!-- Placeholder -->
                                <div x-show="!editData.imagePreview && !editData.currentImage" class="text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0
                        0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172
                        a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172
                        a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-1 text-xs text-gray-500">Icon</p>
                                </div>
                            </div>

                            <!-- File Input -->
                            <input type="file" name="icon" x-ref="imageInput" accept="image/*"
                                @change="handleImageUpload($event)" class="hidden">

                            <div x-show="editData.iconError" class="mt-1 text-xs text-red-600"
                                x-text="editData.iconError"></div>

                            @error('icon')
                                <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                            @enderror

                            <div class="flex gap-2 mt-2">
                                <button type="button" @click="removeImage()"
                                    class="px-3 py-1 text-xs border border-red-300 text-red-600 rounded hover:bg-red-50">
                                    Hapus
                                </button>
                                <button type="button" @click="$refs.imageInput.click()"
                                    class="px-3 py-1 text-xs border border-green-300 text-green-600 rounded hover:bg-green-50">
                                    Upload
                                </button>
                            </div>
                        </div>


                        <!-- Name -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" name="name" :value="editData.name"
                                class="w-full border rounded px-3 py-2" required>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 border-t p-4">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 bg-gray-100">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white">Simpan</button>
                    </div>

                </form>

            </div>
        </div>



        <!-- Pagination Inline -->
        <div class="bg-gray-50 px-4 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <!-- Items per page -->
            <div class="flex items-center gap-2">
                {{-- <span class="text-sm text-gray-600">Tampilkan</span> --}}

                <select x-model.number="itemsPerPage" @change="currentPage = 1"
                    class="w-20 border-gray-300 rounded-lg py-1.5 text-sm focus:ring-2 focus:ring-green-600 focus:border-green-600">
                    <option value="3">3</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>

                {{-- <span class="text-sm text-gray-600">item</span> --}}
            </div>

            <!-- Pagination Buttons -->
            <div class="flex items-center gap-2">

                <!-- Previous -->
                <button @click="previousPage" :disabled="currentPage === 1"
                    class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm shadow-sm transition
                   hover:bg-green-600 hover:text-white
                   disabled:text-gray-300 disabled:hover:bg-transparent disabled:cursor-not-allowed">
                    Sebelumnya
                </button>

                <!-- Page Numbers -->
                {{-- <div class="flex items-center gap-1">

                    <!-- First Page -->
                    <template x-if="currentPage > 3">
                        <button @click="goToPage(1)"
                            class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm shadow-sm
                        hover:bg-green-600 hover:text-white"
                            :class="currentPage === 1 ? 'bg-green-600 text-white' : ''">
                            1
                        </button>
                    </template>

                    <!-- Left Ellipsis -->
                    <template x-if="currentPage > 4">
                        <span class="text-gray-500 px-1">...</span>
                    </template>

                    <!-- Middle Pages -->
                    <template x-for="page in getPageNumbers()" :key="page">
                        <button @click="goToPage(page)"
                            class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm shadow-sm
                        hover:bg-green-600 hover:text-white"
                            :class="currentPage === page ? 'bg-green-600 text-white' : ''" x-text="page"></button>
                    </template>

                    <!-- Right Ellipsis -->
                    <template x-if="currentPage < totalPages - 3">
                        <span class="text-gray-500 px-1">...</span>
                    </template>

                    <!-- Last Page -->
                    <template x-if="currentPage < totalPages - 2">
                        <button @click="goToPage(totalPages)"
                            class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm shadow-sm
                        hover:bg-green-600 hover:text-white"
                            x-text="totalPages"></button>
                    </template>
                </div> --}}

                <!-- Next -->
                <button @click="nextPage" :disabled="currentPage === totalPages"
                    class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm shadow-sm transition
                   hover:bg-green-600 hover:text-white
                   disabled:text-gray-300 disabled:hover:bg-transparent disabled:cursor-not-allowed">
                    Selanjutnya
                </button>

            </div>
        </div>

    </div>

    <script>
        function categoryManager() {
            return {
                baseUrl: '{{ asset('') }}',
                categories: @json($categories),

                searchQuery: '',

                currentPage: 1,
                itemsPerPage: 5,
                filteredCategories: [],

                showAddModal: false,
                showEditModal: false,
                validationErrors: @json($errors->toArray()),
                editData: {
                    id: '',
                    name: '',
                    currentImage: null,
                    imagePreview: null,
                    iconError: '',
                },

                init() {
                    this.filteredCategories = this.categories;

                    if (Object.keys(this.validationErrors).length > 0) {
                        @if (session('_method') === 'PUT')
                            this.showEditModal = true;
                        @else
                            this.showAddModal = true;
                        @endif
                    }
                },

                get paginatedCategories() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredCategories.slice(start, end);
                },

                filterCategories() {
                    let filtered = this.categories;

                    if (this.searchQuery) {
                        filtered = filtered.filter(category =>
                            category.name.toLowerCase().includes(this.searchQuery.toLowerCase())
                        );
                    }
                    this.filteredCategories = filtered;
                },

                get totalPages() {
                    return Math.ceil(this.filteredCategories.length / this.itemsPerPage);
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
                    }
                },

                previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                    }
                },

                getStatusClass(status) {
                    switch (status) {
                        case 'Aktif':
                            return 'bg-green-100 text-green-800';
                        case 'Nonaktif':
                            return 'bg-red-100 text-red-800';
                    }
                },


                openAddModal() {
                    this.showEditModal = false;
                    this.showAddModal = true;
                },


                /**
                 * Edit Modal Handlers
                 */
                openEditModal(category) {
                    this.editData.id = category.id;
                    this.editData.name = category.name;
                    this.editData.currentImage = category.image;

                    this.showAddModal = false;
                    this.showEditModal = true;
                },

                getImageUrl() {
                    if (!this.editData.currentImage) {
                        return this.baseUrl + 'produk/contohproduk.png'; // fallback
                    }

                    const imagePath = this.editData.currentImage;

                    // Jika sudah full URL (http/https), return as is
                    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
                        return imagePath;
                    }

                    // Jika path dimulai dengan 'products/' (dari storage)
                    if (imagePath.startsWith('products/')) {
                        return this.baseUrl + 'storage/' + imagePath;
                    }

                    // Jika path dimulai dengan 'produk/' (old public path)
                    if (imagePath.startsWith('produk/')) {
                        return this.baseUrl + imagePath;
                    }

                    // Default: anggap relative path dari storage
                    return this.baseUrl + 'storage/' + imagePath;
                },

                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.editData.imagePreview = e.target.result;
                            this.editData.iconError = '';
                        };
                        reader.readAsDataURL(file);
                    }
                },

                removeImage() {
                    this.editData.imagePreview = null;
                    this.editData.currentImage = null;
                    this.$refs.imageInput.value = '';
                    this.editData.iconError = '';
                },

                validateEditForm(event) {
                    this.editData.iconError = '';

                    if (!this.editData.currentImage && !this.$refs.imageInput.files.length && !this.editData.imagePreview) {
                        event.preventDefault();
                        this.editData.iconError = 'Icon kategori harus dipilih';
                        return false;
                    }

                    return true;
                }

            }
        }
    </script>

    <script>
        function editCategoryData() {
            return {






            };
        }
    </script>
@endsection
