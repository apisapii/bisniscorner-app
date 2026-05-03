<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::query()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere(function ($q2) use ($user) {
                        $q2->whereNull('user_id')
                            ->whereRaw('LOWER(customer_email) = ?', [strtolower($user->email)]);
                    });
            })
            ->withCount('items')
            ->with('items')
            ->orderByDesc('created_at')
            ->get();

        return view('customer.history', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        $this->ensureCustomerOwnsOrder($request->user(), $order);

        $order->load('items.product.umkm');

        return view('customer.order-show', compact('order'));
    }

    public function receipt(Request $request, Order $order)
    {
        $this->ensureCustomerOwnsOrder($request->user(), $order);

        $order->load('items.product.umkm');

        return view('customer.receipt', compact('order'));
    }

    private function ensureCustomerOwnsOrder(User $user, Order $order): void
    {
        if ((int) $order->user_id === (int) $user->id) {
            return;
        }

        if ($order->user_id === null && strcasecmp((string) $order->customer_email, (string) $user->email) === 0) {
            return;
        }

        abort(403);
    }
}
