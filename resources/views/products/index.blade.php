{{-- products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Search Form -->
        <div class="mb-12">
            <form action="{{ route('products.index') }}" method="GET" class="flex gap-4">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..."
                       class="w-full px-6 py-3 border-2 border-soft-beige rounded-xl focus:ring-2 focus:ring-dusty-rose focus:border-transparent placeholder-gray-300">
                <button type="submit"
                        class="px-8 py-3 bg-deep-navy text-off-white rounded-xl hover:bg-terracotta transition-colors font-medium">
                    Cari
                </button>
            </form>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse ($products as $product)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 over flow-hidden group">
                    <div class="group relative h-72 flex items-center justify-center rounded-xl overflow-hidden">
                        <!-- Gambar dari Unsplash -->
                        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://source.unsplash.com/random/2778x1284?Beach');">
                            <!-- Overlay untuk efek gelap -->
                            <div class="absolute inset-0 bg-black/20"></div>
                        </div>

                        <!-- Tombol Quick View -->
                        <button class="absolute bottom-4 right-4 bg-[#001f3f] text-white px-4 py-2 rounded hover:bg-[#FFD700] transition-colors opacity-0 group-hover:opacity-100">
                            Quick View
                        </button>
                    </div>


                    <!-- Product Info -->
                    <div class="p-6">
                        <h3 class="font-playfair text-xl text-deep-navy mb-2">{{ $product->name }}</h3>
                        <p class="text-slate-gray text-sm mb-4">{{ Str::limit($product->description, 60) }}</p>

                        <div class="flex items-center justify-between">
                            <span class="font-medium text-terracotta">Rp {{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('products.show', $product) }}"
                               class="px-6 py-3 bg-[#001f3f] text-white rounded hover:bg-[#FFD700] transition-colors duration-300">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-slate-gray text-lg">Produk tidak ditemukan</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>
@endsection
