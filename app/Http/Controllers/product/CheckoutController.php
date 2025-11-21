<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\OrderHistory;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Payments;
use App\Models\Products;
use App\Models\UserAdresses;
use App\Models\PaymentAccounts;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    /**
     * Cek apakah checkout diperbolehkan (sebelum jam 17:00 WIB)
     */
    private function isCheckoutAllowed()
    {
        $now = Carbon::now('Asia/Jakarta');
        $cutoffTime = Carbon::today('Asia/Jakarta')->setTime(17, 0, 0); // 17:00 WIB
        
        return $now->lessThan($cutoffTime);
    }

    /**
     * Get remaining time until cutoff
     */
    private function getRemainingTime()
    {
        $now = Carbon::now('Asia/Jakarta');
        $cutoffTime = Carbon::today('Asia/Jakarta')->setTime(17, 0, 0);
        
        if ($now->greaterThanOrEqualTo($cutoffTime)) {
            // Sudah lewat cutoff, hitung ke besok
            $nextCutoff = Carbon::tomorrow('Asia/Jakarta')->setTime(17, 0, 0);
            return $now->diffForHumans($nextCutoff, ['parts' => 2]);
        }
        
        return $now->diffForHumans($cutoffTime, ['parts' => 2]);
    }

    /**
     * GET /checkout/cart
     * Menampilkan halaman checkout dari session (alur dari keranjang).
     */
    public function checkoutCart()
    {
        $address         = session('address', []);
        $orderItems      = session('orderItems', []);
        $cartItemIds     = session('cartItemIds', []);

        $paymentAccounts = PaymentAccounts::where('is_active', true)->orderBy('bank_name')->get();

        // Pass data waktu checkout ke view
        $checkoutAllowed = $this->isCheckoutAllowed();
        $remainingTime = $this->getRemainingTime();

        return view('pages.product.checkout', compact(
            'address', 
            'orderItems', 
            'paymentAccounts',
            'checkoutAllowed',
            'remainingTime'
        ));
    }

    /**
     * POST /checkout
     * Datang dari tombol petir (Beli Sekarang).
     */
    public function checkout(Request $request)
    {
        // Validasi waktu checkout
        if (!$this->isCheckoutAllowed()) {
            return redirect()->back()->with('error', 
                'Maaf, checkout hanya dapat dilakukan sebelum jam 17:00 WIB. Silakan coba lagi besok.');
        }

        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty'        => ['nullable', 'integer', 'min:1'],
        ]);

        $productId = (int) $request->input('product_id');
        $qty       = (int) $request->input('qty', 1);

        $userAddressCount = UserAdresses::where('user_id', Auth::id())->count();
        if ($userAddressCount === 0) {
            return redirect()->route('user.profile.address')
                ->with('error', 'Silakan tambahkan alamat terlebih dahulu sebelum checkout.');
        }

        $address = UserAdresses::where('user_id', Auth::id())->get();
        $product = Products::select('id', 'name', 'price', 'stock')->findOrFail($productId);

        if (!is_null($product->stock) && $product->stock < $qty) {
            return back()->with('error', "Stok produk tidak cukup untuk {$product->name}");
        }

        $orderItems = [[
            'product_id' => $product->id,
            'name'       => $product->name,
            'quantity'   => $qty,
            'price'      => (int) $product->price,
        ]];

        $paymentAccounts = PaymentAccounts::where('is_active', true)->orderBy('bank_name')->get();

        // Pass data waktu checkout ke view
        $checkoutAllowed = $this->isCheckoutAllowed();
        $remainingTime = $this->getRemainingTime();

        return view('pages.product.checkout', compact(
            'address', 
            'orderItems', 
            'paymentAccounts',
            'checkoutAllowed',
            'remainingTime'
        ));
    }

    /**
     * POST /checkout/process
     * Proses form checkout dengan validasi waktu.
     */
    public function checkoutProcess(Request $request)
    {
        // CRITICAL: Validasi waktu checkout di backend
        if (!$this->isCheckoutAllowed()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Maaf, waktu checkout telah berakhir (setelah jam 17:00 WIB). Silakan coba lagi besok.');
        }

        $request->validate([
            'sender_name'   => 'required|string|max:255',
            'address'       => 'required|integer|exists:user_adresses,id',
            'order_items'   => 'required|json',
            'total_amount'  => 'required|numeric|min:0',
            'payment_proof' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'payment_account_id' => 'required|integer|exists:payment_accounts,id,is_active,1',
        ]);

        $paymentAccount = PaymentAccounts::where('id', $request->input('payment_account_id'))
            ->where('is_active', true)
            ->first();

        if (!$paymentAccount) {
            return redirect()->back()->with('error', 'Akun pembayaran yang dipilih tidak tersedia.');
        }

        try {
            $orderItems = json_decode($request->input('order_items'), true) ?: [];

            // Validasi stok
            foreach ($orderItems as $item) {
                $product = Products::find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    return redirect()->back()->with('error', 'Stok produk tidak cukup: ' . $item['name']);
                }
            }

            $serverTotal = array_sum(array_map(function ($i) {
                return ((int) $i['price']) * ((int) $i['quantity']);
            }, $orderItems));

            $FileServices = new FileUploadService();
            $payment_proof = $FileServices->upload($request, 'payment_proof', 'payments');

            $address = optional(UserAdresses::find($request->input('address')));
            $full_address = $address->full_address;

            DB::beginTransaction();

            $order = Orders::create([
                'order_number'     => '#ORD-' . substr(str_replace('.', '', microtime(true)), -8),
                'user_id'          => Auth::id(),
                'customer_name'    => $request->input('sender_name'),
                'customer_email'   => Auth::user()->email,
                'customer_phone'   => $address->phone,
                'shipping_address' => $full_address,
                'total_amount'     => $serverTotal,
            ]);

            foreach ($orderItems as $item) {
                OrderItems::create([
                    'order_id'   => $order->id,
                    'product_id' => (int) $item['product_id'],
                    'quantity'   => (int) $item['quantity'],
                    'price'      => (int) $item['price'],
                ]);
            }

            $paymentData = [
                'order_id'      => $order->id,
                'amount'        => $serverTotal,
                'payment_proof' => $payment_proof,
                'transfer_date' => now(),
                'sender_name'   => $request->input('sender_name'),
            ];

            if (\Schema::hasColumn('payments', 'payment_account_id')) {
                $paymentData['payment_account_id'] = (int) $request->input('payment_account_id');
            } elseif (\Schema::hasColumn('payments', 'payment_method_id')) {
                $paymentData['payment_method_id'] = (int) $request->input('payment_account_id');
            }

            Payments::create($paymentData);

            $cartItemIds = session('cartItemIds', []);
            if (!empty($cartItemIds)) {
                DB::table('carts')->whereIn('id', $cartItemIds)->delete();
            }

            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'waiting',
                'description' => 'Pesanan telah dibuat oleh ' . Auth::user()->name,
            ]);

            DB::commit();

            $fromCart = session('from_cart');
            session()->forget(['address', 'orderItems', 'cartItemIds', 'from_cart']);

            if ($fromCart) {
                return redirect()->route('cart.index')->with('success', 'Checkout berhasil diproses!');
            }
            
            return redirect()->route('products.index')->with('success', 'Checkout berhasil diproses!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}