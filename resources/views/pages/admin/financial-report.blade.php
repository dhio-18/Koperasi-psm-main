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

            <!-- Download Button -->
            <div>
                <a href="{{ route('superadmin.financial-report', [
                    'start_date' => request('start_date', $customStartDate ?? $startDate->format('Y-m-d')),
                    'end_date' => request('end_date', $customEndDate ?? $endDate->format('Y-m-d')),
                    'download' => 'pdf'
                ]) }}"
                    class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-medium shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
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
            <div class="p-6 border-b">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Detail Transaksi Selesai</h2>
                        <p class="text-sm text-gray-600 mt-1">Menampilkan {{ $orderDetails->count() }} dari
                            {{ $orderDetails->total() }} transaksi</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $totalOrders }} Transaksi</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Order ID</th>
                            <th class="px-4 py-3 text-left">Pelanggan</th>
                            <th class="px-4 py-3 text-left">Produk</th>
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
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $order->orderItems->count() }} Item
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

        <!-- Chart & Top Products -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Chart -->
            <div
                class="bg-white p-6 rounded-xl shadow-md border border-gray-100 lg:col-span-2 hover:shadow-lg transition duration-300">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Grafik Pendapatan Harian</h2>
                    <p class="text-xs text-gray-500 mt-1">Periode: {{ $startDate->format('d M Y') }} -
                        {{ $endDate->format('d M Y') }}</p>
                </div>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Produk Paling Banyak Dijual</h2>
                    <p class="text-xs text-gray-500 mt-1">Top 5 produk periode {{ $startDate->format('d M') }} -
                        {{ $endDate->format('d M Y') }}</p>
                </div>
                <div class="space-y-3">
                    @forelse($topProducts as $index => $product)
                        <div
                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-green-300 hover:shadow-sm transition duration-200 bg-white">
                            <!-- Ranking Number -->
                            <div class="flex-shrink-0">
                                <span class="text-lg font-bold text-gray-700">{{ $index + 1 }}</span>
                            </div>
                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $product['product_name'] }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                        </svg>
                                        {{ $product['quantity'] }} Terjual
                                    </span>
                                    <span class="text-xs font-semibold text-green-600">
                                        Rp {{ number_format($product['revenue'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-sm">Tidak ada produk terjual pada periode ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dailyData = @json($dailyData);

        function reportCharts() {
            return {
                chart: null,
                init() {
                    const ctx = document.getElementById('revenueChart').getContext('2d');

                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
                    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.3)');

                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: dailyData.map(d => d.day),
                            datasets: [{
                                label: 'Pendapatan (Rp)',
                                data: dailyData.map(d => d.revenue),
                                backgroundColor: gradient,
                                borderColor: '#10b981',
                                borderWidth: 2,
                                borderRadius: 8,
                                hoverBackgroundColor: 'rgba(16, 185, 129, 0.9)',
                                hoverBorderWidth: 3,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        font: {
                                            size: 14,
                                            weight: '500'
                                        },
                                        padding: 15,
                                        usePointStyle: true,
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 13
                                    },
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(context) {
                                            return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(
                                                context.parsed.y);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                        drawBorder: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 12
                                        },
                                        callback: function(value) {
                                            if (value >= 1000000) {
                                                return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                            } else if (value >= 1000) {
                                                return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                            }
                                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            }
                        }
                    });
                }
            }
        }
    </script>
@endsection
