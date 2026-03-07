@extends('layouts.admin-layout')

@section('title')
    <title>Laporan Keuangan</title>
@endsection

@section('main')
    <div class="p-6 space-y-6 bg-gray-50 min-h-screen" x-data="reportCharts()" x-init="init()">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Laporan Keuangan</h1>
                <p class="text-gray-600">Laporan {{ $periodLabel }} - {{ $startDate->format('d M Y') }} s/d
                    {{ $endDate->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Filter Periode -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Pilih Periode Laporan
            </h3>
            <form method="GET" action="{{ route('superadmin.financial-report') }}"
                class="grid grid-cols-1 md:grid-cols-3 gap-4" id="filterForm">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" required
                        value="{{ request('start_date', $customStartDate ?? $startDate->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" name="end_date" required
                        value="{{ request('end_date', $customEndDate ?? $endDate->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-medium shadow-md hover:shadow-lg">
                        <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>

        <!-- Detail Transaksi -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300">
            <div class="p-6 border-b flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Detail Transaksi Selesai</h2>
                    <p class="text-xs text-gray-500 mt-1">Periode: {{ $startDate->format('d M Y') }} -
                        {{ $endDate->format('d M Y') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Total Info -->
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $totalOrders }} Transaksi</p>
                    </div>
                    <!-- Download Button untuk PDF Transaksi -->
                    <a href="{{ route('superadmin.financial-report', [
                        'start_date' => request('start_date', $customStartDate ?? $startDate->format('Y-m-d')),
                        'end_date' => request('end_date', $customEndDate ?? $endDate->format('Y-m-d')),
                        'download' => 'pdf'
                    ]) }}"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-medium shadow-md hover:shadow-lg text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download PDF
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Order ID</th>
                            <th class="px-4 py-3 text-left">Pelanggan</th>
                            <th class="px-4 py-3 text-center">Produk</th>
                            <th class="px-4 py-3 text-left">Jumlah</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderDetails as $index => $order)
                            <tr class="border-t border-gray-100 hover:bg-gray-50 transition duration-200">
                                <td class="px-4 py-3 text-gray-600">
                                    {{ ($orderDetails->currentPage() - 1) * $orderDetails->perPage() + $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">#{{ $order->order_number }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $order->customer_name }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                        </svg>
                                        {{ $order->orderItems->count() }} Item
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-900">Rp
                                    {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Tidak ada transaksi pada periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($orderDetails->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Info -->
                        <div class="text-sm text-gray-600">
                            Menampilkan
                            <span class="font-semibold text-gray-900">{{ $orderDetails->firstItem() }}</span>
                            sampai
                            <span class="font-semibold text-gray-900">{{ $orderDetails->lastItem() }}</span>
                            dari
                            <span class="font-semibold text-gray-900">{{ $orderDetails->total() }}</span>
                            transaksi
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center gap-2">
                            {{-- Previous Button --}}
                            @if ($orderDetails->onFirstPage())
                                <span class="px-3 py-2 text-sm bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                    Sebelumnya
                                </span>
                            @else
                                <a href="{{ $orderDetails->previousPageUrl() }}"
                                    class="px-3 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Sebelumnya
                                </a>
                            @endif
                            {{-- Next Button --}}
                            @if ($orderDetails->hasMorePages())
                                <a href="{{ $orderDetails->nextPageUrl() }}"
                                    class="px-3 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Berikutnya
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                    Berikutnya
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Seluruh Produk yang Terjual -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300">
            <div class="p-6 border-b flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Seluruh Produk yang Terjual</h2>
                    <p class="text-xs text-gray-500 mt-1">Periode: {{ $startDate->format('d M Y') }} -
                        {{ $endDate->format('d M Y') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Total Info -->
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalProductRevenue, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $totalProductQuantity }} Item</p>
                    </div>
                    <!-- Download Button untuk PDF Produk -->
                    <a href="{{ route('superadmin.financial-report', [
                        'start_date' => request('start_date', $customStartDate ?? $startDate->format('Y-m-d')),
                        'end_date' => request('end_date', $customEndDate ?? $endDate->format('Y-m-d')),
                        'download' => 'pdf-products'
                    ]) }}"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-medium shadow-md hover:shadow-lg text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download PDF
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Nama Produk</th>
                            <th class="px-4 py-3 text-center">Total Terjual</th>
                            <th class="px-4 py-3 text-right">Harga Satuan</th>
                            <th class="px-4 py-3 text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productsPaginated as $index => $product)
                            <tr class="border-t border-gray-100 hover:bg-gray-50 transition duration-200">
                                <td class="px-4 py-3 text-gray-600">
                                    {{ ($productsPaginated->currentPage() - 1) * $productsPaginated->perPage() + $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $product['product_name'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                        </svg>
                                        {{ $product['quantity'] }} Item
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-gray-700">
                                    Rp {{ number_format($product['unit_price'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($product['revenue'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    Tidak ada produk terjual pada periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($productsPaginated->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Info -->
                        <div class="text-sm text-gray-600">
                            Menampilkan
                            <span class="font-semibold text-gray-900">{{ $productsPaginated->firstItem() }}</span>
                            sampai
                            <span class="font-semibold text-gray-900">{{ $productsPaginated->lastItem() }}</span>
                            dari
                            <span class="font-semibold text-gray-900">{{ $productsPaginated->total() }}</span>
                            produk
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center gap-2">
                            {{-- Previous Button --}}
                            @if ($productsPaginated->onFirstPage())
                                <span class="px-3 py-2 text-sm bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                    Sebelumnya
                                </span>
                            @else
                                <a href="{{ $productsPaginated->previousPageUrl() }}"
                                    class="px-3 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Sebelumnya
                                </a>
                            @endif
                            {{-- Next Button --}}
                            @if ($productsPaginated->hasMorePages())
                                <a href="{{ $productsPaginated->nextPageUrl() }}"
                                    class="px-3 py-2 text-sm bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Berikutnya
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                    Berikutnya
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
