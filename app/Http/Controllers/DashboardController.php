<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $umkmId = Auth::user()->umkm_id;

        $validated = $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $tanggalMulai = isset($validated['tanggal_mulai'])
            ? Carbon::parse($validated['tanggal_mulai'])->startOfDay()
            : null;
        $tanggalSelesai = isset($validated['tanggal_selesai'])
            ? Carbon::parse($validated['tanggal_selesai'])->endOfDay()
            : null;

        // Daftar baris pesanan untuk UMKM — ikut rentang tanggal sama GMV kalau ada filter
        $incomingQuery = OrderItem::with(['order', 'product'])
            ->where('umkm_id', $umkmId);

        $ordersDateFilterActive = $tanggalMulai !== null || $tanggalSelesai !== null;

        if ($ordersDateFilterActive) {
            $incomingQuery->whereHas('order', function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->where(function ($outer) use ($tanggalMulai, $tanggalSelesai) {
                    // Lunas: sama GMV → patokan payment_paid_at
                    $outer->where(function ($paid) use ($tanggalMulai, $tanggalSelesai) {
                        $paid->where('payment_status', Order::PAYMENT_PAID)
                            ->whereNotNull('payment_paid_at');
                        if ($tanggalMulai !== null) {
                            $paid->where('payment_paid_at', '>=', $tanggalMulai);
                        }
                        if ($tanggalSelesai !== null) {
                            $paid->where('payment_paid_at', '<=', $tanggalSelesai);
                        }
                    })->orWhere(function ($pending) use ($tanggalMulai, $tanggalSelesai) {
                        // Belum lunas: pakai tanggal pesanan dibuat agar tetap bisa difollow-up dalam rentang
                        $pending->where('payment_status', '!=', Order::PAYMENT_PAID);
                        if ($tanggalMulai !== null) {
                            $pending->where('created_at', '>=', $tanggalMulai);
                        }
                        if ($tanggalSelesai !== null) {
                            $pending->where('created_at', '<=', $tanggalSelesai);
                        }
                    });
                });
            });
        }

        $incomingOrders = $incomingQuery->orderByDesc('created_at')->get();

        $paidItemsBase = OrderItem::query()
            ->where('umkm_id', $umkmId)
            ->whereHas('order', function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->where('payment_status', Order::PAYMENT_PAID)
                    ->whereNotNull('payment_paid_at');
                if ($tanggalMulai) {
                    $q->where('payment_paid_at', '>=', $tanggalMulai);
                }
                if ($tanggalSelesai) {
                    $q->where('payment_paid_at', '<=', $tanggalSelesai);
                }
            });

        $financials = [
            'gmv' => (int) (clone $paidItemsBase)->sum(DB::raw('price_at_time * quantity')),
            'paid_orders_count' => (int) (clone $paidItemsBase)->distinct('order_id')->count('order_id'),
        ];

        $topProduct = (clone $paidItemsBase)
            ->select('product_id', DB::raw('SUM(quantity) as sold_qty'))
            ->with('product:id,name')
            ->groupBy('product_id')
            ->orderByDesc('sold_qty')
            ->first();

        $byDayAgg = [];
        OrderItem::query()
            ->where('umkm_id', $umkmId)
            ->whereHas('order', function ($q) {
                $q->where('payment_status', Order::PAYMENT_PAID)
                    ->whereNotNull('payment_paid_at')
                    ->where('payment_paid_at', '>=', Carbon::now()->subDays(365)->startOfDay());
            })
            ->with(['order:id,payment_status,payment_paid_at'])
            ->select(['id', 'order_id', 'price_at_time', 'quantity'])
            ->orderBy('id')
            ->chunk(200, function ($chunk) use (&$byDayAgg) {
                foreach ($chunk as $row) {
                    $paidAt = $row->order?->payment_paid_at;
                    if (! $paidAt) {
                        continue;
                    }
                    $key = Carbon::parse($paidAt)->toDateString();
                    if (! isset($byDayAgg[$key])) {
                        $byDayAgg[$key] = ['gmv' => 0, 'orders' => collect()];
                    }
                    $byDayAgg[$key]['gmv'] += (int) $row->price_at_time * (int) $row->quantity;
                    $byDayAgg[$key]['orders']->push((int) $row->order_id);
                }
            });

        $dailyGmvs = collect();
        foreach ($byDayAgg as $day => $row) {
            $dailyGmvs->push([
                'date' => $day,
                'gmv' => $row['gmv'],
                'paid_orders_count' => $row['orders']->unique()->count(),
            ]);
        }
        $dailyGmvs = $dailyGmvs->sortByDesc('date')->values()->take(90);

        $filterHint = '';
        if ($tanggalMulai && $tanggalSelesai) {
            $filterHint = $tanggalMulai->toDateString().' — '.$tanggalSelesai->toDateString();
        } elseif ($tanggalMulai) {
            $filterHint = 'Mulai '.$tanggalMulai->toDateString();
        } elseif ($tanggalSelesai) {
            $filterHint = 'Sampai '.$tanggalSelesai->toDateString();
        }

        return view('dashboard', [
            'incomingOrders' => $incomingOrders,
            'financials' => $financials,
            'topProduct' => $topProduct,
            'tanggalMulai' => $tanggalMulai?->toDateString(),
            'tanggalSelesai' => $tanggalSelesai?->toDateString(),
            'filterHint' => $filterHint,
            'dailyGmvs' => $dailyGmvs,
            'ordersDateFilterActive' => $ordersDateFilterActive,
        ]);
    }
}