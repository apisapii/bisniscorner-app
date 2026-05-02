<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua rincian pesanan yang masuk KHUSUS untuk UMKM yang sedang login
        $incomingOrders = OrderItem::with(['order', 'product'])
                            ->where('umkm_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('dashboard', compact('incomingOrders'));
    }
}