@extends('layouts.layout')

@section('title')
    <title>Profile</title>
@endsection

@section('main')

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-[auto_1fr] items-start gap-8 px-6 py-14 md:grid" x-data="profileManager()">
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
                                    <img x-show="!previewUrl" src=" {{ asset(Auth::user()->profile_photo_path) }}" alt="Profile"
                                        class="w-full h-full object-cover">
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
                            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('name')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" value="Email" />
                            <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('email')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- No Handphone --}}
                        <div>
                            <x-input-label for="phone" value="No Handphone" />
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500"
                                required>
                            @error('phone')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Delete Account Section --}}
                        <div class="pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Zona Berbahaya</h3>
                            <p class="text-xs text-gray-500 mb-4">Menghapus akun akan menghilangkan semua data Anda secara permanen.</p>
                            <button type="button" @click="openDeleteModal()"
                                class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Hapus Akun</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Delete Account Confirmation Modal --}}
        <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak
            style="backdrop-filter: blur(4px);">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60"
                    @click="closeDeleteModal()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-90"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-90"
                    class="inline-block w-full max-w-md my-8 overflow-hidden text-left align-middle transform bg-white shadow-2xl rounded-2xl p-6 space-y-4">

                    {{-- Icon Danger --}}
                    <div class="flex justify-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4v2m0 6a9 9 0 1018 0 9 9 0 01-18 0z"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900">Hapus Akun?</h3>
                    </div>

                    {{-- Message --}}
                    <div class="text-center">
                        <p class="text-sm text-gray-500">
                            Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan dan semua data Anda akan dihapus secara permanen.
                        </p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="closeDeleteModal()"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            Tolak
                        </button>
                        <button type="button" @click="confirmDeleteAccount()"
                            class="flex-1 px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                            Terima
                        </button>
                    </div>
                </div>
            </div>
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
                showDeleteModal: false,
                isDeleting: false,

                openDeleteModal() {
                    this.showDeleteModal = true;
                },

                closeDeleteModal() {
                    this.showDeleteModal = false;
                },

                confirmDeleteAccount() {
                    this.isDeleting = true;

                    fetch('{{ route("user.profile.destroy") }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            // Redirect ke home page
                            window.location.href = '{{ route("home") }}';
                        } else {
                            this.isDeleting = false;
                            alert('Gagal menghapus akun. Silahkan coba lagi.');
                        }
                    })
                    .catch(error => {
                        this.isDeleting = false;
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus akun.');
                    });
                }
            }
        }
    </script>

@endsection
