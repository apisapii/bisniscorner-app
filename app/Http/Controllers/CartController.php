<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart', compact('cart', 'total'));
    }

    // Memasukkan barang ke keranjang
    public function add(Request $request, $id)
    {
        $product = Product::with('umkm')->findOrFail($id);
        $cart = session()->get('cart', []);

        // Kalau barang sudah ada di keranjang, tambah jumlahnya (quantity)
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Kalau belum ada, masukkan sebagai barang baru
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "umkm" => $product->umkm->name
            ];
        }

        session()->put('cart', $cart);
        
        // Kembali ke halaman sebelumnya (katalog)
        return redirect()->back();
    }
}