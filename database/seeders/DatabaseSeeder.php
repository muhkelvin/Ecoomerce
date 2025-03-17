<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Membuat user default untuk testing
        $user = User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Membuat 20 produk secara otomatis
        $products = Product::factory(20)->create();

        // Menambahkan beberapa produk ke dalam keranjang user untuk testing
        foreach ($products->take(5) as $product) {
            CartItem::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
                'quantity'   => rand(1, 3),
            ]);
        }

        // Contoh pembuatan order dari cart items
        $orderTotal = 0;
        $orderItems = [];
        foreach ($user->cartItems as $cartItem) {
            $orderTotal += $cartItem->product->price * $cartItem->quantity;
            $orderItems[] = [
                'product_id' => $cartItem->product->id,
                'quantity'   => $cartItem->quantity,
                'price'      => $cartItem->product->price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($orderItems)) {
            $order = Order::create([
                'user_id' => $user->id,
                'total'   => $orderTotal,
                'status'  => 'pending',
            ]);

            // Simpan setiap item order
            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            // Simulasi data pembayaran menggunakan Stripe (dummy)
            Payment::create([
                'order_id'         => $order->id,
                'payment_method'   => 'stripe',
                'payment_status'   => 'succeeded',
                'stripe_payment_id'=> 'pi_' . uniqid(),
                'amount'           => $orderTotal,
            ]);
        }
    }
}
