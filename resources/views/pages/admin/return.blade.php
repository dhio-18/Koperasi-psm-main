@extends('layouts.admin-layout')

@section('title')
    <title>Manajemen Pengembalian</title>
@endsection

@section('main')
    <div x-data="orderManagement()"
        x-effect="document.documentElement.classList.toggle('overflow-hidden', showDetailModal || showDetailImageModal)"
        class="container mx-auto px-4 pt-8 flex flex-col min-h-screen">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Pengembalian Barang</h1>

        </div>

        <!-- Search & Filter -->
        <div class="flex flex-col md:flex-row md:flex-wrap items-stretch gap-4 mb-6">
            <!-- Search -->
            <div class="flex-1 min-w-[220px]">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                        <!-- icon -->
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input x-model="searchQuery" @input="filterOrders" placeholder="Cari pesanan..."
                        class="h-12 w-full pl-10 pr-12 rounded-lg border border-gray-300 shadow-lg
                                                      focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none" />
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-60">
                <div class="relative">
                    <select x-model="statusFilter" @change="filterOrders"
                        class="h-12 w-full appearance-none bg-white border border-gray-300 rounded-lg
                                                       pl-4 pr-10 shadow-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Semua</option>
                        <option value="sending">Dikirim</option>
                        <option value="completed">Selesai</option>
                        <option value="returned">Dikembalikan</option>
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Sort Filter -->
            <div class="w-full md:w-60">
                <div class="relative">
                    <select x-model="sortBy" @change="filterOrders"
                        class="h-12 w-full appearance-none bg-white border border-gray-300 rounded-lg
                                                       pl-4 pr-10 shadow-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="highest">Total Tertinggi</option>
                        <option value="lowest">Total Terendah</option>
                    </select>
                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="w-full bg-white rounded-lg shadow-sm border border-gray-200 max-h-screen overflow-auto">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-green-50">
                        <tr class="text-gray-900">
                            <th class="px-6 py-4 text-left text-xs font-medium  uppercase tracking-wider">ID
                                Pesanan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium  uppercase tracking-wider">Nama
                                Pelanggan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium  uppercase tracking-wider">
                                Tanggal Pesan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium  uppercase tracking-wider">Total
                                Pembayaran</th>
                            <th class="px-6 py-4 text-left text-xs font-medium  uppercase tracking-wider">
                                Status Pesanan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium  uppercase tracking-wider">
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="order in paginatedOrders" :key="order.id">
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-green-600 font-medium" x-text="order.order_number"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 font-medium" x-text="order.customer_name"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900" x-text="order.date"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-gray-900 font-medium"
                                        x-text="formatCurrency(order.total_amount)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                        :class="getStatusClass(order.status)" x-text="getStatusName(order.status)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-4">
                                        <!-- View Button -->
                                        <button type="button" @click="openDetailModal(order)"
                                            class="text-green-600 hover:text-green-400 transition-colors"
                                            title="Lihat Pesanan">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>

                                        <button x-show="order.returns && order.returns.length" type="button" @click="openReturnModal(order)"
                                            class="text-green-600 hover:text-green-400 transition-colors"
                                            title="Lihat Pengembalian">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 14 4 9l5-5" />
                                                <path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11" />
                                            </svg>
                                        </button>

                                        <button
                                            x-show="order.returns && order.returns.length && order.returns[0].status === 'approved' && order.status === 'returned'"
                                            type="button" @click="openModalSend(order.id)"
                                            class="text-green-600 hover:text-green-400 transition-colors"
                                            title="Kirim Ulang">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z" />
                                                <path d="m21.854 2.147-10.94 10.939" />
                                            </svg>
                                        </button>

                                        <button type="button" @click="openModalTracking(order)" class="text-green-600 hover:text-green-400 transition-colors"
                                            title="Lihat Histori Pengiriman">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                                                <path d="M15 18H9" />
                                                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14" />
                                                <circle cx="17" cy="18" r="2" />
                                                <circle cx="7" cy="18" r="2" />
                                            </svg>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <x-admin.empty-table data="filteredOrders" />

        </div>

        <div x-show="showModalSend" class="fixed inset-0 z-50 overflow-y-auto" x-cloak
            style="backdrop-filter: blur(4px);">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModalSend"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-900 bg-opacity-60" @click="showModalSend = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showModalSend"
                    x-transition:enter="transition ease-out duration-400 delay-75"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-90"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-90"
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transform bg-white shadow-2xl rounded-2xl">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi Pesanan Siap Dikirim
                    </h3>
                    <p class="text-sm text-gray-600 text-center mb-6">Apakah Anda yakin ingin mengirim pesanan ini kembali?
                        Pastikan produk dalam kondisi baik sebelum mengirimkannya.
                    </p>

                    <form :action="baseUrl + 'admin/order/send/' + selectedOrderId" method="POST" x-cloak class="space-y-2">
                        @csrf
                        <div>
                            <x-input-label for="carrier" value="Nama Pengirim" />

                            <input id="carrier" name="carrier" placeholder="Masukkan nama pengirim"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500"
                                required />

                            @error('carrier')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <x-input-label for="notes" value="Catatan" />

                            <textarea id="notes" name="notes" placeholder="Masukkan Catatan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500"
                                required></textarea>

                            @error('notes')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex gap-3">
                            <button @click="showModalSend = false" type="button"
                                class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                Ya, Kirim
                            </button>
                        </div>
                    </form>


                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div x-show="showDetailModal"
            x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="closeDetailModal()"
            @click.self="closeDetailModal()"
            class="fixed inset-0 bg-black/60 z-[100] flex items-center justify-center p-4"
            style="display: none; backdrop-filter: blur(6px);">
            <!-- Modal Box -->
            <div x-show="showDetailModal"
                x-transition:enter="transition ease-out duration-400 delay-75"
                x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-90 translate-y-8"
                class="bg-white rounded-2xl max-w-5xl w-full shadow-2xl overflow-hidden z-[110]">

                <!-- Header -->
                <div class="bg-green-600 text-white p-6 flex justify-between items-center rounded-t-xl">
                    <div>
                        <h2 class="text-2xl font-bold">Detail Pesanan</h2>
                        <p class="text-green-100 mt-1" x-text="'ID: ' + (orderData?.order_number ?? '-')"></p>
                    </div>
                    <button @click="closeDetailModal()" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6 max-h-[70vh] overflow-y-auto">

                    <!-- Status & Info Umum -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Waiting -->
                        <template x-if="orderData?.status == 'waiting'">
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L5.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <span class="font-medium text-yellow-800"
                                        x-text="getStatusName(orderData?.status)"></span>
                                </div>
                                <p class="text-sm text-yellow-700 mt-1">Status pembayaran</p>
                            </div>
                        </template>

                        <!-- Sending -->
                        <template x-if="orderData?.status == 'sending'">
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-medium text-blue-800"
                                        x-text="getStatusName(orderData?.status)"></span>
                                </div>
                                <p class="text-sm text-blue-700 mt-1">Pesanan sedang dikirim</p>
                            </div>
                        </template>

                        <!-- Completed -->
                        <template x-if="orderData?.status == 'completed'">
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="font-medium text-green-800"
                                        x-text="getStatusName(orderData?.status)"></span>
                                </div>
                                <p class="text-sm text-green-700 mt-1">Pesanan telah selesai</p>
                            </div>
                        </template>

                        <!-- Rejected -->
                        <template x-if="orderData?.status == 'rejected'">
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="font-medium text-red-800" x-text="getStatusName(orderData?.status)"></span>
                                </div>
                                <p class="text-sm text-red-700 mt-1">Pesanan ditolak</p>
                            </div>
                        </template>

                        <!-- Rejected -->
                        <template x-if="orderData?.status == 'returned'">
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="font-medium text-red-800" x-text="getStatusName(orderData?.status)"></span>
                                </div>
                                <p class="text-sm text-red-700 mt-1">Pesanan dikembalikan</p>
                            </div>
                        </template>

                        <!-- Tanggal -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 6h6" />
                                </svg>
                                <span class="font-medium text-blue-800" x-text="orderData?.date"></span>
                            </div>
                            <p class="text-sm text-blue-700 mt-1">Tanggal pesanan</p>
                        </div>

                        <!-- Total -->
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                                <span class="font-medium text-green-800"
                                    x-text="formatCurrency(orderData?.total_amount)"></span>
                            </div>
                            <p class="text-sm text-green-700 mt-1">Total pembayaran</p>
                        </div>
                    </div>

                    <!-- Customer Info & Shipping Address -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Customer Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                Data Pelanggan
                            </h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium" x-text="orderData?.user.name"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium" x-text="orderData?.user.email"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">No. HP:</span>
                                    <span class="font-medium" x-text="orderData?.user.phone"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                                Alamat Pengiriman
                            </h3>
                            <div class="space-y-2">
                                <p class="font-medium text-gray-900" x-text="orderData?.customer.name"></p>
                                <p class="text-gray-700" x-html="orderData?.shipping_address.replace(/\n/g, '<br>')">
                                </p>
                                <p class="text-gray-600" x-text="'No. HP: ' + orderData?.customer_phone"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7H6L5 9z"></path>
                            </svg>
                            Produk Pesanan
                        </h3>
                        <div class="bg-white border rounded-lg overflow-hidden">
                            <div class="divide-y divide-gray-200">
                                <template x-for="product in orderData?.order_items" :key="product.id">
                                    <div class="p-4 flex items-center space-x-4">
                                        <img :src="baseUrl + product.products.images" :alt="product.products.name"
                                            class="w-20 h-20 object-cover rounded-lg">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900" x-text="product.products.name">
                                            </h4>
                                            <p class="text-sm text-gray-600" x-text="'Stok: ' + product.products.stock">
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium text-gray-900" x-text="product.quantity + 'x'">
                                            </p>
                                            <p class="text-sm text-gray-600" x-text="formatCurrency(product.price)">
                                            </p>
                                            <p class="font-semibold text-green-600"
                                                x-text="formatCurrency(product.subtotal)"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Proof -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Bukti Pembayaran
                        </h3>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <!-- Stack on mobile, 2 cols on md+ -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 items-start">

                                <!-- Image -->
                                <div class="order-1 md:order-none text-center">
                                    <img :src="baseUrl + orderData?.payment.payment_proof" alt="Bukti Transfer"
                                        @click="openDetailImageModal()"
                                        class="mx-auto w-40 h-56 sm:w-48 sm:h-64 md:w-48 md:h-64 object-cover rounded-lg border-2 border-gray-200 cursor-pointer hover:border-green-400 transition-colors" />
                                    <!-- Tombol lihat penuh khusus mobile -->
                                    <button @click="showDetailImageModal = true"
                                        class="md:hidden mt-3 inline-block text-green-600 hover:text-green-700 text-sm font-medium">
                                        Lihat Gambar Penuh
                                    </button>
                                </div>

                                <!-- Detail -->
                                <div class="order-2 md:order-none">
                                    <dl class="space-y-2">
                                        <div class="flex items-start justify-between gap-3">
                                            <dt class="text-gray-600">Nama Bank:</dt>
                                            <dd class="font-medium text-right break-words"
                                                x-text="paymentAccount.bank_name"></dd>
                                        </div>
                                        <div class="flex items-start justify-between gap-3">
                                            <dt class="text-gray-600">No. Rekening:</dt>
                                            <dd class="font-medium text-right break-words"
                                                x-text="paymentAccount.account_number"></dd>
                                        </div>
                                        <div class="flex items-start justify-between gap-3">
                                            <dt class="text-gray-600">Nama Penerima:</dt>
                                            <dd class="font-medium text-right break-words"
                                                x-text="paymentAccount.account_holder_name"></dd>
                                        </div>
                                        <div class="flex items-start justify-between gap-3">
                                            <dt class="text-gray-600">Tanggal Transfer:</dt>
                                            <dd class="font-medium text-right" x-text="orderData?.date"></dd>
                                        </div>
                                        <div class="flex items-start justify-between gap-3">
                                            <dt class="text-gray-600">Jumlah Transfer:</dt>
                                            <dd class="font-medium text-green-600 text-right"
                                                x-text="formatCurrency(orderData?.total_amount)"></dd>
                                        </div>
                                    </dl>

                                    <!-- Tombol lihat penuh untuk md+ -->
                                    <button @click="showDetailImageModal = true"
                                        class="hidden md:inline-block mt-3 text-green-600 hover:text-green-700 text-sm font-medium">
                                        Lihat Gambar Penuh
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-green-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Ringkasan Pesanan</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal Produk:</span>
                                <span x-text="formatCurrency(orderData?.total_amount)"></span>
                            </div>
                            <hr class="border-green-200">
                            <div class="flex justify-between font-semibold text-lg text-green-800">
                                <span>Total Pembayaran:</span>
                                <span x-text="formatCurrency(orderData?.total_amount)"></span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Action -->
                <div class="flex justify-end space-x-3 p-4 border-t bg-gray-50">
                    <form :action="baseUrl + 'admin/order/reject/' + orderData?.id" method="POST">
                        @csrf
                        <button type="submit" :disabled="orderData?.status != 'waiting'"
                            :class="orderData?.status != 'waiting' ? 'opacity-50 cursor-not-allowed' : ''"
                            class="px-6 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 hover:border-red-400 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tolak Pembayaran
                        </button>
                    </form>

                    <form :action="baseUrl + 'admin/order/approve/' + orderData?.id" method="POST">
                        @csrf
                        <template x-for="(item, i) in orderData?.order_items" :key="i">
                            <div>
                                <input type="hidden" :name="`order_items[${i}][id]`" :value="item.id">
                                <input type="hidden" :name="`order_items[${i}][quantity]`" :value="item.quantity">
                                <input type="hidden" :name="`order_items[${i}][product_id]`" :value="item.product_id">
                            </div>
                        </template>
                        <button type="submit" :disabled="orderData?.status != 'waiting'"
                            :class="orderData?.status != 'waiting' ? 'opacity-50 cursor-not-allowed' : ''"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Terima Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <!-- Full Image Modal -->
        <div x-show="showDetailImageModal"
            x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape="showDetailImageModal = false"
            @click.self="showDetailImageModal = false"
            class="fixed inset-0 bg-black bg-opacity-90 z-[120]"
            style="display: none; backdrop-filter: blur(8px);">

            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative">
                    <img :src="baseUrl + orderData?.payment.payment_proof" alt="Bukti Transfer"
                        class="max-w-full max-h-[90vh] rounded-lg">
                    <button @click="showDetailImageModal = false"
                        class="absolute top-4 right-4 bg-red-600 bg-opacity-70 text-white p-2 rounded-full hover:bg-opacity-30 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Return Image Modal -->
        <div x-show="showReturnImageModal"
            x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape="closeReturnImageModal()"
            @click.self="closeReturnImageModal()"
            class="fixed inset-0 bg-black bg-opacity-90 z-[120]"
            style="display: none; backdrop-filter: blur(8px);">

            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative">
                    <img :src="baseUrl + 'storage/' + selectedReturnImage" alt="Gambar Pengembalian"
                        class="max-w-full max-h-[90vh] rounded-lg">
                    <button @click="closeReturnImageModal()"
                        class="absolute top-4 right-4 bg-red-600 bg-opacity-70 text-white p-2 rounded-full hover:bg-opacity-30 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showTrackingModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak
            style="backdrop-filter: blur(4px);">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                <!-- Overlay -->
                <div x-show="showTrackingModal"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-900 bg-opacity-60" @click="showTrackingModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <!-- Modal -->
                <div x-show="showTrackingModal"
                    x-transition:enter="transition ease-out duration-400 delay-75"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-90"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-90"
                    class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transform bg-white shadow-2xl rounded-2xl">

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Riwayat Pesanan</h2>
                        <button @click="showTrackingModal = false" class="text-gray-400 hover:text-gray-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Timeline -->
                    <div class="relative border-l border-gray-200 pl-4">
                        <template x-for="(history, index) in orderData.histories" :key="index">
                            <div class="mb-6 ml-2">
                                <!-- Dot -->
                                <div class="absolute w-3 h-3 bg-green-500 rounded-full -left-1.5 border border-white"></div>

                                <!-- Content -->
                                <p class="text-sm font-semibold text-gray-800" x-text="formatAction(history.action)"></p>
                                <p class="text-sm text-gray-600" x-text="history.description"></p>
                                <p class="text-xs text-gray-400 mt-1" x-text="new Date(history.created_at).toLocaleString()">
                                </p>
                            </div>
                        </template>

                        <template x-if="!order.histories || order.histories.length === 0">
                            <p class="text-sm text-gray-500">Belum ada riwayat untuk pesanan ini.</p>
                        </template>
                    </div>

                    <!-- Shipment Notes -->
                    <div x-show="orderData.shipment.notes">
                        <p class="text-sm mb-1">Catatan Pengiriman : </p>
                        <div class="border p-2 rounded-xl">
                            <p class="text-sm text-gray-600" x-text="orderData.shipment.notes"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showReturnModal"
            x-transition:enter="transition ease-out duration-400"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="closeReturnModal()"
            @click.self="closeReturnModal()"
            class="fixed inset-0 bg-black/60 z-[100] flex items-center justify-center p-4"
            style="display: none; backdrop-filter: blur(6px);">

            <!-- Modal Box -->
            <div x-show="showReturnModal"
                x-transition:enter="transition ease-out duration-400 delay-75"
                x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-90 translate-y-8"
                class="bg-white rounded-2xl max-w-3xl w-full shadow-2xl overflow-hidden z-[110]">
                <!-- Header -->
                <div class="bg-green-600 text-white p-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Detail Pengembalian</h2>
                    <button @click="closeReturnModal()" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    <template x-if="orderData.returns && orderData.returns.length > 0">
                        <div>
                            <template x-for="(ret, idx) in orderData.returns" :key="ret.id ?? idx">
                                <div class="space-y-4">
                                    <div>
                                        <p class="font-medium text-gray-800">Alasan:</p>
                                        <p
                                            x-text="(ret.reason === 'defective' ? 'Produk rusak / cacat' : (ret.reason === 'wrong_item' ? 'Produk tidak sesuai pesanan' : (ret.reason === 'other' ? 'Lainnya' : ret.reason)))">
                                        </p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">Catatan Pengembalian:</p>
                                        <p x-text="(ret.comments)"></p>
                                    </div>
                                    <template x-if="ret.images && ret.images.length > 0">
                                        <div class="flex gap-3 mt-2 flex-wrap">
                                            <template x-for="(img, i) in ret.images" :key="i">
                                                <button @click="openReturnImageModal(img)" type="button"
                                                    class="focus:outline-none hover:opacity-80 transition-opacity">
                                                    <img :src="`${baseUrl}storage/${img}`"
                                                        class="w-16 h-16 rounded-lg object-cover border border-gray-200 cursor-pointer">
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                    <p><span class="font-medium text-gray-800">Status:</span> <span
                                            x-text="getReturnStatusText(ret.status)"></span></p>
                                </div>
                            </template>
                        </div>
                    </template>

                </div>

                <div class="bg-gray-50 p-4">
                    <form x-show="showFormRejected" :action="baseUrl + 'admin/return/rejected/' + orderData.returns[0]?.id"
                        method="POST" class="mt-4">
                        @csrf
                        <div>
                            <x-input-label for="admin_notes" value="Alasan menolak pengembalian" />
                            <textarea id="admin_notes" name="admin_notes" placeholder="Masukkan catatan pengembalian"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500"
                                required></textarea>
                            @error('admin_notes')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex justify-end space-x-3 p-4 ">
                            <button @click="openFormRejected()"
                                class="px-6 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 hover:border-red-400 transition-colors flex items-center">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">

                                Kirim
                            </button>
                        </div>
                    </form>
                    <div class="flex justify-end space-x-3 border-t p-4" x-show="!showFormRejected">
                        <button @click="openFormRejected()" :disabled="orderData.returns[0]?.status != 'pending'"
                            :class="orderData.returns[0]?.status != 'pending' ? 'opacity-50 cursor-not-allowed' : ''"
                            class="px-6 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 hover:border-red-400 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                            Tolak Pengembalian
                        </button>

                        <form :action="baseUrl + 'admin/return/approved/' + orderData.returns[0]?.id" method="POST">
                            @csrf
                            <button type="submit"
                                :class="orderData.returns[0]?.status != 'pending' ? 'opacity-50 cursor-not-allowed' : ''"
                                :disabled="orderData.returns[0]?.status != 'pending'"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                                Terima Pengembalian
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Pagination -->
        <div class="mt-auto">
            <!-- Pagination -->
            <x-admin.pagination data="filteredOrders" />
        </div>
    </div>

    <script>
        function orderManagement() {
            return {
                baseUrl: '{{ asset('') }}',
                // Sample data
                orders: @json($orders),
                paymentAccount: @json($paymentAccount),

                // Filter states
                searchQuery: '',
                statusFilter: 'returned',
                sortBy: 'newest',

                // Pagination states
                currentPage: 1,
                itemsPerPage: 5,
                filteredOrders: [],

                // Detail modal states
                showDetailModal: false,
                showDetailImageModal: false,
                processing: false,
                orderData: null,

                showReturnModal: false,
                showReturnImageModal: false,
                selectedReturnImage: null,

                showFormRejected: false,

                showModalSend: false,
                selectedOrderId: null,

                formConfirmActtion: '',

                showTrackingModal: false,

                init() {
                    // Baca parameter status dari URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const statusParam = urlParams.get('status');

                    // Set statusFilter dari parameter URL jika ada
                    if (statusParam) {
                        this.statusFilter = statusParam;
                    }

                    const sorted = this.sortOrders(this.orders);

                    this.filteredOrders = sorted.filter(order => {
                        if (this.statusFilter === '' || !this.statusFilter) {
                            return true; // Tampilkan semua jika filter kosong
                        }
                        return order.status == this.statusFilter;
                    });
                },

                get paginatedOrders() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;

                    return this.filteredOrders.slice(start, end);
                },

                filterOrders() {
                    let filtered = this.orders;

                    // Search filter
                    if (this.searchQuery) {
                        filtered = filtered.filter(order =>
                            order.customer_name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            order.order_number.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            order.date.toString().includes(this.searchQuery) ||
                            order.total_amount.toString().includes(this.searchQuery) ||
                            order.shipping_address.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            order.user.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            order.user.email.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            order.user.phone.toLowerCase().includes(this.searchQuery.toLowerCase())
                        );
                    }

                    // Status filter
                    if (this.statusFilter) {
                        filtered = filtered.filter(order => order.status == this.statusFilter);
                    }

                    // Sort
                    this.filteredOrders = this.sortOrders(filtered);
                    this.currentPage = 1;
                },


                sortOrders(orders) {
                    const sorted = [...orders];

                    switch (this.sortBy) {
                        case 'newest':
                            return sorted.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                        case 'oldest':
                            return sorted.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                        case 'highest':
                            return sorted.sort((a, b) => b.total_amount - a.total_amount);
                        case 'lowest':
                            return sorted.sort((a, b) => a.total_amount - b.total_amount);
                        default:
                            return sorted;
                    }
                },

                get totalPages() {
                    return Math.ceil(this.filteredOrders.length / this.itemsPerPage);
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

                formatCurrency(price) {
                    // Convert to number if it's a string
                    const numPrice = typeof price === 'string' ? parseFloat(price) : price;

                    // Format with Indonesian locale (dots as thousands separator)
                    return 'Rp ' + numPrice.toLocaleString('id-ID', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                },

                getStatusClass(status) {
                    switch (status) {
                        case 'pending':
                            return 'bg-yellow-100 text-yellow-800 border border-yellow-300';
                        case 'waiting':
                            return 'bg-orange-100 text-orange-800 border border-orange-300';
                        case 'verified':
                            return 'bg-cyan-100 text-cyan-800 border border-cyan-300';
                        case 'processing':
                            return 'bg-blue-100 text-blue-800 border border-blue-300';
                        case 'sending':
                            return 'bg-indigo-100 text-indigo-800 border border-indigo-300';
                        case 'shipped':
                            return 'bg-purple-100 text-purple-800 border border-purple-300';
                        case 'completed':
                            return 'bg-green-100 text-green-800 border border-green-300';
                        case 'delivered':
                            return 'bg-emerald-100 text-emerald-800 border border-emerald-300';
                        case 'cancelled':
                            return 'bg-red-100 text-red-800 border border-red-300';
                        case 'rejected':
                            return 'bg-red-100 text-red-800 border border-red-300';
                        case 'returned':
                            return 'bg-pink-100 text-pink-800 border border-pink-300';
                        default:
                            return 'bg-gray-100 text-gray-800 border border-gray-300';
                    }
                },
                getStatusName(status) {
                    switch (status) {
                        case 'pending':
                            return 'Menunggu';
                        case 'waiting':
                            return 'Menunggu Pembayaran';
                        case 'verified':
                            return 'Terverifikasi';
                        case 'processing':
                            return 'Diproses';
                        case 'sending':
                            return 'Sedang Dikirim';
                        case 'shipped':
                            return 'Dikirim';
                        case 'completed':
                            return 'Selesai';
                        case 'delivered':
                            return 'Diterima';
                        case 'cancelled':
                            return 'Dibatalkan';
                        case 'rejected':
                            return 'Ditolak';
                        case 'returned':
                            return 'Dikembalikan';
                        default:
                            return status;
                    }
                },

                openReturnModal(curOrder) {
                    this.orderData = curOrder;
                    this.showReturnModal = true;
                    this.showReturnImageModal = false;
                    document.body.classList.add('overflow-hidden');
                },

                closeReturnModal() {
                    this.showReturnModal = false;
                    document.body.classList.remove('overflow-hidden');
                },

                openReturnImageModal(img) {
                    this.selectedReturnImage = img;
                    this.showReturnImageModal = true;
                },

                closeReturnImageModal() {
                    this.showReturnImageModal = false;
                    this.selectedReturnImage = null;
                },

                openFormRejected() {
                    this.showFormRejected = !this.showFormRejected;
                },

                openModalSend(orderId) {
                    this.selectedOrderId = orderId;
                    this.showModalSend = true;
                },

                openModalTracking(curOrder) {
                    this.orderData = curOrder;
                    this.showTrackingModal = true;
                },

                processConfirmation() {
                    if (this.selectedOrderId) {
                        const url = this.formConfirmActtion = this.baseUrl + 'admin/order/resend/' + this.selectedOrderId;
                        document.getElementById('form-confirm').setAttribute('action', url);
                        document.getElementById('form-confirm').submit();
                    }
                    this.showModalSend = false;
                    this.selectedOrderId = null;
                },

                getReturnStatusText(status) {
                    const labels = {
                        pending: 'Menunggu',
                        approved: 'Disetujui',
                        rejected: 'Ditolak',
                    };
                    return labels[status] || status;
                },



                /**
                 *
                 * Detail modal handlers
                 *
                 */

                openDetailModal(curOrder) {
                    this.orderData = curOrder;
                    this.showDetailModal = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeDetailModal() {
                    this.showDetailModal = false;
                    this.showDetailImageModal = false;
                    document.body.classList.remove('overflow-hidden');
                },
                openDetailImageModal() {
                    this.showDetailImageModal = true;
                },
                approvePayment() {
                    if (!confirm('Apakah Anda yakin ingin menerima pembayaran ini?')) {
                        return;
                    }
                },

                rejectPayment() {
                    if (!confirm('Apakah Anda yakin ingin menolak pembayaran ini?')) {
                        return;
                    }
                },


            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection
