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
                                    <img :src="baseUrl + 'storage/' +  category.image" alt="image"
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
        <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click.self="showEditModal = false" style="display: none;">
            <!-- Modal Content -->
            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4" @click.stop>
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Edit Kategori</h2>
                    <button @click="showEditModal = false"
                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form :action="baseUrl + 'admin/category/' + editData.id" method="POST" enctype="multipart/form-data"
                    @submit="validateEditForm($event)">
                    @csrf
                    @method('PUT')

                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <!-- Icon -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Icon
                            </label>

                            <!-- Image Preview Area -->
                            <div
                                class="p-4 h-28 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex items-center justify-center">
                                <!-- Image Preview -->
                                <div x-show="editData.imagePreview || editData.currentImage"
                                    class="w-full h-full flex items-center justify-center">
                                    <img :src="editData.imagePreview || getImageUrl()" alt="Preview"
                                        class="max-w-full max-h-full object-contain rounded">
                                </div>

                                <!-- Placeholder -->
                                <div x-show="!editData.imagePreview && !editData.currentImage" class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Icon</p>
                                </div>
                            </div>

                            <!-- File Input (Hidden) -->
                            <input type="file" x-ref="imageInput" name="image" accept="image/*"
                                @change="handleImageUpload($event)" class="hidden">

                            <!-- Error Message for Icon -->
                            <div x-show="editData.iconError" class="mt-2 text-sm text-red-600"
                                x-text="editData.iconError">
                            </div>

                            <!-- Upload Buttons -->
                            <div class="flex gap-2 mt-3">
                                <button type="button" @click="removeImage()"
                                    class="px-4 py-2 text-sm border border-red-300 text-red-600 hover:bg-red-50 rounded-md transition-colors duration-200">
                                    Hapus Icon
                                </button>
                                <button type="button" @click="$refs.imageInput.click()"
                                    class="px-4 py-2 text-sm border border-green-300 text-green-600 hover:bg-green-50 rounded-md transition-colors duration-200">
                                    Upload Icon
                                </button>
                            </div>

                            @error('icon')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Kategori -->
                        <div class="mb-4">
                            <label for="edit_nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Kategori
                            </label>
                            <input type="text" id="edit_nama_kategori" name="name"
                                placeholder="Masukkan nama kategori" :value="editData.name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                required>

                            @error('name')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 border-t border-gray-200 p-4">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors duration-200">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>


        <!-- Pagination -->
        <div class="mt-auto">
            <!-- Pagination -->
            <x-admin.pagination data="filteredCategories" />
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
                    return this.editData.currentImage ? this.baseUrl + this.editData.currentImage : null;
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
