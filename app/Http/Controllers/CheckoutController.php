<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Kalau keranjang kosong, tendang balik ke halaman depan
        if(empty($cart)) {
            return redirect()->route('catalog');
        }

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('cart', 'total'));
    }
        public function process(Request $request)
        {
        // 1. Validasi Input
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
        ]);

        $cart = session()->get('cart', []);
        if(empty($cart)) {
            return redirect()->route('catalog');
        }

        // Hitung total lagi buat jaga-jaga
        $totalAmount = 0;
        foreach($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // 2. Simpan ke tabel 'orders'
        $order = Order::create([
            'order_number' => 'BZR-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'total_amount' => $totalAmount,
            'status' => 'paid', // Kita anggap langsung lunas (Simulasi)
        ]);

        // 3. Simpan rincian ke tabel 'order_items'
        foreach($cart as $id => $details) {
            // Ambil umkm_id dari produk asli di database
            $product = \App\Models\Product::find($id);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'umkm_id' => $product->umkm_id,
                'quantity' => $details['quantity'],
                'price_at_time' => $details['price'],
                'delivery_status' => 'pending'
            ]);
        }

        // 4. Kosongkan Keranjang
        session()->forget('cart');

        // 5. Arahkan ke halaman Sukses (Struk)
        return redirect()->route('checkout.success', $order->id);
    }
    public function success($id)
    {
        // Cari data pesanan berdasarkan ID, tarik juga data produk dan UMKM-nya
        $order = Order::with('items.product.umkm')->findOrFail($id);
        
        return view('checkout-success', compact('order'));
    }
    }