@props(['show', 'errors' => null])

<!-- Modal Overlay -->
<div x-data="data()">
    <div x-show="{{ $show }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        @click.self="{{ $show }} = false" style="display: none;">
        <!-- Modal Content -->
        <div x-show="{{ $show }}" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4" @click.stop>
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Tambah Kategori</h2>
                <button @click="{{ $show }} = false"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data"
                @submit="validateForm($event)">
                @csrf
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
                            <div x-show="imagePreview" class="w-full h-full flex items-center justify-center">
                                <img :src="imagePreview" alt="Preview"
                                    class="max-w-full max-h-full object-contain rounded">
                            </div>

                            <!-- Placeholder -->
                            <div x-show="!imagePreview" class="text-center">
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
                        <input type="file" x-ref="imageInput" name="icon" accept="image/*"
                            @change="handleImageUpload($event)" class="hidden">

                        <!-- Error Message for Icon -->
                        <div x-show="iconError" class="mt-2 text-sm text-red-600" x-text="iconError"></div>

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
                        <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kategori
                        </label>
                        <input type="text" id="nama_kategori" name="name" placeholder="Masukkan nama kategori"
                            value="{{ old('name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                            required>

                        @error('name')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 border-t border-gray-200 p-4">
                    <button type="button" @click="{{ $show }} = false; resetForm()"
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

    <script>
        function data() {
            return {
                imagePreview: null,
                iconError: '',

                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                            this.iconError = ''; // Clear error when image is selected
                        };
                        reader.readAsDataURL(file);
                    }
                },

                removeImage() {
                    this.imagePreview = null;
                    this.$refs.imageInput.value = '';
                    this.iconError = '';
                },

                resetForm() {
                    this.imagePreview = null;
                    this.iconError = '';
                    // Reset form properly
                    const form = this.$el.querySelector('form');
                    if (form) {
                        form.reset();
                    }
                    this.$refs.imageInput.value = '';
                },

                validateForm(event) {
                    // Reset error
                    this.iconError = '';

                    // Check if icon is selected
                    if (!this.$refs.imageInput.files.length && !this.imagePreview) {
                        event.preventDefault();
                        this.iconError = 'Icon kategori harus dipilih';

                        console.log('Validation failed: Icon is required');
                        return false;
                    }

                    return true;
                }
            };
        }
    </script>
</div>
