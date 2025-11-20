@extends('layouts.admin-layout')

@section('title')
    <title>Kelola Carousel</title>
@endsection

@section('main')
    <div x-data="carouselManagement()" class="container mx-auto px-4 pt-8 flex flex-col min-h-screen">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Gambar Carousel</h1>
            <p class="text-gray-600 mt-2">Upload dan atur gambar carousel untuk halaman utama</p>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Tambah Gambar Baru</h2>
            <form action="{{ route('admin.carousel.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Gambar</label>
                    <input type="file" name="image" accept="image/*" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, WEBP. Maksimal 2MB. Resolusi rekomendasi:
                        1920x800px</p>
                    @error('image')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Upload Gambar
                </button>
            </form>
        </div>

        <!-- Carousel List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Daftar Gambar Carousel</h2>

            @if ($carousels->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500 mt-4">Belum ada gambar carousel. Upload gambar pertama Anda!</p>
                </div>
            @else
                <div id="carousel-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($carousels as $carousel)
                        <div data-id="{{ $carousel->id }}"
                            class="carousel-item bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <!-- Image Preview -->
                            <div class="relative aspect-video mb-3 rounded-lg overflow-hidden bg-gray-200">
                                <img src="{{ asset('storage/' . $carousel->image_path) }}" alt="Carousel Image"
                                    class="w-full h-full object-cover">

                                <!-- Status Badge -->
                                <div class="absolute top-2 right-2">
                                    @if ($carousel->is_active)
                                        <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-500 text-white text-xs rounded-full">Non-aktif</span>
                                    @endif
                                </div>

                                <!-- Drag Handle -->
                                <div class="drag-handle absolute top-2 left-2 cursor-move bg-white/80 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Order & Actions -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Urutan: <span
                                        class="font-semibold">{{ $carousel->order }}</span></span>

                                <div class="flex gap-2">
                                    <!-- Toggle Active -->
                                    <form action="{{ route('admin.carousel.toggle', $carousel->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="{{ $carousel->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if ($carousel->is_active)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.carousel.destroy', $carousel->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus gambar ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function carouselManagement() {
            return {
                init() {
                    const el = document.getElementById('carousel-list');
                    if (el) {
                        Sortable.create(el, {
                            handle: '.drag-handle',
                            animation: 150,
                            onEnd: (evt) => {
                                this.updateOrder();
                            }
                        });
                    }
                },

                updateOrder() {
                    const items = document.querySelectorAll('.carousel-item');
                    const orders = Array.from(items).map(item => item.dataset.id);

                    fetch('{{ route('admin.carousel.update-order') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                orders: orders
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                        })
                        .catch(error => {
                        });
                }
            }
        }
    </script>
@endsection
