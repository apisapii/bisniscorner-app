<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderItemFulfillmentController extends Controller
{
    public function update(Request $request, OrderItem $orderItem)
    {
        $user = Auth::user();
        abort_unless($user->umkm_id && (int) $orderItem->umkm_id === (int) $user->umkm_id, 403);

        if (! $orderItem->order->isPaid()) {
            return redirect()->route('dashboard')->with('warning', 'Pesanan belum lunas. Tunggu pembayaran dari pelanggan.');
        }

        $request->validate([
            'delivery_status' => 'required|in:'.OrderItem::DELIVERY_PENDING.','.OrderItem::DELIVERY_READY.','.OrderItem::DELIVERY_PICKED_UP,
        ]);

        $orderItem->update([
            'delivery_status' => $request->delivery_status,
        ]);

        return redirect()->route('dashboard')->with('success', 'Status barang diperbarui.');
    }
}
