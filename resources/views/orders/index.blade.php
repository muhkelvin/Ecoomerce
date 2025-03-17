{{-- orders/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="font-playfair text-3xl text-deep-navy mb-8">Riwayat Pesanan</h1>

        @if($orders->isEmpty())
            <div class="text-center py-20">
                <p class="text-slate-gray mb-4">Belum ada pesanan</p>
                <a href="{{ route('products.index') }}"
                   class="px-8 py-3 bg-deep-navy text-off-white rounded-xl hover:bg-terracotta transition-colors font-medium">
                    Jelajahi Koleksi
                </a>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <h2 class="font-playfair text-xl text-deep-navy mb-2">Order #{{ $order->id }}</h2>
                                <div class="flex items-center gap-4 text-sm">
                                    <p class="text-slate-gray">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    <span class="px-3 py-1 rounded-full bg-terracotta/10 text-terracotta text-sm">
                                    {{ ucfirst($order->status) }}
                                </span>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="font-playfair text-xl text-terracotta mb-2">Rp {{ number_format($order->total, 2) }}</p>
                                <a href="{{ route('orders.show', $order) }}"
                                   class="px-4 py-2 bg-deep-navy/10 text-deep-navy rounded-lg hover:bg-deep-navy hover:text-off-white transition-colors">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
