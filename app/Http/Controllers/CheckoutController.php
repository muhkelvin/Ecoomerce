<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout dengan ringkasan cart.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        // Hitung total harga cart
        $total = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Proses checkout.
     *
     * Membuat Order, OrderItem, dan Payment dengan metode manual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Hitung total order
        $total = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);

        // Buat Order baru
        $order = Order::create([
            'user_id' => $user->id,
            'total'   => $total,
            'status'  => 'pending',
        ]);

        // Buat Order Items untuk setiap item di cart
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $cartItem->product->id,
                'quantity'   => $cartItem->quantity,
                'price'      => $cartItem->product->price,
            ]);
        }

        // (Opsional) Hapus item di keranjang setelah checkout
        $user->cartItems()->delete();

        // Buat data Payment dengan metode manual dan status awal pending
        Payment::create([
            'order_id'         => $order->id,
            'payment_method'   => 'manual',
            'payment_status'   => 'pending', // Status awal, nantinya akan diubah setelah upload bukti pembayaran
            'stripe_payment_id'=> null,
            'amount'           => $total,
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order berhasil dibuat. Silakan upload bukti pembayaran untuk konfirmasi.');
    }
}
