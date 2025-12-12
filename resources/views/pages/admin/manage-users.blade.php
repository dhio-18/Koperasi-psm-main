@extends('layouts.admin-layout')

@section('title')
    <title>Manajemen Pengguna</title>
@endsection

@section('main')
    <div x-data="userPage()" class="min-h-screen px-4 py-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-2">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Manajemen Admin</h1>
            <div class="flex items-center gap-2">
                <input type="text" placeholder="Cari nama atau email..." x-model="search"
                    class="border rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-green-500 w-56 md:w-64" />
                <button @click="openAddModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-3.5 py-1.5 rounded-lg text-sm font-medium">
                    Tambah Admin
                </button>
            </div>
        </div>

        <!-- Table wrapper -->
        <div class="w-full bg-white rounded-lg shadow-sm border border-gray-200 max-h-[70vh] overflow-auto">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-green-50 sticky top-0 z-10">
                        <tr class="text-gray-900 text-xs md:text-sm">
                            <th class="px-4 md:px-6 py-3 text-left font-medium uppercase tracking-wider">No</th>
                            <th class="px-4 md:px-6 py-3 text-left font-medium uppercase tracking-wider">Nama</th>
                            <th class="px-4 md:px-6 py-3 text-left font-medium uppercase tracking-wider">Email</th>
                            <th class="px-4 md:px-6 py-3 text-left font-medium uppercase tracking-wider">Role</th>
                            <th class="px-4 md:px-6 py-3 text-center font-medium uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="(user, index) in filteredUsers()" :key="user.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-gray-900 font-medium"
                                    x-text="index + 1"></td>
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-gray-900" x-text="user.name"></td>
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-gray-900" x-text="user.email"></td>
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap capitalize text-gray-700" x-text="user.role">
                                </td>
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-right">
                                    <div class="flex justify-center gap-2">
                                        <form action="" id="form-confirm" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <!-- Edit -->
                                        <button @click="openEditModal(user)"
                                            class="text-green-600 hover:text-green-400 transition-colors">
                                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <!-- Hapus -->
                                        <button @click="confirmDelete(user.id)"
                                            class="text-red-600 hover:text-red-700 transition-colors" title="Hapus">
                                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4h6v3" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredUsers().length === 0">
                            <td colspan="5" class="text-center py-6 text-gray-500 text-sm">Tidak ada pengguna ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Konfirmasi Delete -->
        <div x-show="showConfirmModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-sm rounded-lg p-5 shadow-xl">
                <h3 class="text-base font-semibold text-center mb-2">Hapus Akun Admin</h3>
                <p class="text-sm text-gray-600 text-center mb-4">Apakah Anda yakin ingin menghapus admin ini?</p>
                <div class="flex gap-2">
                    <button @click="showConfirmModal = false"
                        class="flex-1 px-3 py-2 border rounded-lg text-gray-700 hover:bg-gray-100 text-sm">Batal</button>

                    <button @click="processConfirmation()"
                        class="flex-1 px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Ya,
                        Hapus</button>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Admin -->
        <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div x-data="{ showPassword: false }" @click.away="closeModal()"
                class="bg-white w-full max-w-sm rounded-xl shadow-lg p-5">
                <h2 class="text-base font-semibold mb-3">Tambah Admin</h2>

                <form action="{{ asset('superadmin/manage-users/') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" x-model="form.name" required placeholder="Masukkan nama"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="form.email" required placeholder="Masukkan email"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" x-model="form.password"
                                required placeholder="Masukkan password"
                                class="w-full px-3 py-2 pr-10 border rounded-lg focus:ring-2 focus:ring-green-500 text-sm">

                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <circle cx="12" cy="12" r="3" stroke-width="1.8" />
                                </svg>

                                <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.507-4.568M6.18 6.18L18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t">
                        <button type="button" @click="closeModal()"
                            class="px-3 py-1.5 border rounded-lg text-gray-600 hover:bg-gray-100 text-sm">Batal</button>
                        <button type="submit"
                            class="px-3.5 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Admin -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div x-data="{ showPasswordEdit: false }" @click.away="closeEditModal()"
                class="bg-white w-full max-w-sm rounded-xl shadow-lg p-5">
                <h2 class="text-base font-semibold mb-3">Edit Admin</h2>

                <form :action="`${baseUrl}superadmin/manage-users/${form.id}`" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" x-model="form.name" required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="form.email" required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (opsional)</label>
                        <div class="relative">
                            <input :type="showPasswordEdit ? 'text' : 'password'" name="password" x-model="form.password"
                                placeholder="Kosongkan jika tidak ingin diubah"
                                class="w-full px-3 py-2 pr-10 border rounded-lg focus:ring-2 focus:ring-green-500 text-sm">

                            <button type="button" @click="showPasswordEdit = !showPasswordEdit"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700">
                                <svg x-show="!showPasswordEdit" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <circle cx="12" cy="12" r="3" stroke-width="1.8" />
                                </svg>

                                <svg x-show="showPasswordEdit" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.507-4.568M6.18 6.18L18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t">
                        <button type="button" @click="closeEditModal()"
                            class="px-3 py-1.5 border rounded-lg text-gray-600 hover:bg-gray-100 text-sm">Batal</button>
                        <button type="submit"
                            class="px-3.5 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function userPage() {
            return {
                baseUrl: '{{ asset('') }}',
                showModal: false,
                showEditModal: false,
                showConfirmModal: false,
                selectUserId: null,
                search: '',
                users: @json($users),
                form: {
                    id: null,
                    name: '',
                    email: '',
                    password: '',
                },

                filteredUsers() {
                    if (!this.search) return this.users
                    const keyword = this.search.toLowerCase()
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(keyword) ||
                        u.email.toLowerCase().includes(keyword)
                    )
                },

                confirmDelete(id) {
                    this.selectUserId = id
                    this.showConfirmModal = true
                },

                processConfirmation() {
                    if (this.selectUserId) {
                        const url = this.baseUrl + 'superadmin/manage-users/' + this.selectUserId
                        document.getElementById('form-confirm').setAttribute('action', url)
                        document.getElementById('form-confirm').submit()
                    }
                    this.showConfirmModal = false
                },

                openAddModal() {
                    this.form = {
                        id: null,
                        name: '',
                        email: '',
                        password: ''
                    }
                    this.showModal = true
                },

                openEditModal(user) {
                    this.form = {
                        id: user.id,
                        name: user.name,
                        email: user.email,
                        password: ''
                    }
                    this.showEditModal = true
                },

                closeModal() {
                    this.showModal = false
                },

                closeEditModal() {
                    this.showEditModal = false
                },
            }
        }
    </script>
@endsection
