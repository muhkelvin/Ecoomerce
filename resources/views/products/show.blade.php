{{-- products/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="grid md:grid-cols-2 gap-12 p-12">
                <!-- Image Gallery -->
                <div class="space-y-6">
                    <div class="h-96 bg-soft-beige/30 rounded-xl flex items-center justify-center overflow-hidden">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-3/4 h-3/4 bg-soft-beige rounded-xl animate-pulse"></div>
                        @endif
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        @if($product->image)
                            <div class="h-24 rounded-lg overflow-hidden">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </div>
                            @for($i = 0; $i < 2; $i++)
                                <div class="h-24 bg-soft-beige/20 rounded-lg"></div>
                            @endfor
                        @else
                            @for($i = 0; $i < 3; $i++)
                                <div class="h-24 bg-soft-beige/20 rounded-lg"></div>
                            @endfor
                        @endif
                    </div>
                </div>

                <!-- Product Details -->
                <div>
                    <h1 class="font-playfair text-3xl text-deep-navy mb-4">{{ $product->name }}</h1>
                    <div class="flex items-center space-x-4 mb-6">
                        <span class="text-2xl font-medium text-terracotta">Rp {{ number_format($product->price, 2) }}</span>
                        <span class="text-slate-gray">| Stok: {{ $product->inventory }}</span>
                    </div>

                    <p class="text-slate-gray leading-relaxed mb-8">{{ $product->description }}</p>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.store', $product) }}" method="POST">
                        @csrf
                        <div class="flex gap-4">
                            <div class="relative">
                                <input type="number" name="quantity" value="1" min="1"
                                       class="w-32 px-4 py-3 border-2 border-soft-beige rounded-xl appearance-none">
                            </div>
                            <button type="submit"
                                    class="px-6 py-3 bg-[#001f3f] text-white rounded hover:bg-[#FFD700] transition-colors duration-300 font-medium">
                                + Tambah ke Keranjang
                            </button>
                        </div>
                    </form>

                    <!-- Product Specs -->
                    <div class="mt-8 pt-6 border-t border-soft-beige/30">
                        <h4 class="font-playfair text-lg text-deep-navy mb-4">Spesifikasi</h4>
                        <div class="grid grid-cols-2 gap-4 text-slate-gray">
                            <div><span class="font-medium">Kategori:</span> Fashion</div>
                            <div><span class="font-medium">Material:</span> Katun Premium</div>
                            <div><span class="font-medium">Berat:</span> 450 gram</div>
                            <div><span class="font-medium">SKU:</span> {{ $product->sku ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
