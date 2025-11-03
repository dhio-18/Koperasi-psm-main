@extends('layouts.admin-layout')

@section('title')
    <title>Dashboard</title>
@endsection

@section('main')
    <div class="p-6 space-y-6 bg-gray-50 min-h-screen" x-data="charts()" x-init="init()">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600">Selamat datang kembali! Berikut ini yang sedang terjadi di toko Anda hari ini.</p>
        </div>

        <!-- Stats Cards -->
        <div class="w-full flex justify-center items-center">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mx-auto">

                <!-- Total Orders (biru) -->
                <div
                    class="w-full bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-600">Total Pesanan</div>
                        <div class="text-2xl font-bold mt-1 text-gray-900">{{ $totalOrders }}</div>
                        <div class="text-blue-600 text-sm mt-1">+{{ $ordersThisWeek }} pesanan minggu ini</div>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="6" y="3" width="12" height="18" rx="2" ry="2"></rect>
                            <path d="M9 7h6M9 11h6M9 15h6"></path>
                        </svg>
                    </div>
                </div>

                <!-- Total Pendapatan (hijau) -->
                <div
                    class="w-full bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-600">Total Pendapatan</div>
                        <div class="text-2xl font-bold mt-1 text-gray-900">
                            Rp.{{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        <div class="text-green-600 text-sm mt-1">+{{ $revenueThisWeek }} dari minggu ini</div>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="6" width="18" height="12" rx="2" ry="2"></rect>
                            <path d="M3 10h18"></path>
                            <path d="M7 15h6"></path>
                        </svg>
                    </div>
                </div>

                <!-- Pengembalian (merah) -->
                <div
                    class="w-full bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-600">Pengembalian</div>
                        <div class="text-2xl font-bold mt-1 text-gray-900">{{ $totalReturns }}</div>
                        <div class="text-red-600 text-sm mt-1">Permintaan retur terkini</div>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-red-600" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M15 20a5 5 0 0 0 0-10H7"></path>
                            <path d="M9 6L5 10l4 4"></path>
                        </svg>
                    </div>
                </div>

                <!-- Barang Terkirim (kuning) -->
                <div
                    class="w-full bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300 flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-600">Barang Terkirim</div>
                        <div class="text-2xl font-bold mt-1 text-gray-900">{{ $totalCompletedOrders }}</div>
                        <div class="text-yellow-600 text-sm mt-1">+{{ $completedOrdersThisWeek }} minggu ini</div>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="7" width="11" height="8" rx="1"></rect>
                            <path d="M14 9h4l3 3v3h-7z"></path>
                            <circle cx="7.5" cy="17" r="1.5"></circle>
                            <circle cx="17.5" cy="17" r="1.5"></circle>
                        </svg>
                    </div>
                </div>

            </div>
        </div>

        <!-- Analytics + Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Analytics -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 lg:col-span-2 hover:shadow-lg transition duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Ringkasan Analisis Mingguan</h2>
                </div>
                <div class="h-64">
                    <canvas id="analyticsChart"></canvas>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300">
                <h2 class="text-lg font-semibold mb-4 text-gray-900">Tombol Cepat</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.products') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 hover:bg-green-50 flex items-center gap-3 transition-colors">
                        <div class="shrink-0 w-10 h-10 rounded-lg bg-green-100 grid place-items-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <span class="text-gray-900">Tambah Produk</span>
                    </a>

                    <a href="{{ route('admin.orders') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 hover:bg-green-50 flex items-center gap-3 transition-colors">
                        <div class="shrink-0 w-10 h-10 rounded-lg bg-green-100 grid place-items-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <span class="text-gray-900">Pesanan</span>
                    </a>

                    <a href="{{ route('admin.return') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 hover:bg-green-50 flex items-center gap-3 transition-colors">
                        <div class="shrink-0 w-10 h-10 rounded-lg bg-green-100 grid place-items-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 15l-6-6 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>
                        </div>
                        <span class="text-gray-900">Pengembalian</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300">
            <div class="p-4 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2 text-left">Pelanggan</th>
                            <th class="px-4 py-2 text-left">Order ID</th>
                            <th class="px-4 py-2 text-left">Jumlah</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestOrders as $order)
                            <tr class="border-t hover:bg-gray-50 transition duration-200">
                                <td class="px-4 py-2">{{ $order->customer_name }}</td>
                                <td class="px-4 py-2">#{{ $order->order_number }}</td>
                                <td class="px-4 py-2">Rp.{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-green-600">{{ ucfirst($order->status) }}</td>
                                <td class="px-4 py-2">{{ $order->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dailyOrders = @json($dailyOrders);
        function charts() {
            return {
                chart: null,
                init() {
                    const ctx = document.getElementById('analyticsChart').getContext('2d');
                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                            datasets: [{
                                label: 'Pesanan',
                                data: dailyOrders,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true,
                                pointRadius: 4,
                                pointBackgroundColor: '#10b981'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: true, position: 'bottom' }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        callback: (value) => value.toFixed(0)
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }
    </script>
@endsection
