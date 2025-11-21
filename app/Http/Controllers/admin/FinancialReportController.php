<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Returns;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FinancialReportController extends Controller
{
    /**
     * Halaman Laporan Keuangan
     */
    public function index(Request $request)
    {
        $customStartDate = $request->get('start_date');
        $customEndDate = $request->get('end_date');

        // Tentukan rentang tanggal (default: bulan ini)
        $startDate = $customStartDate ? Carbon::parse($customStartDate)->startOfDay() : now()->startOfMonth();
        $endDate = $customEndDate ? Carbon::parse($customEndDate)->endOfDay() : now()->endOfMonth();
        $periodLabel = 'Custom Period';

        // Data Pendapatan (hanya pesanan selesai)
        $totalRevenue = Orders::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Total Pesanan Selesai
        $totalOrders = Orders::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Data Harian untuk Chart (hanya pesanan selesai)
        $dailyData = [];
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dayRevenue = Orders::where('status', 'completed')
                ->whereDate('created_at', $current)
                ->sum('total_amount');

            $dailyData[] = [
                'date' => $current->format('Y-m-d'),
                'day' => $current->format('D, d M'),
                'revenue' => $dayRevenue,
            ];

            $current->addDay();
        }

        // Produk Terlaris (hanya dari pesanan selesai)
        $topProducts = Orders::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('orderItems.products')
            ->get()
            ->flatMap(function ($order) {
                return $order->orderItems;
            })
            ->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product_name' => $items->first()->products->name ?? 'Unknown',
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    }),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values();

        // Detail Pesanan dengan Pagination (hanya pesanan selesai, 10 per halaman)
        $orderDetails = Orders::with('user', 'orderItems.products')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(10)
            ->appends([
                'start_date' => $customStartDate,
                'end_date' => $customEndDate
            ]);

        return view('pages.admin.financial-report', compact(
            'periodLabel',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'dailyData',
            'topProducts',
            'orderDetails',
            'customStartDate',
            'customEndDate'
        ));
    }


}
