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

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $userId = Auth::id();
        $productId = (int) $data['product_id'];
        $qty = (int) ($data['qty'] ?? 1);

        try {
            DB::beginTransaction();

            $product = Products::select('id', 'name', 'price', 'stock')->findOrFail($productId);

            if (is_null($product->stock) || $product->stock == 0) {
                DB::rollBack();
                return redirect()->back()->with('error', "Maaf, stok produk '{$product->name}' habis. Produk tidak dapat ditambahkan ke keranjang.");
            }

            $existing = Carts::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                $newQty = $existing->quantity + $qty;

                if ($newQty > $product->stock) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Maaf, stok produk '{$product->name}' tidak mencukupi. Stok tersedia {$product->stock}, di keranjang sudah ada {$existing->quantity}, dan kuantitas yang ingin ditambah adalah {$qty}.");
                }

                // Update quantity
                $existing->update([
                    'quantity' => $newQty,
                    'price' => (int) $product->price,
                ]);
            } else {
                if ($qty > $product->stock) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Maaf, stok produk '{$product->name}' tidak mencukupi. Stok tersedia: {$product->stock}, diminta: {$qty}.");
                }


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

    public function checkout(Request $request)
    {
        $action = $request->input('action');
        $selectedItems = $request->input('selected_items', []);
        $quantities = $request->input('quantities', []);
        $prices = $request->input('prices', []);

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
                    return [
                        'product_id' => $item->product_id,
                        'name' => $item->product->name,
                        'price' => (int) $item->product->price,
                        'quantity' => isset($quantities[$item->id]) ? (int) $quantities[$item->id] : (int) $item->quantity,
                        'cart_id' => $item->id,
                    ];
                })->toArray();

            session([
                'address' => $address,
                'orderItems' => $orderItems,
                'cartItemIds' => $selectedItems,
                'from_cart' => true
            ]);

            return redirect()->route('checkout.cart');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

