<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        // Ambil semua produk yang stoknya masih ada, sertakan data UMKM-nya
        $products = Product::with('umkm')->where('stock', '>', 0)->latest()->get();
        
        return view('welcome', compact('products'));
    }
}