@extends('layouts.layout')

@section('title')
    <title>Profile</title>
@endsection

@section('main')
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-[auto_1fr] items-start gap-8 px-6 py-14 md:grid"
        x-data="profileManager()">
        <!-- Left Sidebar -->
        <x-profile.sidebar />

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200/60">
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">

                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    {{-- Bagian Kiri - Upload Foto Profile --}}
                    <div class="flex flex-col items-center justify-center" x-data="photoPreview()">
                        <div class="relative">
                            {{-- Preview Foto --}}
                            <div
                                class="w-40 h-40 rounded-full border-4 border-gray-200 bg-gray-100 overflow-hidden relative">
                                @if (Auth::user()->profile_photo_path)
                                    <img x-show="!previewUrl" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                                    alt="Profile" class="w-full h-full object-cover">
                                @else
                                    <img x-show="!previewUrl" src="{{ asset('profile/blank.webp') }}" alt="Default Profile"
                                        class="w-full h-full object-cover">
                                @endif

                                {{-- Preview gambar baru --}}
                                <img x-show="previewUrl" x-bind:src="previewUrl" alt="Preview"
                                    class="w-full h-full object-cover">
                            </div>

                            {{-- Loading overlay saat upload --}}
                            <div x-show="isLoading"
                                class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                            </div>
                        </div>

                        {{-- Input File --}}
                        <div class="w-full flex justify-center mt-4">
                            <label
                                class="cursor-pointer inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2.5 rounded-full shadow-sm transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Pilih Foto</span>
                                <input type="file" name="profile_photo" accept="image/jpeg,image/jpg,image/png"
                                    class="hidden" x-ref="fileInput" @change="handleFileChange($event)">
                            </label>
                        </div>

                        {{-- Tombol untuk reset preview --}}
                        <button type="button" x-show="previewUrl" @click="resetPreview()"
                            class="mt-2 text-sm text-red-500 hover:text-red-700 underline">
                            Batalkan pilihan
                        </button>

                        {{-- Info Upload --}}
                        <div class="mt-3 text-center text-sm text-gray-500">
                            <p>Ukuran gambar: maks. 1 MB.</p>
                            <p>Ekstensi file gambar: JPEG, JPG, PNG</p>
                        </div>

                        {{-- Error Message --}}
                        @error('profile_photo')
                            <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                        @enderror

                        {{-- Error message dari Alpine.js --}}
                        <div x-show="errorMessage" x-text="errorMessage" class="mt-1 text-red-500 text-sm"></div>
                    </div>

                    {{-- Bagian Kanan - Data Diri --}}
                    <div class="space-y-6">
                        {{-- Header dengan Button Selesai --}}
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-900">Data Diri</h2>
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                <span>Selesai</span>
                            </button>
                        </div>

                        {{-- Nama --}}
                        <div>
                            <x-input-label for="name" value="Nama" />
                            <input type="text" id="name" name="name"
                                value="{{ old('name', Auth::user()->name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('name')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" value="Email" />
                            <input type="email" id="email" name="email"
                                value="{{ old('email', Auth::user()->email) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('email')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- No Handphone --}}
                        <div>
                            <x-input-label for="phone" value="No Handphone" />
                            <input type="tel" id="phone" name="phone"
                                value="{{ old('phone', Auth::user()->phone) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('phone')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>
                </div>
            </form>
        </div>

        <script>
            function photoPreview() {
                return {
                    previewUrl: null,
                    isLoading: false,
                    errorMessage: '',

                    handleFileChange(event) {
                        const file = event.target.files[0];
                        this.errorMessage = '';

                        if (!file) {
                            this.resetPreview();
                            return;
                        }

                        // Validasi ukuran file (1MB = 1048576 bytes)
                        if (file.size > 1048576) {
                            this.errorMessage = 'Ukuran file terlalu besar. Maksimal 1 MB.';
                            this.resetPreview();
                            return;
                        }

                        // Validasi tipe file
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                        if (!allowedTypes.includes(file.type)) {
                            this.errorMessage = 'Tipe file tidak didukung. Gunakan JPEG, JPG, atau PNG.';
                            this.resetPreview();
                            return;
                        }

                        // Tampilkan loading
                        this.isLoading = true;

                        // Baca file dan tampilkan preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previewUrl = e.target.result;
                            this.isLoading = false;
                        };
                        reader.onerror = () => {
                            this.errorMessage = 'Gagal membaca file.';
                            this.isLoading = false;
                            this.resetPreview();
                        };
                        reader.readAsDataURL(file);
                    },

                    resetPreview() {
                        this.previewUrl = null;
                        this.errorMessage = '';
                        this.$refs.fileInput.value = '';
                    }
                }
            }

            function profileManager() {
                return {
                    // Empty - no delete account functionality
                }
            }
        </script>
    @endsection
