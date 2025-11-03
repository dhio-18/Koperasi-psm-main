@extends('layouts.admin-layout')

@section('title')
    <title>Manajemen Akun Bank</title>
@endsection

@section('main')
    <div class="bg-gray-50 min-h-screen">
        <div x-data="paymentAccountManager()" class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Payment Account</h1>
                    <p class="text-gray-600 text-sm sm:text-base">Kelola akun pembayaran sistem</p>
                </div>
                <button @click="openAddModal"
                    class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 sm:px-6 py-2 shadow-lg rounded-lg flex items-center justify-center gap-2 transition-colors w-full sm:w-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12M6 12h12"></path>
                    </svg>
                    Tambah Account
                </button>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- ✅ tambahan: buat tabel bisa di-scroll horizontal di layar kecil -->
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-sm md:text-base">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Nama Bank</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Nomor Rekening</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Nama Pemegang</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">QR</th>
                                <th class="px-4 sm:px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="account in accounts" :key="account.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-gray-900" x-text="account.bank_name"></td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-gray-900" x-text="account.account_number"></td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-gray-900" x-text="account.account_holder_name"></td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <template x-if="account.qr_code_url">
                                            <img :src="account.qr_code_url" alt="QR" class="h-10 w-10 sm:h-12 sm:w-12 rounded border object-cover">
                                        </template>
                                        <template x-if="!account.qr_code_url">
                                            <span class="text-xs text-gray-400">-</span>
                                        </template>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 flex items-center justify-start">
                                        <button @click="openEditModal(account)"
                                            class="text-green-600 hover:text-green-400 transition-colors">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="openDeleteModal(account)"
                                            class="text-red-600 hover:text-red-700 transition-colors">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4h6v3" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            <tr x-show="accounts.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada payment account. Klik "Tambah Account" untuk menambah data pertama.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add/Edit Modal -->
            <!-- ✅ tambahan: modal dibuat penuh di mobile agar mudah digunakan -->
            <div x-show="showModal" x-transition class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-2 sm:p-0"
                style="display:none;">
                <div x-transition class="bg-white rounded-lg shadow-xl w-full max-w-md sm:max-w-lg md:max-w-xl mx-auto h-full sm:h-auto overflow-y-auto">
                    <div class="bg-green-600 text-white px-6 py-4 rounded-t-lg">
                        <h3 class="text-base sm:text-lg font-semibold" x-text="modalTitle"></h3>
                    </div>

                    <form :action="formAction" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                        @csrf
                        <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                        <template x-if="isEdit"><input type="hidden" name="id" :value="editData.id"></template>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                                <input type="text" name="bank_name" x-model="formData.bank_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm sm:text-base"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                                <input type="text" name="account_number" x-model="formData.account_number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm sm:text-base"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pemegang Rekening</label>
                                <input type="text" name="account_holder_name" x-model="formData.account_holder_name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm sm:text-base"
                                    required>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">QR Code (opsional)</label>
                                <input type="file" name="qr_code" accept="image/*" @change="previewQr($event)"
                                    class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                <div class="flex items-center gap-3 flex-wrap" x-show="qrPreview || formData.qr_code_url">
                                    <img :src="qrPreview || formData.qr_code_url"
                                        class="h-20 w-20 rounded border object-cover">
                                    <label class="inline-flex items-center gap-2 text-sm"
                                        x-show="isEdit && formData.qr_code_url">
                                        <input type="checkbox" name="remove_qr" value="1" x-model="removeQr"
                                            class="rounded">
                                        Hapus QR yang ada
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">Kosongkan jika tidak ingin upload.</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 pt-4 border-t">
                            <button type="button" @click="closeModal"
                                class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors w-full sm:w-auto">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors w-full sm:w-auto">
                                <span x-text="isEdit ? 'Update' : 'Simpan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div x-show="showDeleteModal" x-transition
                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-2 sm:p-0" style="display:none;">
                <div x-transition class="bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
                    <div class="bg-red-600 text-white px-6 py-4 rounded-t-lg">
                        <h3 class="text-lg font-semibold">Konfirmasi Hapus</h3>
                    </div>

                    <div class="p-6">
                        <p class="mb-4 text-gray-700">
                            Yakin ingin hapus akun <span class="font-medium" x-text="deleteData.bank_name"></span>?
                        </p>
                        <form :action="`/superadmin/payment-accounts/${deleteData.id}`" method="POST"
                            class="flex flex-col sm:flex-row justify-end gap-3">
                            @csrf @method('DELETE')
                            <button type="button" @click="closeDeleteModal"
                                class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg w-full sm:w-auto">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg w-full sm:w-auto">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function paymentAccountManager() {
            return {
                accounts: @json($accounts ?? []),
                showModal: false,
                showDeleteModal: false,
                isEdit: false,
                formData: {
                    bank_name: '',
                    account_number: '',
                    account_holder_name: '',
                    qr_code_url: null
                },
                editData: {},
                deleteData: {},
                qrPreview: null,
                removeQr: false,

                get modalTitle() {
                    return this.isEdit ? 'Edit Payment Account' : 'Tambah Payment Account';
                },
                get formAction() {
                    return this.isEdit ? `/superadmin/payment-accounts/${this.editData.id}` :
                        '/superadmin/payment-accounts';
                },

                openAddModal() {
                    this.isEdit = false;
                    this.resetForm();
                    this.showModal = true;
                },
                openEditModal(account) {
                    this.isEdit = true;
                    this.editData = account;
                    this.formData = {
                        ...account
                    };
                    this.qrPreview = null;
                    this.removeQr = false;
                    this.showModal = true;
                },
                openDeleteModal(account) {
                    this.deleteData = account;
                    this.showDeleteModal = true;
                },

                closeModal() {
                    this.showModal = false;
                    setTimeout(() => this.resetForm(), 200);
                },
                closeDeleteModal() {
                    this.showDeleteModal = false;
                    this.deleteData = {};
                },

                resetForm() {
                    this.formData = {
                        bank_name: '',
                        account_number: '',
                        account_holder_name: '',
                        qr_code_url: null
                    };
                    this.editData = {};
                    this.qrPreview = null;
                    this.removeQr = false;
                },

                previewQr(e) {
                    const f = e.target.files?.[0];
                    if (!f) {
                        this.qrPreview = null;
                        return;
                    }
                    const r = new FileReader();
                    r.onload = ev => this.qrPreview = ev.target.result;
                    r.readAsDataURL(f);
                },
            }
        }
    </script>
@endsection
