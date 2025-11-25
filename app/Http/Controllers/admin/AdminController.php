<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\OrderHistory;
use App\Models\Orders;
use App\Models\PaymentAccounts;
use App\Models\Payments;
use App\Models\Products;
use App\Models\Returns;
use App\Models\Shipments;
use App\Services\FileUploadService;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Str;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalOrders = Orders::whereNot('status', 'rejected')->count();
        $ordersThisWeek = Orders::whereNot('status', 'rejected')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $totalRevenue = Orders::whereNot('status', 'rejected')
            ->whereNot('status', 'waiting')
            ->sum('total_amount');

        $revenueThisWeek = Orders::whereNot('status', 'rejected')
            ->whereNot('status', 'waiting')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total_amount');

        $totalReturns = Returns::where('status', 'pending')->count();

        $totalCompletedOrders = Orders::where('status', 'completed')->count();
        $completedOrdersThisWeek = Orders::where('status', 'completed')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $totalPendingOrders = Orders::whereIn('status', ['pending', 'waiting', 'verified', 'processing'])
            ->count();

        $totalVerifiedOrders = Orders::where('status', 'verified')->count();

        $statusFilter = $request->get('status', 'all');

        $ordersQuery = Orders::with('returns')
            ->where(function ($query) {
                $query->where('status', 'waiting')
                    ->orWhere('status', 'verified')
                    ->orWhereHas('returns', function ($subQuery) {
                        $subQuery->where('status', 'pending');
                    });
            });

        if ($statusFilter !== 'all') {
            if ($statusFilter === 'return') {
                $ordersQuery->whereHas('returns', function ($query) {
                    $query->where('status', 'pending');
                });
            } elseif ($statusFilter === 'waiting') {
                $ordersQuery->where('status', 'waiting');
            } elseif ($statusFilter === 'verified') {
                $ordersQuery->where('status', 'verified');
            }
        }

        $orders = $ordersQuery->latest()->paginate(10)->appends(['status' => $statusFilter]);

        return view('pages.admin.dashboard', [
            'totalOrders' => $totalOrders,
            'ordersThisWeek' => $ordersThisWeek,
            'totalRevenue' => $totalRevenue,
            'revenueThisWeek' => $revenueThisWeek,
            'totalReturns' => $totalReturns,
            'totalCompletedOrders' => $totalCompletedOrders,
            'completedOrdersThisWeek' => $completedOrdersThisWeek,
            'totalPendingOrders' => $totalPendingOrders,
            'totalVerifiedOrders' => $totalVerifiedOrders,
            'orders' => $orders,
            'statusFilter' => $statusFilter,
        ]);
    }

    /**
     * Kelola Kategori
     */
    public function category()
    {
        $categories = Categories::with('products')->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'image' => $category->image,
                'name' => $category->name,
                'product_count' => $category->products->count(),
                'status' => $category->is_active,
            ];
        });

        return view('pages.admin.category', compact('categories'));
    }

    /**
     * Tambah Kategori Baru
     */
    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:categories,name',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 50 karakter.',
            'name.unique' => 'Nama kategori sudah ada.',
            'icon.required' => 'Icon kategori wajib diunggah.',
            'icon.image' => 'File harus berupa gambar.',
            'icon.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
            'icon.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $fileUploadService = new FileUploadService();
            $image_path = $fileUploadService->upload($request, 'icon', 'categories');

            Categories::create([
                'slug' => Str::slug($validated['name']),
                'name' => $validated['name'],
                'image' => $image_path,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage());
        }
    }

    /**
     * Update Kategori
     */
    public function categoryUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:categories,name,' . $id,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 50 karakter.',
            'name.unique' => 'Nama kategori sudah ada.',
            'icon.image' => 'File harus berupa gambar.',
            'icon.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
            'icon.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'slug' => Str::slug($validated['name']),
                'name' => $validated['name'],
            ];

            // Hanya ubah icon jika ada file baru
            if ($request->hasFile('icon')) {
                $fileUploadService = new FileUploadService();
                $image_path = $fileUploadService->upload($request, 'icon', 'categories');
                if ($image_path) {
                    $updateData['image'] = $image_path;
                }
            }

            Categories::where('id', $id)->update($updateData);

            DB::commit();
            return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Kategori
     */
    public function categoryDelete($id)
    {
        try {
            DB::beginTransaction();

            Categories::where('id', $id)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }

    /**
     * Kelola Produk
     */
    public function product(Request $request)
    {
        $categories = Categories::where('is_active', 1)->get();

        // Filter status produk
        $statusFilter = $request->get('status', 'active');

        $productsQuery = Products::with('category');

        if ($statusFilter === 'active') {
            $productsQuery->where('is_active', 1);
        } elseif ($statusFilter === 'inactive') {
            $productsQuery->where('is_active', 0);
        }
        // 'all' akan menampilkan semua produk

        $products = $productsQuery->get();

        return view('pages.admin.product', compact('categories', 'products', 'statusFilter'));
    }

    /**
     * Toggle Status Produk (Aktif/Non-Aktif)
     */
    public function productToggleStatus($id)
    {
        try {
            $product = Products::findOrFail($id);
            $product->is_active = !$product->is_active;
            $product->save();

            $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()->with('success', "Produk berhasil {$status}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah status produk: ' . $e->getMessage());
        }
    }

    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'expired_date' => 'nullable|date|after_or_equal:today',
            'description' => 'nullable|string',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $fileUploadService = new FileUploadService();
            $image_path = $fileUploadService->upload($request, 'images', 'products');

            Products::create([
                'slug' => Str::slug($validated['name']),
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'expired_date' => $validated['expired_date'] ?? null,
                'description' => $validated['description'] ?? null,
                'images' => $image_path,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function productUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:products,name,' . $id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'expired_date' => 'nullable|date|after_or_equal:today',
            'description' => 'nullable|string',
            'images' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'slug' => Str::slug($validated['name']),
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'expired_date' => $validated['expired_date'] ?? null,
                'description' => $validated['description'] ?? null,
            ];

            if ($request->hasFile('images')) {
                $fileUploadService = new FileUploadService();
                $image_path = $fileUploadService->upload($request, 'images', 'products');
                $updateData['images'] = $image_path;
            }

            Products::where('id', $id)->update($updateData);

            DB::commit();
            return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function productDelete($id)
    {
        try {
            DB::beginTransaction();
            Products::where('id', $id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Kelola Pesanan
     */
    public function order(Request $request)
    {
        $type = DB::select("SHOW COLUMNS FROM orders WHERE Field = 'status'")[0]->Type;
        preg_match("/^enum\('(.*)'\)$/", $type, $matches);
        $enumValues = explode("','", $matches[1]);

        $statusFilter = $request->get('status', null);

        $ordersQuery = Orders::with('user', 'orderItems.products', 'payment', 'returns', 'shipment', 'histories')
            ->whereDoesntHave('returns');

        if ($statusFilter) {
            $ordersQuery->where('status', $statusFilter);
        }

        $orders = $ordersQuery->get()
            ->map(function ($order) {
                $order->date = Carbon::parse($order->created_at)->format('d-m-Y');
                return $order;
            });

        $paymentAccount = PaymentAccounts::where('is_active', 1)->firstOrFail();

        return view('pages.admin.order', [
            'status' => $enumValues,
            'orders' => $orders,
            'paymentAccount' => $paymentAccount,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function return(Request $request)
    {
        $type = DB::select("SHOW COLUMNS FROM orders WHERE Field = 'status'")[0]->Type;
        preg_match("/^enum\('(.*)'\)$/", $type, $matches);
        $enumValues = explode("','", $matches[1]);

        $statusFilter = $request->get('status', null);

        $ordersQuery = Orders::with('user', 'orderItems.products', 'payment', 'returns', 'histories')
            ->whereHas('returns');

        if ($statusFilter) {
            $ordersQuery->where('status', $statusFilter);
        }

        $orders = $ordersQuery->get()
            ->map(function ($order) {
                $order->date = Carbon::parse($order->created_at)->format('d-m-Y');
                return $order;
            });

        $paymentAccount = PaymentAccounts::where('is_active', 1)->firstOrFail();

        return view('pages.admin.return', [
            'status' => $enumValues,
            'orders' => $orders,
            'paymentAccount' => $paymentAccount,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function approvePayment(Request $request, $id)
    {
        $validated = $request->validate([
            'order_items' => 'required',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['order_items'] as $item) {
                $product = Products::find($item['product_id']);
                if ($product) {
                    $product->stock = max(0, $product->stock - $item['quantity']);
                    $product->save();
                }
            }
            $order = Orders::findOrFail($id);

            if (!Storage::exists('public/invoices')) {
                Storage::makeDirectory('public/invoices');
            }

            $pdf = Pdf::loadView('pdf.invoice', compact('order'))->setPaper('a4');
            $fileName = 'invoice-' . str_replace('#', '', $order->order_number) . '.pdf';

            // 🔹 Simpan ke storage publik (seperti kamu simpan images)
            $path = 'invoices/' . $fileName;
            Storage::disk('public')->put($path, $pdf->output());
            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'verified',
                'description' => 'Pesanan telah diverifikasi oleh ' . Auth::user()->name,
            ]);

            $order = Orders::findOrFail($id);
            $order->status = 'verified';
            $order->invoice_path = $fileName;
            $order->save();

            DB::commit();
            return redirect()->route('admin.orders', ['status' => 'verified'])
                ->with('success', 'Pembayaran berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyetujui pembayaran: ' . $e->getMessage());
        }
    }

    public function orderSend(Request $request, $id)
    {
        $validated = $request->validate([
            'carrier' => 'required|string|max:100',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $order = Orders::with('orderItems')->findOrFail($id);

            $trackingNumber = strtoupper('TRK-' . Str::random(10));

            foreach ($order->orderItems as $item) {
                $product = Products::find($item->product_id);
                if ($product) {
                    $product->stock = max(0, $product->stock - $item->quantity);
                    $product->save();
                }
            }
            $order->status = 'sending';
            $order->save();

            Shipments::create([
                'order_id' => $order->id,
                'tracking_number' => $trackingNumber,
                'carrier' => $validated['carrier'],
                'status' => 'shipped',
                'shipped_at' => Carbon::now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'shipped',
                'description' => 'Pesanan dikirim via ' . $validated['carrier'],
            ]);

            DB::commit();
            return redirect()->route('admin.orders', ['status' => 'sending'])
                ->with('success', 'Pesanan berhasil dikirim.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengirim pesanan: ' . $e->getMessage());
        }
    }

    public function rejectPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $order = Orders::findOrFail($id);
            $order->status = 'rejected';
            $order->rejection_reason = $validated['rejection_reason'];
            $order->save();

            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'rejected',
                'description' => 'Pembayaran ditolak oleh ' . Auth::user()->name . '. Alasan: ' . $validated['rejection_reason'],
            ]);

            DB::commit();
            return redirect()->route('admin.orders', ['status' => 'rejected'])
                ->with('success', 'Pembayaran berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    public function returnRejected(Request $request, $id)
    {

        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $return = Returns::findOrFail($id);
            $return->status = 'rejected';
            $return->admin_notes = $validated['admin_notes'];
            $return->processed_by = auth()->id();
            $return->processed_at = Carbon::now();
            $return->save();

            $order = Orders::findOrFail($return->order_id);
            $order->status = 'completed';
            $order->save();

            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'rejected',
                'description' => 'Pengembalian ditolak oleh ' . Auth::user()->name,
            ]);

            DB::commit();
            // return redirect()->route('admin.return', ['status' => 'rejected'])
            //     ->with('success', 'Pengembalian berhasil ditolak.');
            return redirect()->back()->with('success', 'Pengembalian berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menolak pengembalian: ' . $e->getMessage());
        }
    }

    public function returnApproved(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $return = Returns::findOrFail($id);
            $return->status = 'approved';
            $return->processed_by = auth()->id();
            $return->processed_at = Carbon::now();
            $return->save();

            $order = Orders::with('orderItems')->findOrFail($return->order_id);
            $order->status = 'returned';
            $order->save();

            // Kembalikan stok produk
            foreach ($order->orderItems as $item) {
                $product = Products::find($item->product_id);
                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }
            }

            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'returned',
                'description' => 'Pengembalian disetujui oleh ' . Auth::user()->name . '. Stok produk telah dikembalikan.',
            ]);

            DB::commit();
            return redirect()->route('admin.return', ['status' => 'returned'])
                ->with('success', 'Pengembalian berhasil disetujui dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Return approval error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyetujui pengembalian: ' . $e->getMessage());
        }
    }

    public function payment()
    {
        return view('pages.admin.payment');
    }

    public function shipping()
    {
        return view('pages.admin.shipping');
    }
}
