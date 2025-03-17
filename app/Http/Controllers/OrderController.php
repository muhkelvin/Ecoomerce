<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar order milik user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail suatu order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        // Pastikan order tersebut milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('orderItems.product', 'payment');
        return view('orders.show', compact('order'));
    }
}
