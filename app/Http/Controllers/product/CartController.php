<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\Carts;
use App\Models\Products;
use App\Models\User;
use App\Models\UserAdresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Carts::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('pages.product.cart', compact('cartItems'));
    }

    /**
     * Tambah ke keranjang dari halaman detail/kartu produk.
     * - Baca qty dari form (default 1)
     * - Jika produk sudah ada di cart user → increment quantity
     * - Harga diambil dari DB (bukan input user)
     */
    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1'],   // ← qty opsional, default 1
            // 'price' DIHAPUS: jangan percaya harga dari client
        ]);

        $userId = Auth::id();
        $productId = (int) $data['product_id'];
        $qty = (int) ($data['qty'] ?? 1);

        try {
            DB::beginTransaction();

            // Ambil produk & harga dari DB
            $product = Products::select('id', 'name', 'price', 'stock')->findOrFail($productId);

            // (Opsional) batasi qty terhadap stok
            if (!is_null($product->stock) && $qty > $product->stock) {
                $qty = $product->stock;
            }

            // Cek apakah produk sudah ada di keranjang user
            $existing = Carts::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                // Tambahkan quantity
                $newQty = $existing->quantity + $qty;

                // (Opsional) batasi lagi terhadap stok
                if (!is_null($product->stock) && $newQty > $product->stock) {
                    $newQty = $product->stock;
                }

                $existing->update([
                    'quantity' => $newQty,
                    'price' => (int) $product->price, // simpan harga saat ini
                ]);
            } else {
                // Buat baris baru
                Carts::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'price' => (int) $product->price,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $cartItem = Carts::find($id);

        if (!$cartItem || $cartItem->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Item keranjang tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.');
        }

        try {
            DB::beginTransaction();

            $cartItem->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Item keranjang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Batch action dari halaman keranjang:
     * - delete
     * - update qty
     * - lanjut checkout
     */
    public function checkout(Request $request)
    {
        $action = $request->input('action');
        $selectedItems = $request->input('selected_items', []);
        $quantities = $request->input('quantities', []);
        $prices = $request->input('prices', []); // masih dipakai di view? biarkan saja walau kita override harga saat checkout

        if (empty($selectedItems)) {
            return redirect()->back()->with('error', 'Tidak ada item yang dipilih.');
        }

        switch ($action) {
            case 'delete':
                return $this->batchDelete($selectedItems);
            case 'update':
                return $this->updateQuantities($selectedItems, $quantities);
            case 'checkout':
                return $this->redirectToCheckout($selectedItems, $quantities);
            default:
                return redirect()->back()->with('error', 'Aksi tidak dikenal.');
        }
    }

    private function batchDelete(array $selectedItems)
    {
        try {
            DB::beginTransaction();

            Carts::where('user_id', Auth::id())
                ->whereIn('id', $selectedItems)
                ->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Item terpilih berhasil dihapus dari keranjang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function updateQuantities(array $selectedItems, array $quantities)
    {
        try {
            DB::beginTransaction();

            foreach ($selectedItems as $itemId) {
                if (isset($quantities[$itemId])) {
                    $quantity = max(1, (int) $quantities[$itemId]);

                    Carts::where('user_id', Auth::id())
                        ->where('id', $itemId)
                        ->update(['quantity' => $quantity]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Kuantitas item terpilih berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function redirectToCheckout(array $selectedItems, array $quantities)
    {
        $address = UserAdresses::where('user_id', Auth::id())->get();

        if ($address->isEmpty()) {
            return redirect()->route('user.profile.address')->with('error', 'Silakan tambahkan alamat terlebih dahulu di halaman profil sebelum melanjutkan ke checkout.');
        }

        try {
            $orderItems = Carts::with('product')
                ->where('user_id', Auth::id())
                ->whereIn('id', $selectedItems)
                ->get()
                ->map(function ($item) use ($quantities) {
                    // harga & nama diambil dari relasi product (bukan dari carts)
                    return [
                        'product_id' => $item->product_id,
                        'name' => $item->product->name,
                        'price' => (int) $item->product->price,
                        'quantity' => isset($quantities[$item->id]) ? (int) $quantities[$item->id] : (int) $item->quantity,
                        'cart_id' => $item->id,
                    ];
                })->toArray();

            // Simpan untuk dipakai di CheckoutController@checkoutCart
            session([
                'address' => $address,
                'orderItems' => $orderItems,
                'cartItemIds' => $selectedItems
            ]);

            return redirect()->route('checkout.cart');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

