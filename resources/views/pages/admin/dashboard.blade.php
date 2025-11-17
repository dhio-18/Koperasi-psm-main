@extends('layouts.admin-layout')

@section('title')
    <title>Dashboard</title>
@endsection

@section('main')
    <div class="p-6 space-y-6 bg-gray-50 min-h-screen">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600">Selamat datang kembali! Berikut ini yang sedang terjadi di toko Anda hari ini.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Total Orders (biru) -->
            <div
                class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-600">Total Pesanan</div>
                    <div class="text-2xl font-bold mt-1 text-gray-900">{{ $totalOrders }}</div>
                    <div class="text-blue-600 text-sm mt-1">+{{ $ordersThisWeek }} pesanan minggu ini</div>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="6" y="3" width="12" height="18" rx="2" ry="2"></rect>
                        <path d="M9 7h6M9 11h6M9 15h6"></path>
                    </svg>
                </div>
            </div>

            <!-- Barang Belum Terkirim (orange) -->
            <div
                class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-600">Belum Terkirim</div>
                    <div class="text-2xl font-bold mt-1 text-gray-900">{{ $totalPendingOrders }}</div>
                    <div class="text-orange-600 text-sm mt-1">Perlu diproses</div>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                </div>
            </div>

            <!-- Barang Terkirim (kuning) -->
            <div
                class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-600">Barang Terkirim</div>
                    <div class="text-2xl font-bold mt-1 text-gray-900">{{ $totalCompletedOrders }}</div>
                    <div class="text-yellow-600 text-sm mt-1">+{{ $completedOrdersThisWeek }} minggu ini</div>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="7" width="11" height="8" rx="1"></rect>
                        <path d="M14 9h4l3 3v3h-7z"></path>
                        <circle cx="7.5" cy="17" r="1.5"></circle>
                        <circle cx="17.5" cy="17" r="1.5"></circle>
                    </svg>
                </div>
            </div>

            <!-- Pengembalian (merah) -->
            <div
                class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-600">Pengembalian</div>
                    <div class="text-2xl font-bold mt-1 text-gray-900">{{ $totalReturns }}</div>
                    <div class="text-red-600 text-sm mt-1">Permintaan retur terkini</div>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M15 20a5 5 0 0 0 0-10H7"></path>
                        <path d="M9 6L5 10l4 4"></path>
                    </svg>
                </div>
            </div>

        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Pesanan</h2>
                        <p class="text-sm text-gray-500 mt-1">Kelola dan pantau semua pesanan</p>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.dashboard') }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $statusFilter === 'all' ? 'bg-green-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            Semua
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'waiting']) }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $statusFilter === 'waiting' ? 'bg-orange-600 text-white shadow-md' : 'bg-orange-50 text-orange-700 hover:bg-orange-100 border border-orange-200' }}">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Menunggu Konfirmasi
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'verified']) }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $statusFilter === 'verified' ? 'bg-cyan-600 text-white shadow-md' : 'bg-cyan-50 text-cyan-700 hover:bg-cyan-100 border border-cyan-200' }}">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Terverifikasi
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'sending']) }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $statusFilter === 'sending' ? 'bg-indigo-600 text-white shadow-md' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200' }}">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Sedang Dikirim
                        </a>
                        <a href="{{ route('admin.dashboard', ['status' => 'return']) }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $statusFilter === 'return' ? 'bg-pink-600 text-white shadow-md' : 'bg-pink-50 text-pink-700 hover:bg-pink-100 border border-pink-200' }}">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            Retur
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-4 text-left">Pelanggan</th>
                            <th class="px-6 py-4 text-left">Order ID</th>
                            <th class="px-6 py-4 text-left">Jumlah</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            @php
                                // Cek apakah pesanan memiliki pengembalian
                                $hasReturn = $order->returns && $order->returns->count() > 0;
                                // Tentukan route tujuan
                                $targetRoute = $hasReturn ? route('admin.return') : route('admin.orders');
                                // Tambahkan parameter status jika bukan 'all'
                                $targetUrl = $targetRoute . ($statusFilter !== 'all' ? '?status=' . $statusFilter : '');
                            @endphp
                            <tr class="hover:bg-green-50 transition duration-150 cursor-pointer"
                                onclick="window.location='{{ $targetUrl }}'">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->customer_phone }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono text-sm font-medium text-gray-700">#{{ $order->order_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <x-status-badge :status="$order->status" size="md" />
                                        @if ($hasReturn)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-pink-100 text-pink-800 border border-pink-300">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                </svg>
                                                Retur
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-700">
                                        <p class="font-medium">{{ $order->created_at->format('d M Y') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-base">Tidak ada pesanan</p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                @if ($statusFilter === 'waiting')
                                                    Tidak ada pesanan yang menunggu konfirmasi
                                                @elseif($statusFilter === 'verified')
                                                    Tidak ada pesanan yang terverifikasi
                                                @elseif($statusFilter === 'sending')
                                                    Tidak ada pesanan yang sedang dikirim
                                                @else
                                                    Belum ada pesanan yang dibuat
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-center">
                        <!-- Tombol Navigasi -->
                        <div class="flex items-center gap-2">
                            @if ($orders->onFirstPage())
                                <span
                                    class="appearance-none border border-gray-300 rounded-lg px-2.5 py-1 text-xs sm:text-sm
                                 shadow-sm transition-colors
                                 disabled:text-gray-300 disabled:cursor-not-allowed">
                                    Sebelumnya
                                </span>
                            @else
                                <a href="{{ $orders->previousPageUrl() }}"
                                    class="appearance-none border border-gray-300 rounded-lg px-2.5 py-1 text-xs sm:text-sm
                                 shadow-sm transition-colors
                                 hover:bg-green-600 hover:text-white">
                                    Sebelumnya
                                </a>
                            @endif

                            <span class="px-1 text-xs sm:text-sm text-gray-700">
                                <span class="text-green-600 font-semibold">{{ $orders->currentPage() }}</span>
                                <span class="text-gray-400">/</span>
                                <span class="font-semibold">{{ $orders->lastPage() }}</span>
                            </span>

                            @if ($orders->hasMorePages())
                                <a href="{{ $orders->nextPageUrl() }}"
                                    class="appearance-none border border-gray-300 rounded-lg px-2.5 py-1 text-xs sm:text-sm
                                 shadow-sm transition-colors
                                 hover:bg-green-600 hover:text-white">
                                    Selanjutnya
                                </a>
                            @else
                                <span
                                    class="appearance-none border border-gray-300 rounded-lg px-2.5 py-1 text-xs sm:text-sm
                                 shadow-sm transition-colors
                                 disabled:text-gray-300 disabled:cursor-not-allowed">
                                    Selanjutnya
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
