{{-- checkout/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="font-playfair text-3xl text-deep-navy mb-8">Checkout</h1>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-8">
                <h2 class="font-playfair text-xl text-deep-navy mb-6">Ringkasan Pesanan</h2>

                <div class="space-y-6">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between items-start pb-4 border-b border-soft-beige/30">
                            <div>
                                <h3 class="font-medium text-deep-navy">{{ $item->product->name }}</h3>
                                <p class="text-slate-gray text-sm mt-1">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-medium text-terracotta">Rp {{ number_format($item->product->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach

                    <div class="flex justify-between items-center pt-4">
                        <span class="font-playfair text-xl text-deep-navy">Total Pembayaran</span>
                        <span class="font-playfair text-2xl text-terracotta">Rp {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white rounded-xl shadow-sm p-8 h-fit sticky top-6">
                <h2 class="font-playfair text-xl text-deep-navy mb-6">Metode Pembayaran</h2>

                <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                    @csrf
                    <!-- Payment Method Selection -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-4 border-2 border-soft-beige rounded-xl">
                            <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" checked>
                            <label for="bank_transfer" class="text-slate-gray">Bank Transfer</label>
                        </div>
                        <div class="flex items-center gap-3 p-4 border-2 border-soft-beige rounded-xl">
                            <input type="radio" name="payment_method" id="e-wallet" value="e-wallet">
                            <label for="e-wallet" class="text-slate-gray">E-Wallet</label>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-[#001f3f] text-white rounded hover:bg-[#FFD700] transition-colors font-medium">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
