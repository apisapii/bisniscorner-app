<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat pesanan — Bazar PCR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased text-gray-900">
    <x-navbar />

    <main class="max-w-md mx-auto p-4 pb-24">
        <h1 class="text-xl font-bold text-gray-800 mb-1">Riwayat pesanan</h1>
        <p class="text-sm text-gray-500 mb-6">Status pembayaran (nanti Xendit) & kesiapan barang dari penjual.</p>

        @forelse($orders as $order)
            @php
                $itemTotal = $order->items->count();
                $readyCount = $order->items->filter(fn ($i) => in_array($i->delivery_status, [\App\Models\OrderItem::DELIVERY_READY, \App\Models\OrderItem::DELIVERY_PICKED_UP], true))->count();
            @endphp
            <article class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-4">
                <div class="flex justify-between items-start gap-2 mb-2">
                    <div>
                        <p class="text-xs text-gray-500">No. pesanan</p>
                        <p class="font-black text-gray-900 tracking-wide">{{ $order->order_number }}</p>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg
                        @if($order->isPaid()) bg-green-100 text-green-700
                        @elseif($order->payment_status === \App\Models\Order::PAYMENT_PENDING) bg-amber-100 text-amber-900
                        @elseif($order->payment_status === \App\Models\Order::PAYMENT_FAILED) bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-600
                        @endif">
                        {{ $order->paymentLabel() }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mb-1">{{ $order->created_at->format('d/m/Y H:i') }} WIB</p>
                <p class="text-sm text-gray-600 mb-1">
                    {{ $order->items_count }} jenis item ·
                    <span class="font-bold text-gray-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </p>
                @if($order->isPaid() && $itemTotal > 0)
                    <p class="text-xs font-semibold text-blue-700 mb-3">
                        Barang: {{ $readyCount }}/{{ $itemTotal }} siap diambil atau selesai
                    </p>
                @elseif(!$order->isPaid())
                    <p class="text-xs text-amber-700 font-medium mb-3">Selesaikan pembayaran agar penjual bisa menyiapkan pesanan.</p>
                @endif
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('customer.orders.show', $order) }}"
                       class="inline-flex items-center justify-center text-sm font-bold bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition">
                        Detail
                    </a>
                    <a href="{{ route('customer.orders.receipt', $order) }}"
                       class="inline-flex items-center justify-center text-sm font-bold bg-white border-2 border-blue-600 text-blue-600 px-4 py-2 rounded-xl hover:bg-blue-50 transition">
                        Cetak struk
                    </a>
                </div>
            </article>
        @empty
            <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-10 text-center">
                <span class="text-4xl block mb-2">🛒</span>
                <p class="text-gray-600 text-sm font-medium mb-1">Belum ada pesanan</p>
                <p class="text-gray-400 text-xs mb-4">Checkout sambil login supaya riwayat tersimpan ke akunmu.</p>
                <a href="{{ route('catalog') }}" class="text-sm font-bold text-blue-600 hover:underline">Belanja di katalog</a>
            </div>
        @endforelse

        <a href="{{ route('catalog') }}" class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:text-blue-800">
            ← Kembali ke katalog
        </a>
    </main>
</body>
</html>
