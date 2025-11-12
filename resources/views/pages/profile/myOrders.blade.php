@extends('layouts.layout')

@section('title')
    <title>Profile</title>
@endsection

@section('main')
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-[auto_1fr] items-start gap-8 px-6 py-14 md:grid">
        <!-- Left Sidebar -->
        <x-profile.sidebar />

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200/60" x-data="orderManager()">
            <div class="container mx-auto px-4 py-8">
                {{-- Header --}}
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Pesananku</h1>
                </div>

                <!-- Search and Filter Section -->
                <div class="mb-6 space-y-4">
                    <!-- Search Bar and Sort Section -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Search Bar -->
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" x-model="searchQuery" @input="filterOrders()"
                                placeholder="Cari pesanan berdasarkan nomor order atau nama produk..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        </div>

                        <!-- Sort Dropdown -->
                        <div class="relative sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                </svg>
                            </div>
                            <select x-model="sortOrder" @change="filterOrders()"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors appearance-none bg-white cursor-pointer">
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter Tabs -->
                    <div class="flex flex-wrap gap-2">
                        <button @click="selectedStatus = 'all'; filterOrders()"
                            :class="selectedStatus === 'all' ? 'bg-green-600 text-white' :
                                'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            Semua (<span x-text="orders.length"></span>)
                        </button>
                        <button @click="selectedStatus = 'waiting'; filterOrders()"
                            :class="selectedStatus === 'waiting' ? 'bg-yellow-600 text-white' :
                                'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            Menunggu (<span x-text="getOrdersByStatus('waiting').length"></span>)
                        </button>
                        <button @click="selectedStatus = 'verified'; filterOrders()"
                            :class="selectedStatus === 'verified' ? 'bg-blue-400 text-white' :
                                'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            Terverifikasi (<span x-text="getOrdersByStatus('verified').length"></span>)
                        </button>
                        <button @click="selectedStatus = 'sending'; filterOrders()"
                            :class="selectedStatus === 'sending' ? 'bg-blue-600 text-white' :
                                'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            Dikirim (<span x-text="getOrdersByStatus('sending').length"></span>)
                        </button>
                        <button @click="selectedStatus = 'completed'; filterOrders()"
                            :class="selectedStatus === 'completed' ? 'bg-green-600 text-white' :
                                'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            Selesai (<span x-text="getOrdersByStatus('completed').length"></span>)
                        </button>
                        <button @click="selectedStatus = 'rejected'; filterOrders()"
                            :class="selectedStatus === 'rejected' ? 'bg-red-600 text-white' :
                                'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            Ditolak (<span x-text="getOrdersByStatus('rejected').length"></span>)
                        </button>
                        <button @click="selectedStatus = 'returned'; filterOrders()"
                            :class="selectedStatus === 'returned' ? 'bg-orange-600 text-white' :
                                'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg font-medium transition-colors">
                            Dikembalikan (<span x-text="getOrdersByStatus('returned').length"></span>)
                        </button>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="space-y-4 max-h-[100vh] overflow-y-auto">
                    <template x-for="order in getFilteredOrders" :key="order.id">
                        <div class="border border-gray-200 rounded-lg hover:shadow-md transition-shadow duration-200">

                            <!-- Order Header -->
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                        <div class="flex items-center gap-3">
                                            <h3 class="font-semibold text-gray-900" x-text="order.order_number"></h3>
                                            <span
                                                :class="{
                                                    'bg-yellow-100 text-yellow-800 border-yellow-300': order
                                                        .status === 'pending',
                                                    'bg-orange-100 text-orange-800 border-orange-300': order
                                                        .status === 'waiting',
                                                    'bg-cyan-100 text-cyan-800 border-cyan-300': order
                                                        .status === 'verified',
                                                    'bg-blue-100 text-blue-800 border-blue-300': order
                                                        .status === 'processing',
                                                    'bg-indigo-100 text-indigo-800 border-indigo-300': order
                                                        .status === 'sending',
                                                    'bg-purple-100 text-purple-800 border-purple-300': order
                                                        .status === 'shipped',
                                                    'bg-green-100 text-green-800 border-green-300': order
                                                        .status === 'completed',
                                                    'bg-emerald-100 text-emerald-800 border-emerald-300': order
                                                        .status === 'delivered',
                                                    'bg-red-100 text-red-800 border-red-300': order
                                                        .status === 'cancelled' || order.status === 'rejected',
                                                    'bg-pink-100 text-pink-800 border-pink-300': order
                                                        .status === 'returned',
                                                }"
                                                class="px-2 py-1 text-xs font-medium rounded-full border"
                                                x-text="getStatusText(order.status)"></span>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <span x-text="order.date"></span>
                                        </div>
                                        <a x-show="order?.invoice_path"
                                            :href="'{{ asset('storage/invoices') }}' + '/' + order?.invoice_path"
                                            target="_blank"
                                            class="text-xs underline py-1 px-2 rounded-full cursor-pointer bg-green-100 text-black border-green-200">Lihat
                                            Invoice</a>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button @click="toggleOrderDetails(order.id)"
                                            class="text-green-600 hover:text-green-700 font-medium text-sm flex items-center gap-1">
                                            <span>Detail</span>
                                            <svg :class="order.showDetails ? 'rotate-180' : ''"
                                                class="w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Notes (Outside Detail) - Pindah ke atas setelah Order Header -->
                            <div x-show="(order.status === 'sending' || order.status === 'completed') && order.shipment && order.shipment.notes && order.shipment.notes !== null && order.shipment.notes !== ''"
                                class="mx-4 mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-blue-900 mb-1">Catatan Pengiriman</p>
                                        <p class="text-xs text-blue-700" x-text="order.shipment.notes"></p>
                                        <p class="text-xs text-blue-600 mt-1">
                                            Pengirim: <span class="font-medium" x-text="order.shipment.carrier"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejection Reason Alert (if exists) -->
                            <div x-show="order.rejection_reason && order.rejection_reason !== null && order.rejection_reason !== ''"
                                class="mx-4 mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-red-900 mb-1">Pesanan Ditolak</p>
                                        <p class="text-xs text-red-700" x-text="order.rejection_reason"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Summary (ringkasan di kartu) -->
                            <div class="p-4">
                                <template x-for="item in order.order_items" :key="item.id">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <img :src="baseUrl + item.products.images" :alt="item.products.name"
                                                class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                            <div>
                                                <p class="font-medium text-gray-900" x-text="item.products.name"></p>
                                                <p class="text-sm text-gray-500"><span x-text="item.quantity"></span> item
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900" x-text="formatCurrency(item.price)">
                                            </p>
                                        </div>
                                    </div>
                                </template>
                                <div class="border-t border-gray-100 pt-3 mt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold text-gray-900">Total</span>
                                        <span class="font-bold text-gray-900" x-text="formatCurrency(order.total_amount)"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Details (Collapsible) - SEKARANG di dalam loop -->
                            <template x-if="order.showDetails">
                                <div class="border-t border-gray-100" x-transition>
                                    <div class="p-4 space-y-4">

                                        <!-- Shipping Address -->
                                        <div>
                                            <h4 class="font-medium text-gray-900 mb-2">Alamat Pengiriman</h4>
                                            <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg leading-relaxed">
                                                <p class="font-medium text-gray-800">Koperasi PSM</p>
                                                <p>Hutan Register Kec. Pakuan Ratu, Kabupaten Way Kanan, Lampung 34762</p>
                                            </div>
                                        </div>

                                        <template x-if="order.returns && order.returns.length > 0">
                                            <div class="mt-4">
                                                <h4 class="font-medium text-gray-900 mb-2">Detail Pengembalian</h4>
                                                <div>
                                                    <template x-for="(ret, idx) in order.returns" :key="ret.id ?? idx">
                                                        <div class="p-3 bg-gray-50 rounded-lg space-y-3">
                                                            <div>
                                                                <p class="font-medium text-gray-800">Alasan:</p>
                                                                <p class="text-gray-700"
                                                                    x-text="(ret.reason === 'defective' ? 'Produk rusak / cacat' : (ret.reason === 'wrong_item' ? 'Produk tidak sesuai pesanan' : (ret.reason === 'other' ? 'Lainnya' : ret.reason)))">
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="font-medium text-gray-800">Catatan Pengembalian:
                                                                </p>
                                                                <p class="text-gray-700" x-text="(ret.comments)"></p>
                                                            </div>
                                                            <template x-if="ret.images && ret.images.length > 0">
                                                                <div class="flex gap-3 mt-2 flex-wrap">
                                                                    <template x-for="(img, i) in ret.images"
                                                                        :key="i">
                                                                        <button @click="openReturnImageModal(img)" type="button" class="cursor-pointer hover:opacity-90 transition-opacity">
                                                                            <img :src="`${baseUrl}storage/${img}`"
                                                                                class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                                                                        </button>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                            <p class="text-gray-700"><span
                                                                    class="font-medium text-gray-800">Status:</span> <span
                                                                    x-text="getStatusReturnText(ret.status)"></span></p>
                                                            <template
                                                                x-if="ret.admin_notes && (ret.status === 'rejected' || ret.status === 'approved')">
                                                                <div
                                                                    class="mt-3 p-3 bg-gray-50 border-l-4 border-gray-400 rounded-r">
                                                                    <p class="font-medium text-gray-800 mb-1">
                                                                        <span
                                                                            x-text="ret.status === 'rejected' ? 'Alasan Penolakan:' : 'Catatan Admin:'"></span>
                                                                    </p>
                                                                    <p class="text-gray-700" x-text="ret.admin_notes"></p>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="!order.returns || order.returns.length === 0">
                                            <!-- No returns found - only show if returns is explicitly empty or undefined -->
                                        </template>

                                        <!-- Timeline -->
                                        <div class="mt-6">
                                            <h4 class="font-medium text-gray-900 mb-3">Status Pesanan</h4>

                                            <div class="space-y-4 border-l-2 border-gray-200 pl-4">
                                                <template x-for="(history, index) in order.histories"
                                                    :key="index">
                                                    <div class="relative pb-4">
                                                        <div
                                                            class="absolute -left-[23px] w-3 h-3 bg-green-500 rounded-full border border-white">
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <p class="text-sm font-semibold text-gray-900 capitalize"
                                                                x-text="formatAction(history.action)">
                                                            </p>
                                                            <p class="text-sm text-gray-700" x-text="history.description">
                                                            </p>
                                                            <p class="text-xs text-gray-500"
                                                                x-text="formatDate(history.created_at)">
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </template>

                            <!-- Alasan Penolakan Pengembalian (Tampil di atas Catatan Pengiriman) -->
                            <template x-if="order.returns && order.returns.length > 0">
                                <template x-for="(ret, idx) in order.returns" :key="ret.id ?? idx">
                                    <div x-show="ret.admin_notes && ret.status === 'rejected'" class="mx-4 mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                </path>
                                            </svg>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-red-900 mb-1">Alasan Penolakan:</p>
                                                <p class="text-xs text-red-700" x-text="ret.admin_notes"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </template>
                            <!-- Actions when sending -->
                            <div class="px-4 pb-4 pt-2">
                                <!-- Auto-confirm notification -->
                                <template x-if="order.status === 'sending'">
                                    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-blue-900 mb-1">Konfirmasi Otomatis</p>
                                                <p class="text-xs text-blue-700">
                                                    Jika tidak dikonfirmasi atau di-retur, pesanan akan otomatis
                                                    terkonfirmasi dalam waktu
                                                    <span class="font-semibold"
                                                        x-text="getAutoConfirmCountdown(order.updated_at)"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div class="flex flex-wrap gap-2">
                                    <template x-if="order.status === 'sending'">
                                        <button @click="confirmOrder(order.id)"
                                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                            Konfirmasi Pesanan Diterima
                                        </button>
                                    </template>
                                    <template x-if="order.status === 'sending' &&  order.returns.length === 0">
                                        <button @click="returnOrder(order.id)"
                                            class="px-4 py-2 text-green-600 border border-green-600 text-sm font-medium rounded-lg transition-colors">
                                            Ajukan Pengembalian
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <div x-show="filteredOrders?.length === 0" class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M8 11v6h8v-6M8 11H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-2">
                            </path>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium mb-2">Tidak ada pesanan ditemukan</p>
                        <p class="text-gray-400">Coba ubah filter atau kata kunci pencarian</p>
                    </div>
                </div>


                <!-- Confirmation Modal -->
                <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak
                    style="backdrop-filter: blur(4px);">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-400"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60"
                            @click="showConfirmModal = false"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                        <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-400"
                            x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-90"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-90"
                            class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transform bg-white shadow-2xl rounded-2xl">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi Pesanan Diterima
                            </h3>
                            <p class="text-sm text-gray-600 text-center mb-6">Apakah Anda yakin pesanan sudah diterima
                                dengan baik?
                                Tindakan ini tidak dapat dibatalkan.</p>

                            <div class="flex gap-3">
                                <button @click="showConfirmModal = false"
                                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                    Batal
                                </button>
                                <button @click="processConfirmation()"
                                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                    Ya, Konfirmasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Form -->
                <form id="form-confirm" action="" method="POST">
                    @csrf
                </form>


                <div x-show="showReturnModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak
                    style="backdrop-filter: blur(4px);">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showReturnModal" x-transition:enter="transition ease-out duration-400"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-60"
                            @click="showReturnModal = false"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                        <div x-show="showReturnModal" x-transition:enter="transition ease-out duration-400"
                            x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-90"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-90"
                            class="inline-block w-full max-w-md my-8 overflow-hidden text-left  max-h-[90vh] align-middle transform bg-white shadow-2xl rounded-2xl">
                            <form id="form-return" method="POST" enctype="multipart/form-data"
                                :action="baseUrl + 'user/profile/orders/return/' + selectedOrderId"
                                class="space-y-4 overflow-y-auto max-h-[90vh] p-6">
                                @csrf
                                <div>
                                    <x-input-label for="reason" value="Pilih Alasan Pengembalian" />

                                    <select id="reason" name="reason" x:model="returnData.reason"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 appearance-none bg-white"
                                        required>
                                        <option value="" selected disabled>Pilih alasan</option>
                                        <option value="defective">Produk rusak / cacat</option>
                                        <option value="wrong_item">Produk tidak sesuai pesanan</option>
                                        <option value="other">Lainnya</option>
                                    </select>

                                    @error('reason')
                                        <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <x-input-label for="comments" value="Catatan Pengembalian" />
                                    <textarea id="comments" name="comments" placeholder="Masukkan catatan pengembalian" x:model="returnData.comments"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-green-500" required></textarea>
                                    @error('comments')
                                        <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>

                                    <x-input-label for="images" value="Bukti Kondisi" />

                                    <!-- Image Preview Area -->
                                    <div @click="$refs.imageInput.click()"
                                        class="p-4 w-full h-48 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex items-center justify-center relative overflow-hidden cursor-pointer">
                                        <!-- Image Preview -->
                                        <!-- Placeholder -->
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500">Product Condition</p>
                                        </div>
                                    </div>
                                    <div x-show="imagePreview.length > 0"
                                        class="grid grid-cols-2 sm:grid-cols-3 gap-3 w-full h-full overflow-auto p-2">
                                        <template x-for="(src, index) in imagePreview" :key="index">
                                            <div class="relative">
                                                <img :src="src" alt="Preview"
                                                    class="w-full h-32 object-cover rounded-md border">
                                                <button type="button" @click="removeImage(index)"
                                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full text-xs px-2 py-1 hover:bg-red-600">
                                                    ✕
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- File Input (Hidden) -->
                                    <input type="file" x-ref="imageInput" name="images[]" accept="image/*" multiple
                                        @change="handleImageUpload($event)" class="hidden">

                                    <!-- Upload Buttons -->
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
                                <div class="flex justify-end space-x-3 border-t border-gray-200 p-4">
                                    <button type="button" @click="showReturnModal = false; resetForm()"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200">
                                        Batal
                                    </button>
                                    <button type="submit" @click="prepareForm()"
                                        class="px-6 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors duration-200">
                                        Kirim
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Return Image Modal -->
                <div x-show="showReturnImageModal"
                    x-transition:enter="transition ease-out duration-400"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    @keydown.escape="closeReturnImageModal()"
                    @click.self="closeReturnImageModal()"
                    class="fixed inset-0 bg-black bg-opacity-90 z-[120]">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="relative">
                            <img :src="baseUrl + 'storage/' + selectedReturnImage"
                                class="max-w-full max-h-[90vh] rounded-lg">
                            <button @click="closeReturnImageModal()"
                                class="absolute top-4 right-4 bg-red-600 bg-opacity-70 hover:bg-opacity-100 text-white p-2 rounded-full transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function orderManager() {
            return {
                baseUrl: '{{ asset('') }}',

                orders: @json($orders),

                searchQuery: '',
                selectedStatus: 'all',
                sortOrder: 'newest', // Default sorting: newest first
                showConfirmModal: false,
                showReturnModal: false,
                selectedOrderId: null,
                filteredOrders: [],
                formConfirmActtion: '',

                imagePreview: [],
                showReturnImageModal: false,
                selectedReturnImage: null,

                returnData: {
                    reason: '',
                    comments: '',
                    images: []
                },

                init() {
                    this.filterOrders();

                    // Debug: Log orders structure untuk lihat returns data
                    console.log('Orders data:', this.orders);
                    this.orders.forEach(order => {
                        console.log(`Order ${order.id}:`, {
                            order_number: order.order_number,
                            status: order.status,
                            returns: order.returns,
                            returns_length: order.returns ? order.returns.length : 0
                        });
                    });

                    // Update countdown every minute
                    setInterval(() => {
                        this.$nextTick(() => {
                            // Force Alpine to re-evaluate the countdown
                            this.orders = [...this.orders];
                        });
                    }, 60000); // Update every 60 seconds
                },
                filterOrders() {
                    // Data orders sudah diurutkan dari terbaru ke terlama dari server (created_at DESC)
                    let filtered = this.orders;

                    // Filter by status
                    if (this.selectedStatus !== 'all') {
                        filtered = filtered.filter(order => order.status === this.selectedStatus);
                    }

                    // Filter by search query
                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(order =>
                            order.order_number.toLowerCase().includes(query) ||
                            order.total_amount.toString().includes(query) ||
                            order.order_items.some(item => item.products.name.toLowerCase().includes(query)) ||
                            order.order_items.some(item => item.products.description.toLowerCase().includes(query))
                        );
                    }

                    // Sort by date
                    filtered = filtered.sort((a, b) => {
                        const dateA = new Date(a.created_at);
                        const dateB = new Date(b.created_at);

                        if (this.sortOrder === 'newest') {
                            return dateB - dateA; // Terbaru ke terlama
                        } else {
                            return dateA - dateB; // Terlama ke terbaru
                        }
                    });

                    this.filteredOrders = filtered;
                },

                get getFilteredOrders() {
                    return this.filteredOrders;
                },

                getOrdersByStatus(status) {
                    return this.orders.filter(order => order.status === status);
                },

                getStatusText(status) {
                    const statusMap = {
                        'pending': 'Menunggu',
                        'waiting': 'Menunggu Pembayaran',
                        'verified': 'Terverifikasi',
                        'processing': 'Diproses',
                        'sending': 'Sedang Dikirim',
                        'shipped': 'Dikirim',
                        'completed': 'Selesai',
                        'delivered': 'Diterima',
                        'cancelled': 'Dibatalkan',
                        'rejected': 'Ditolak',
                        'returned': 'Dikembalikan',
                    };
                    return statusMap[status] || status;
                },

                getStatusReturnText(status) {
                    const statusMap = {
                        'pending': 'Menunggu',
                        'approved': 'Disetujui',
                        'rejected': 'Ditolak',
                    };
                    return statusMap[status] || status;
                },

                toggleOrderDetails(orderId) {
                    const order = this.orders.find(o => o.id === orderId);
                    if (order) {
                        order.showDetails = !order.showDetails;
                    }
                },

                confirmOrder(orderId) {
                    this.selectedOrderId = orderId;
                    this.showConfirmModal = true;
                },

                returnOrder(orderId) {
                    this.selectedOrderId = orderId;
                    this.showReturnModal = true;
                },

                processConfirmation() {
                    if (this.selectedOrderId) {
                        const url = this.formConfirmActtion = this.baseUrl + 'user/profile/orders/' + this.selectedOrderId;
                        document.getElementById('form-confirm').setAttribute('action', url);
                        document.getElementById('form-confirm').submit();
                    }
                    this.showConfirmModal = false;
                    this.selectedOrderId = null;
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

                handleImageUpload(event) {
                    const files = event.target.files;
                    if (!files || files.length === 0) return;

                    Array.from(files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (e) => this.imagePreview.push(e.target.result);
                        reader.readAsDataURL(file);
                    });

                    const dt = new DataTransfer();
                    this.returnData.images.forEach(f => dt.items.add(f));
                    Array.from(files).forEach(f => dt.items.add(f));

                    this.$refs.imageInput.files = dt.files;
                    this.returnData.images = Array.from(dt.files);
                },

                removeImage(index) {
                    this.imagePreview.splice(index, 1);

                    const dt = new DataTransfer();
                    this.returnData.images.forEach((f, i) => {
                        if (i !== index) dt.items.add(f);
                    });

                    this.$refs.imageInput.files = dt.files;
                    this.returnData.images = Array.from(dt.files);
                },

                resetForm() {
                    this.imagePreview = [];
                    this.returnData.images = [];

                    this.$refs.imageInput.value = '';
                    const dt = new DataTransfer();
                    this.$refs.imageInput.files = dt.files;
                },

                formatDate(dateString) {
                    if (!dateString) return '-';
                    const date = new Date(dateString);
                    return date.toLocaleString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                formatAction(action) {
                    const labels = {
                        waiting: 'Pesanan Dibuat',
                        verified: 'Pesanan Diverifikasi',
                        shipped: 'Pesanan Dikirim',
                        completed: 'Pesanan Diterima',
                        returned: 'Pesanan Dikembalikan',
                        rejected: 'Pesanan Ditolak',
                    };
                    return labels[action] || action;
                },

                // Helper untuk decode HTML entities
                decodeHtml(html) {
                    const txt = document.createElement('textarea');
                    txt.innerHTML = html;
                    return txt.value;
                },

                getAutoConfirmCountdown(updatedAt) {
                    if (!updatedAt) return 'menghitung...';

                    const now = new Date();
                    const orderDate = new Date(updatedAt);
                    const cutoffTime = new Date(orderDate.getTime() + (8 * 60 * 60 * 1000)); // 8 hours in milliseconds

                    const diff = cutoffTime - now;

                    if (diff <= 0) {
                        return 'segera';
                    }

                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                    if (hours > 0) {
                        return `${hours} jam ${minutes} menit`;
                    } else {
                        return `${minutes} menit`;
                    }
                },

                openReturnImageModal(img) {
                    this.selectedReturnImage = img;
                    this.showReturnImageModal = true;
                },

                closeReturnImageModal() {
                    this.showReturnImageModal = false;
                    this.selectedReturnImage = null;
                }

            }


        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endsection
