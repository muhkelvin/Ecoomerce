<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk dengan opsi pencarian.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Ambil query pencarian (misalnya: ?q=keyword)
        $searchQuery = $request->input('q');

        // Jika ada query pencarian, filter produk berdasarkan nama atau deskripsi,
        // jika tidak, tampilkan semua produk dengan pagination.
        $products = Product::when($searchQuery, function($query, $searchQuery) {
            return $query->where('name', 'like', "%{$searchQuery}%")
                ->orWhere('description', 'like', "%{$searchQuery}%");
        })->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Tampilkan detail dari produk tertentu.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
