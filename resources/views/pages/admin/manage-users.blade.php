@extends('layouts.admin-layout')

@section('title')
    <title>Manajemen Pengguna</title>
@endsection

@section('main')
    <div x-data="userPage()" class="min-h-screen px-4 py-6"><!-- px-6→4, py-10→6 -->

        <!-- Header (dipadatkan) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-2"><!-- mb-6→4, gap-3→2 -->
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Manajemen Admin</h1><!-- 2xl→xl di mobile -->
            <div class="flex items-center gap-2">
                <input type="text" placeholder="Cari nama atau email..." x-model="search"
                    class="border rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-green-500 w-56 md:w-64" /><!-- py-2→1.5, text-sm -->
                <button @click="openAddModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-3.5 py-1.5 rounded-lg text-sm font-medium"><!-- px-4→3.5, py-2→1.5 -->
                    + Tambah Admin
                </button>
            </div>
        </div>

        <!-- Table wrapper -->
        <div class="w-full bg-white rounded-lg shadow-sm border border-gray-200 max-h-[70vh] overflow-auto">
            <!-- max-h-screen→70vh -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-green-50 sticky top-0 z-10">
                        <tr class="text-gray-900 text-xs md:text-sm">
                            <th class="px-4 md:px-6 py-3 text-left font-medium uppercase tracking-wider">No</th>
                            <!-- py-4→3 -->
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
                                    x-text="index + 1"></td><!-- py-4→3 -->
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-gray-900" x-text="user.name"></td>
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-gray-900" x-text="user.email"></td>
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap capitalize text-gray-700" x-text="user.role">
                                </td>
                                <td class="px-4 md:px-6 py-3 whitespace-nowrap text-right">
                                    <div class="flex justify-center gap-2"><!-- space-x-3→gap-2 -->
                                        <form action="" id="form-confirm" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
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

        <!-- Modal Konfirmasi (dipadatkan) -->
        <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                <div x-show="showConfirmModal" x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500/70"
                    @click="showConfirmModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showConfirmModal" x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-sm p-5 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                    <!-- max-w-md→sm, p-6→5 -->
                    <div
                        class="flex items-center justify-center w-10 h-10 mx-auto mb-3 bg-red-100 rounded-full text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 11v6" />
                            <path d="M14 11v6" />
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                            <path d="M3 6h18" />
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                        </svg>
                    </div>

                    <h3 class="text-base font-semibold text-gray-900 text-center mb-1.5">Hapus Akun Admin</h3>
                    <!-- text-lg→base -->
                    <p class="text-sm text-gray-600 text-center mb-4">Apakah Anda yakin untuk menghapus admin ini? Klik
                        batal jika
                        tidak ingin menghapus.</p>

                    <div class="flex gap-2">
                        <button @click="showConfirmModal = false"
                            class="flex-1 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button @click="processConfirmation()"
                            class="flex-1 px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Admin -->
        <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div @click.away="closeModal()" class="bg-white w-full max-w-sm rounded-xl shadow-lg p-5">
                <!-- max-w-md→sm, p-6→5 -->
                <h2 class="text-base font-semibold mb-3">Tambah Admin</h2>
                <!-- lg→base -->

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
                        <input type="password" name="password" x-model="form.password" required
                            placeholder="Masukkan password"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t"><!-- pt-4→3 -->
                        <button type="button" @click="closeModal()"
                            class="px-3 py-1.5 border rounded-lg text-gray-600 hover:bg-gray-100 text-sm">Batal</button>
                        <button type="submit"
                            class="px-3.5 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">Simpan</button>
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
                showConfirmModal: false,
                selectUserId: null,
                search: '',
                users: @json($users),
                form: {
                    id: null,
                    name: '',
                    email: '',
                    role: ''
                },

                filteredUsers() {
                    if (!this.search) return this.users;
                    const keyword = this.search.toLowerCase();
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(keyword) ||
                        u.email.toLowerCase().includes(keyword)
                    );
                },

                confirmDelete(userId) {
                    this.selectUserId = userId;
                    this.showConfirmModal = true;
                },

                processConfirmation() {
                    if (this.selectUserId) {
                        const url = this.baseUrl + 'superadmin/manage-users/' + this.selectUserId;
                        document.getElementById('form-confirm').setAttribute('action', url);
                        document.getElementById('form-confirm').submit();
                    }
                    this.showConfirmModal = false;
                    this.selectUserId = null;
                },

                openAddModal() {
                    this.isEdit = false;
                    this.form = {
                        id: null,
                        name: '',
                        email: '',
                        role: ''
                    };
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                },
            }
        }
    </script>
@endsection
