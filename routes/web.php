<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini kita mendefinisikan route utama aplikasi e-commerce.
| Pastikan untuk menyesuaikan controller dan method sesuai dengan implementasi Anda.
|
*/

// Route untuk Login & Register (Guest Only)
Route::middleware('guest')->group(function () {
    // Tampilan Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // Proses Login
    Route::post('/login', [AuthController::class, 'login']);

    // Tampilan Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    // Proses Register
    Route::post('/register', [AuthController::class, 'register']);
});

// Route untuk Logout (hanya untuk user yang sudah login)
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Home & Product Listing
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Group route yang membutuhkan autentikasi user
Route::middleware('auth')->group(function () {

    // Route untuk pengelolaan Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Route untuk Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Route untuk Order (riwayat pesanan)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/payment/upload/{order}', [PaymentController::class, 'showUploadForm'])
        ->name('payment.upload.form');
    Route::post('/payment/upload/{order}', [PaymentController::class, 'uploadProof'])
        ->name('payment.upload.process');
});

// Route untuk Payment Webhook (contoh jika menggunakan Stripe webhook)
//Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
