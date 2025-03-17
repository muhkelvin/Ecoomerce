<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan daftar item yang ada di keranjang user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil data cart item milik user yang sudah login, beserta relasi produk
        $cartItems = Auth::user()->cartItems()->with('product')->get();

        return view('cart.index', compact('cartItems'));
    }

    /**
     * Menambahkan produk ke keranjang.
     *
     * Jika produk sudah ada, akan menambahkan quantity sesuai input.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product)
    {
        $user = Auth::user();

        // Validasi input (opsional, misal: quantity harus numeric minimal 1)
        $request->validate([
            'quantity' => 'sometimes|numeric|min:1',
        ]);

        // Cari apakah produk sudah ada di keranjang user
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Jika sudah ada, tambahkan jumlah quantity-nya
            $cartItem->quantity += $request->input('quantity', 1);
            $cartItem->save();
        } else {
            // Jika belum ada, buat record baru di cart_items
            CartItem::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
                'quantity'   => $request->input('quantity', 1),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Menghapus item dari keranjang.
     *
     * Pastikan item yang dihapus adalah milik user yang sedang login.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CartItem $cartItem)
    {
        // Pastikan hanya pemilik cart yang dapat menghapus item-nya
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
