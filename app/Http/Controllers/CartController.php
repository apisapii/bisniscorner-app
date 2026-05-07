<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function calculateTotal(array $cart): int
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += ((int) $item['price']) * ((int) $item['quantity']);
        }

        return $total;
    }

    // Menampilkan halaman keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = $this->calculateTotal($cart);
        
        return view('cart', compact('cart', 'total'));
    }

    // Memasukkan barang ke keranjang
    public function add(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $product = Product::with('umkm')->findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "umkm" => $product->umkm->name
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Keranjang diperbarui.');
    }

    public function increase($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function decrease($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity']--;
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }
}