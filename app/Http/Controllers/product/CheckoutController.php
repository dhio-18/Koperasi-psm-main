<?php
// app/Http/Controllers/product/CheckoutController.php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\OrderHistory;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Payments;
use App\Models\Products;
use App\Models\UserAdresses;
use App\Models\PaymentAccounts;
use App\Services\FileUploadservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * GET /checkout/cart
     * Menampilkan halaman checkout dari session (alur dari keranjang).
     */
    public function checkoutCart()
    {
        $address         = session('address', []);
        $orderItems      = session('orderItems', []);
        $cartItemIds     = session('cartItemIds', []);

        // Ubah: ambil semua akun pembayaran aktif, bukan cuma satu
        $paymentAccounts = PaymentAccounts::where('is_active', true)->orderBy('bank_name')->get();

        return view('pages.product.checkout', compact('address', 'orderItems', 'paymentAccounts'));
    }

    /**
     * POST /checkout
     * Datang dari tombol petir (Beli Sekarang).
     * Perbaikan: qty diambil dari request dan diteruskan ke view.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty'        => ['nullable', 'integer', 'min:1'],
        ]);

        $productId = (int) $request->input('product_id');
        $qty       = (int) $request->input('qty', 1);

        // Pastikan user punya alamat
        $userAddressCount = UserAdresses::where('user_id', Auth::id())->count();
        if ($userAddressCount === 0) {
            return redirect()->route('user.profile.address')
                ->with('error', 'Silakan tambahkan alamat terlebih dahulu sebelum checkout.');
        }

        $address = UserAdresses::where('user_id', Auth::id())->get();

        // Ambil produk dari DB (pakai harga dari DB)
        $product = Products::select('id', 'name', 'price', 'stock')->findOrFail($productId);

        // (Opsional) validasi stok
        if (!is_null($product->stock) && $product->stock < $qty) {
            return back()->with('error', "Stok produk tidak cukup untuk {$product->name}");
        }

        // Bentuk item untuk ditampilkan di checkout
        $orderItems = [[
            'product_id' => $product->id,
            'name'       => $product->name,
            'quantity'   => $qty,                 // qty dari tombol +/-
            'price'      => (int) $product->price,
        ]];

        // Ubah: ambil semua akun pembayaran aktif
        $paymentAccounts = PaymentAccounts::where('is_active', true)->orderBy('bank_name')->get();

        return view('pages.product.checkout', compact('address', 'orderItems', 'paymentAccounts'));
    }

    /**
     * POST /checkout/process
     * Proses form checkout. Total dihitung ulang di server.
     */
    public function checkoutProcess(Request $request)
    {
        $request->validate([
            'sender_name'   => 'required',
            'address'       => 'required|integer|exists:user_adresses,id', // sesuaikan jika nama tabel berbeda
            'order_items'   => 'required|json',
            'total_amount'  => 'required',
            'payment_proof' => 'image|max:2048',
            // Tambahan: akun pembayaran harus dipilih
            'payment_account_id' => 'required|integer|exists:payment_accounts,id',
        ]);

        try {
            // Ambil item dari form (skema kamu saat ini)
            $orderItems = json_decode($request->input('order_items'), true) ?: [];

            // Validasi stok
            foreach ($orderItems as $item) {
                $product = Products::find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    return redirect()->back()->with('error', 'Stok produk tidak cukup: ' . $item['name']);
                }
            }

            // Hitung ulang total di server (lebih aman)
            $serverTotal = array_sum(array_map(function ($i) {
                return ((int) $i['price']) * ((int) $i['quantity']);
            }, $orderItems));

            // Upload bukti transfer
            $FileServices = new FileUploadservice();
            $payment_proof = $FileServices->upload($request, 'payment_proof', 'payments');

            // Ambil alamat
            $address = optional(UserAdresses::find($request->input('address')));
            $full_address = $address->full_address;

            DB::beginTransaction();

            // Simpan order
            $order = Orders::create([
                'order_number'     => '#ORD-' . substr(str_replace('.', '', microtime(true)), -8),
                'user_id'          => Auth::id(),
                'customer_name'    => $request->input('sender_name'),
                'customer_email'   => Auth::user()->email,
                'customer_phone'   => $address->phone,
                'shipping_address' => $full_address,
                'total_amount'     => $serverTotal, // gunakan total dari server
            ]);

            // Simpan item
            foreach ($orderItems as $item) {
                OrderItems::create([
                    'order_id'   => $order->id,
                    'product_id' => (int) $item['product_id'],
                    'quantity'   => (int) $item['quantity'],
                    'price'      => (int) $item['price'],
                ]);
            }

            // Simpan pembayaran
            $paymentData = [
                'order_id'      => $order->id,
                'amount'        => $serverTotal,
                'payment_proof' => $payment_proof,
                'transfer_date' => now(),
                'sender_name'   => $request->input('sender_name'),
            ];

            // Tambahan: simpan akun pembayaran yang dipilih (jika kolom tersedia)
            if (\Schema::hasColumn('payments', 'payment_account_id')) {
                $paymentData['payment_account_id'] = (int) $request->input('payment_account_id');
            } elseif (\Schema::hasColumn('payments', 'payment_method_id')) {
                $paymentData['payment_method_id'] = (int) $request->input('payment_account_id');
            }

            Payments::create($paymentData);

            // Jika checkout dari cart
            $cartItemIds = session('cartItemIds', []);
            if (!empty($cartItemIds)) {
                DB::table('carts')->whereIn('id', $cartItemIds)->delete();
            }

            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'waiting',
                'description' => 'Pesanan telah dibuat ' . Auth::user()->name,
            ]);

            DB::commit();

            // Bersihkan session terkait cart
            session()->forget(['address', 'orderItems', 'cartItemIds']);

            return redirect()->route('home')->with('success', 'Checkout berhasil diproses!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
