@props(['show', 'categories'])


{{-- Button untuk membuka modal --}}
<div x-data="data()">

    <!-- Modal Overlay -->
    <div x-show="{{ $show }}"
        x-transition:enter="transition ease-out duration-400"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
        style="display: none; backdrop-filter: blur(4px);"
        @click.self="{{ $show }} = false;">
        <!-- Modal Content -->
        <div x-show="{{ $show }}"
            x-transition:enter="transition ease-out duration-400 delay-75"
            x-transition:enter-start="opacity-0 scale-90 translate-y-8"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-90 translate-y-8"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" @click.stop>
            {{-- Modal Header --}}
            <div
                class="flex justify-between items-center p-6 border-b border-gray-200 sticky top-0 bg-white rounded-t-lg">
                <h2 class="text-lg font-semibold text-gray-900">Tambah Produk</h2>
                <button @click="{{ $show }} = false;"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <form x-ref="productForm" action="{{ route('admin.products.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    {{-- Nama Produk --}}
                    <div class="mb-4">
                        <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Produk
                        </label>
                        <input type="text" id="nama_produk" name="name" placeholder="Masukkan nama produk"
                            value="{{ old('name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                            required>

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-4">
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori
                        </label>
                        <div class="relative">
                            <select id="kategori" name="category_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 appearance-none bg-white"
                                required>
                                <option value="" selected disabled>Pilih Kategori</option>
                                <template x-for="category in {{ $categories }}" :key="category.id">
                                    <option :value="category.id" x-text="category.name"></option>
                                </template>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </div>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Harga --}}
                    <div class="mb-4">
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                            Harga
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" id="harga" name="price" placeholder="0" min="0"
                                step="any" value="{{ old('price') }}"
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                required>

                        </div>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Stok --}}
                    <div class="mb-4">
                        <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                            Stok
                        </label>
                        <input type="number" id="stok" name="stock" placeholder="0" min="0"
                            value="{{ old('stock') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                            required>
                        @error('stock')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea id="deskripsi" name="description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 resize-none"
                            placeholder="Masukkan deskripsi produk...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gambar Produk --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Gambar Produk
                        </label>

                        {{-- Image Preview Area --}}
                        <div
                            class="p-4 w-full h-48 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex items-center justify-center relative overflow-hidden">
                            {{-- Image Preview --}}
                            <div x-show="imagePreview" class="w-full h-full flex justify-center items-center relative">
                                <img :src="imagePreview" alt="Preview" class="w-full h-full object-contain rounded">
                            </div>

                            {{-- Placeholder --}}
                            <div x-show="!imagePreview" class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Product Image</p>
                            </div>
                        </div>

                        {{-- File Input (Hidden) --}}
                        <input type="file" x-ref="imageInput" name="images" accept="image/*"
                            @change="handleImageUpload($event)" class="hidden">

                        {{-- Upload Buttons --}}
                        <div class="flex gap-2 mt-3">
                            <button type="button" @click="imagePreview = null"
                                class="px-4 py-2 text-sm border border-red-300 text-red-600 hover:bg-red-50 rounded-md transition-colors duration-200">
                                Hapus Gambar
                            </button>
                            <button type="button" @click="$refs.imageInput.click()"
                                class="px-4 py-2 text-sm border border-green-300 text-green-600 hover:bg-green-50 rounded-md transition-colors duration-200">
                                Upload Gambar
                            </button>
                        </div>

                        @error('images')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end space-x-3 border-t border-gray-200 p-4">
                    <button type="button" @click="{{ $show }} = false; resetForm()"
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

    <script>
        function data() {
            return {
                imagePreview: null,

                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                removeImage() {
                    this.imagePreview = null;
                    this.$refs.imageInput.value = '';
                },

                resetForm() {
                    this.imagePreview = null;
                    this.$refs.productForm.reset();
                }
            };
        }
    </script>
</div>
