@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <h1 class="text-3xl font-serif font-bold">Detail Pesanan #{{ $order->id }}</h1>

        <!-- Ringkasan Order -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                    <p class="text-gray-600">
                        Status:
                        <span class="font-bold">
                        {{ ucfirst($order->status) }}
                    </span>
                    </p>
                </div>
                <div>
                    <p class="text-lg font-bold">Total: Rp {{ number_format($order->total, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Daftar Item Pesanan -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Daftar Produk</h2>
            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                    <div class="flex justify-between border-b border-gray-200 pb-2">
                        <div>
                            <h3 class="font-serif font-bold">{{ $item->product->name }}</h3>
                            <p class="text-gray-600">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="font-bold">Rp {{ number_format($item->price * $item->quantity, 2) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Detail Pembayaran -->
        @if($order->payment)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Detail Pembayaran</h2>
                <p class="text-gray-600">Metode Pembayaran: <span class="font-bold">{{ ucfirst($order->payment->payment_method) }}</span></p>
                <p class="text-gray-600">Status Pembayaran:
                    <span class="font-bold">
                {{ ucfirst($order->payment->payment_status) }}
            </span>
                </p>
                @if($order->payment->stripe_payment_id)
                    <p class="text-gray-600">ID Transaksi: <span class="font-bold">{{ $order->payment->stripe_payment_id }}</span></p>
                @endif

                <!-- Jika pembayaran manual dan bukti belum diupload atau sedang menunggu konfirmasi -->
                @if($order->payment->payment_method === 'manual' && !$order->payment->payment_proof)
                    <div class="mt-4 p-4 bg-yellow-100 border border-yellow-300 rounded">
                        <p class="text-yellow-700">Anda belum mengupload bukti pembayaran. Silakan upload bukti pembayaran agar proses konfirmasi dapat segera dilakukan oleh admin.</p>
                        <a href="{{ route('payment.upload.form', $order) }}"
                           class="inline-block mt-2 px-4 py-2 bg-[#001f3f] text-white rounded hover:bg-[#FFD700] transition-colors duration-300">
                            Upload Bukti Pembayaran
                        </a>
                    </div>
                @elseif($order->payment->payment_proof && $order->payment->payment_status == 'waiting confirmation')
                    <div class="mt-4 p-4 bg-blue-100 border border-blue-300 rounded">
                        <p class="text-blue-700">Bukti pembayaran telah dikirim dan sedang menunggu konfirmasi admin.</p>
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $order->payment->payment_proof) }}" alt="Bukti Pembayaran" class="max-w-xs rounded">
                        </div>
                    </div>
                @elseif($order->payment->payment_status == 'success')
                    <div class="mt-4 p-4 bg-[#e0f2fe] border border-[#b3e5fc] rounded">
                        <p class="text-[#0284c7]">Pembayaran telah dikonfirmasi. Terima kasih!</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
