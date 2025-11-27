@extends('layouts.layout')

@section('title')
    <title>Profile</title>
@endsection

@section('main')
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-[auto_1fr] items-start gap-8 px-6 py-14 md:grid">
        <!-- Left Sidebar -->
        <x-profile.sidebar />

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200/60">
            <div class="container mx-auto px-4 py-8" x-data="addressManager()">
                {{-- Header --}}
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Daftar Alamat</h1>
                    <button @click="openModal()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Alamat
                    </button>
                </div>

                {{-- Table --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-green-50">
                                <tr class="border-b border-gray-200">
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                                        NO
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                                        NAMA
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                                        NO
                                        HANDPHONE</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-green-800 uppercase tracking-wider">
                                        ALAMAT</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-green-800 uppercase tracking-wider">

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($data ?? [] as $index => $address)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $address->recipient_name }}
                                            <p class="text-xs text-gray-500">{{ $address->label }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $address->phone }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="max-w-xs">
                                                {{ $address->full_address }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button @click="openEditModal({{ $address }})"
                                                    class="text-blue-600 hover:text-blue-900">Edit</button>
                                                <form action="{{ route('user.profile.deleteAddress', $address->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')"
                                                        class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <p class="text-lg font-medium">Belum ada alamat</p>
                                            <p class="text-sm">Tambahkan alamat pertama Anda</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Modal Overlay --}}
                <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
                    style="backdrop-filter: blur(4px);" @click="closeModal()">

                    {{-- Modal Content --}}
                    <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-400 delay-75"
                        x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-90 translate-y-8"
                        class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>

                        {{-- Modal Header --}}
                        <div class="flex justify-between items-center p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900"
                                x-text="isEditMode ? 'Edit Alamat' : 'Daftar Alamat Pengirim'"></h2>
                            <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <form
                            :action="isEditMode ? '{{ url('user/profile/address') }}/' + editingAddress.id :
                                '{{ route('user.profile.addAddress') }}'"
                            method="POST" class="p-6">
                            @csrf

                            {{-- Label Alamat --}}
                            <div>
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Label Alamat <span class="text-red-500">*</span>
                                    </label>
                                    <select id="label" name="label" x-model="label"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        required>

                                        <option value="" disabled selected>Pilih lokasi pengiriman</option>

                                        <optgroup label="DIV 1">
                                            <option value="Blok B DIV 1">Blok B DIV 1</option>
                                            <option value="Blok D DIV 1">Blok D DIV 1</option>
                                            <option value="Blok E DIV 1">Blok E DIV 1</option>
                                            <option value="Bedeng Kontraktor DIV 1">Bedeng Kontraktor DIV 1</option>
                                            <option value="Bedeng Plantation DIV 1">Bedeng Plantation DIV 1</option>
                                            <option value="Kantor DIV 1">Kantor DIV 1</option>
                                        </optgroup>

                                        <optgroup label="DIV 2">
                                            <option value="Blok B DIV 2">Blok B DIV 2</option>
                                            <option value="Blok C DIV 2">Blok C DIV 2</option>
                                            <option value="Blok D DIV 2">Blok D DIV 2</option>
                                            <option value="Blok E Factory DIV 2">Blok E Factory DIV 2</option>
                                            <option value="Blok E Plantation DIV 2">Blok E Plantation DIV 2</option>
                                            <option value="Bedeng Factory DIV 2">Bedeng Factory DIV 2</option>
                                            <option value="Bedeng Kontraktor DIV 2">Bedeng Kontraktor DIV 2</option>
                                            <option value="Bedeng Plantation DIV 2">Bedeng Plantation DIV 2</option>
                                            <option value="Kantor Central DIV 2">Kantor Central DIV 2</option>
                                            <option value="Kantor Mitra Mandiri (MMD) DIV 2">Kantor Mitra Mandiri (MMD) DIV
                                                2
                                            </option>
                                            <option value="Kantor Plantation DIV 2">Kantor Plantation DIV 2</option>
                                        </optgroup>
                                    </select>
                                    @error('label')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-6" x-show="!label.toLowerCase().includes('kantor')">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nomor Rumah
                                        <template x-if="!label.toLowerCase().includes('kantor') && label">
                                            <span class="text-red-500">*</span>
                                        </template>
                                    </label>

                                    <input type="text" name="house_number" x-model="houseNumber"
                                        :required="!label.toLowerCase().includes('kantor') && label"
                                        placeholder="Masukkan nomor rumah"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                </div>
                            </div>

                            {{-- Nomor Telepon & Nama Penerima --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nomor Telepon <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="phone" x-model="formData.phone"
                                        placeholder="Masukkan nomor telepon penerima paket"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        value="{{ Auth::user()->phone }}" required>
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Penerima <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="recipient_name" x-model="formData.recipient_name"
                                        placeholder="Masukkan nama penerima paket"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        required>
                                    @error('recipient_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Provinsi & Kabupaten --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                    <div class="px-3 py-2 bg-gray-200 rounded-lg text-gray-600">
                                        Lampung
                                    </div>
                                    <input type="hidden" value="Lampung">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kabupaten</label>
                                    <div class="px-3 py-2 bg-gray-200 rounded-lg text-gray-600">
                                        Way Kanan
                                    </div>
                                    <input type="hidden" value="Way Kanan">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                                    <div class="px-3 py-2 bg-gray-200 rounded-lg text-gray-600">
                                        Pakuan Ratu
                                    </div>
                                    <input type="hidden" value="Pakuan Ratu">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                    <div class="px-3 py-2 bg-gray-200 rounded-lg text-gray-600">
                                        34762
                                    </div>
                                    <input type="hidden" value="34762">
                                </div>
                            </div>

                            {{-- Kode Pos & Detail Alamat --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Detail Alamat <span class="text-red-500">*</span>
                                </label>
                                <textarea name="address" x-model="formData.address" placeholder="Berikan detail nama jalan, patokan, alamat, dsb."
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    required></textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex justify-end pt-6 border-t border-gray-200">
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200"
                                    x-text="isEditMode ? 'Update Alamat' : 'Simpan Perubahan'">
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function addressManager() {
            return {
                isModalOpen: false,
                isEditMode: false,
                editingAddress: null,
                label: '',
                houseNumber: '',
                labelLength: 0,
                formData: {
                    phone: '',
                    recipient_name: '',
                    district: '',
                    village: '',
                    postal_code: '',
                    address: ''
                },

                openModal() {
                    this.isEditMode = false;
                    this.resetForm();

                    // Set default nomor telepon user ketika TAMBAH baru
                    this.formData.phone = "{{ Auth::user()->phone }}";

                    this.isModalOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                openEditModal(address) {
                    this.isEditMode = true;
                    this.editingAddress = address;
                    this.populateForm(address);
                    this.isModalOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeModal() {
                    this.isModalOpen = false;
                    this.isEditMode = false;
                    this.editingAddress = null;
                    document.body.style.overflow = 'auto';
                    this.resetForm();
                },

                resetForm() {
                    this.label = '';
                    this.houseNumber = '';
                    this.labelLength = 0;
                    this.formData = {
                        phone: '',
                        recipient_name: '',
                        district: '',
                        village: '',
                        postal_code: '',
                        address: ''
                    };
                },

                populateForm(address) {
                    this.label = address.label || '';
                    this.houseNumber = address.house_number || '';
                    this.labelLength = this.label.length;
                    this.formData = {
                        phone: address.phone || '',
                        recipient_name: address.recipient_name || '',
                        district: address.district || '',
                        village: address.village || '',
                        postal_code: address.postal_code || '',
                        address: address.address || ''
                    };
                },

                updatelabelLength() {
                    this.labelLength = this.label.length;
                }
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Trigger close modal if open
                window.dispatchEvent(new CustomEvent('close-modal'));
            }
        });
    </script>
@endsection
