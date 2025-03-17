@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-serif font-bold mb-6">Keranjang Belanja Anda</h1>

        @if($cartItems->isEmpty())
            <div class="text-center text-gray-500">
                <p>Keranjang Anda masih kosong.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block px-6 py-3 bg-[var(--navy-blue)] text-white rounded hover:bg-[var(--gold)] transition-colors duration-300">Mulai Belanja</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($cartItems as $item)
                    <div class="flex flex-col md:flex-row items-center bg-white rounded-lg shadow p-4">
                        <!-- Placeholder Image -->
                        <div class="w-full md:w-1/4 h-40 bg-gray-100 flex items-center justify-center rounded-lg">
                            <span class="text-gray-400">Image</span>
                        </div>

                        <!-- Product Info -->
                        <div class="w-full md:w-2/4 mt-4 md:mt-0 md:pl-6">
                            <h2 class="text-xl font-serif font-bold">{{ $item->product->name }}</h2>
                            <p class="mt-2 text-gray-600">{{ Str::limit($item->product->description, 80) }}</p>
                            <div class="mt-2">
                                <span class="font-bold text-lg">Rp {{ number_format($item->product->price, 2) }}</span>
                            </div>
                        </div>

                        <!-- Quantity & Subtotal -->
                        <div class="w-full md:w-1/4 mt-4 md:mt-0 md:pl-6 text-center">
                            <p class="text-gray-700">Quantity: <span class="font-bold">{{ $item->quantity }}</span></p>
                            <p class="mt-2 text-gray-700">Subtotal:</p>
                            <p class="font-bold text-lg">Rp {{ number_format($item->product->price * $item->quantity, 2) }}</p>
                            <!-- Hapus item dari keranjang -->
                            <form action="{{ route('cart.destroy', $item) }}" method="POST" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors duration-300">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Perbaikan: Deklarasi $total sebelum digunakan -->
            @php
                $total = $cartItems->reduce(function($carry, $item) {
                    return $carry + ($item->product->price * $item->quantity);
                }, 0);
            @endphp

                <!-- Ringkasan Total & Tombol Checkout -->
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-bold">Total Belanja:</span>
                    <span class="text-2xl font-bold">Rp {{ number_format($total, 2) }}</span>
                </div>
                <div class="mt-6 text-right">
                    <a href="{{ route('checkout.index') }}" class="px-6 py-3 bg-[#001f3f] text-white rounded hover:bg-[#FFD700] transition-colors duration-300">
                        Checkout
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
