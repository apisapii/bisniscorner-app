<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

        $userId = Auth::check() && Auth::user()->role === 'customer'
            ? Auth::id()
            : null;

        // 2. Simpan ke tabel 'orders' (pembayaran: nanti Xendit; sekarang dummy)
        $order = Order::create([
            'user_id' => $userId,
            'order_number' => 'BZR-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => Order::PAYMENT_PENDING,
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

    /**
     * Simulasi webhook pembayaran sukses (ganti dengan Xendit nanti).
     */
    public function dummyPay(Request $request, Order $order)
    {
        if ($order->payment_status !== Order::PAYMENT_PENDING) {
            $msg = 'Status pembayaran pesanan ini sudah bukan menunggu bayar.';

            return Auth::check() && Auth::user()->role === 'customer'
                ? redirect()->route('customer.orders.show', $order)->with('info', $msg)
                : redirect()->route('checkout.success', $order->id)->with('info', $msg);
        }

        if (Auth::check()) {
            if (Auth::user()->role !== 'customer') {
                abort(403);
            }
            $owns = (int) $order->user_id === (int) Auth::id()
                || strcasecmp((string) $order->customer_email, (string) Auth::user()->email) === 0;
            if (! $owns) {
                abort(403);
            }
        } else {
            $request->validate([
                'customer_email' => 'required|email',
            ]);
            if (strcasecmp($request->customer_email, (string) $order->customer_email) !== 0) {
                throw ValidationException::withMessages([
                    'customer_email' => 'Email harus sama dengan yang dipakai saat checkout.',
                ]);
            }
        }

        $order->update([
            'payment_status' => Order::PAYMENT_PAID,
            'payment_paid_at' => now(),
            'status' => 'paid',
        ]);

        $flash = 'Pembayaran (dummy) berhasil. Nanti diganti callback Xendit.';

        if (Auth::check() && Auth::user()->role === 'customer') {
            return redirect()->route('customer.orders.show', $order)->with('success', $flash);
        }

        return redirect()->route('checkout.success', $order->id)->with('success', $flash);
    }
}