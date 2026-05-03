<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk {{ $order->order_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            @page { margin: 12mm; }
        }
    </style>
</head>
<body class="bg-gray-200 antialiased text-gray-900 min-h-screen">
    <div class="no-print max-w-md mx-auto p-4 flex gap-2 sticky top-0 z-10 bg-gray-200/95 backdrop-blur-sm pb-2">
        <a href="{{ route('customer.orders.show', $order) }}"
           class="flex-1 text-center text-sm font-bold bg-white border border-gray-200 text-gray-800 py-3 rounded-xl shadow-sm">
            ← Detail
        </a>
        <button type="button" id="btn-print"
                class="flex-1 text-sm font-bold bg-blue-600 text-white py-3 rounded-xl shadow-md hover:bg-blue-700">
            Cetak struk
        </button>
    </div>

    @if(!$order->isPaid())
        <div class="no-print max-w-sm mx-auto mb-2 px-2">
            <div class="rounded-lg border border-amber-300 bg-amber-50 text-amber-900 text-xs font-semibold px-3 py-2 text-center">
                Belum lunas — struk ini hanya ringkasan pesanan. Setelah bayar, status akan diperbarui (Xendit / dummy).
            </div>
        </div>
    @endif

    <div id="struk" class="max-w-sm mx-auto bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200 my-2 mb-8">
        <div class="text-center border-b-2 border-dashed border-gray-300 px-4 py-4">
            <p class="text-lg font-black tracking-tight">{{ config('app.name', 'Bazar PCR') }}</p>
            <p class="text-xs text-gray-500 mt-1">Struk pemesanan / pengambilan</p>
        </div>
        <div class="px-4 py-3 text-sm border-b border-dashed border-gray-200">
            <div class="flex justify-between gap-2 mb-1">
                <span class="text-gray-500">No. pesanan</span>
                <span class="font-mono font-bold">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between gap-2 mb-1">
                <span class="text-gray-500">Tanggal</span>
                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between gap-2 mb-1">
                <span class="text-gray-500">Pembayaran</span>
                <span class="font-semibold">{{ $order->paymentLabel() }}</span>
            </div>
            @if($order->isPaid() && $order->payment_paid_at)
                <div class="flex justify-between gap-2 text-xs text-gray-500">
                    <span>Dibayar</span>
                    <span>{{ $order->payment_paid_at->format('d/m/Y H:i') }}</span>
                </div>
            @endif
        </div>
        <div class="px-4 py-3 text-sm border-b border-dashed border-gray-200">
            <p class="text-gray-500 text-xs uppercase mb-1">Pemesan</p>
            <p class="font-bold">{{ $order->customer_name }}</p>
            <p class="text-gray-600 text-xs break-all">{{ $order->customer_email }}</p>
        </div>
        <div class="px-4 py-3">
            <p class="text-xs text-gray-500 uppercase mb-2">Item</p>
            <ul class="space-y-3 text-sm">
                @foreach($order->items as $item)
                    <li class="flex justify-between gap-3 border-b border-gray-100 pb-2 last:border-0 last:pb-0">
                        <div>
                            <span class="font-semibold">{{ $item->quantity }}× {{ $item->product?->name ?? 'Produk tidak tersedia' }}</span>
                            <span class="block text-xs text-gray-500">{{ $item->product?->umkm?->name ?? '—' }}</span>
                            <span class="block text-[10px] text-blue-600 font-semibold mt-0.5">{{ $item->deliveryLabel() }}</span>
                        </div>
                        <span class="font-mono shrink-0">Rp {{ number_format($item->price_at_time * $item->quantity, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="px-4 py-4 bg-gray-50 border-t-2 border-dashed border-gray-300 flex justify-between items-center">
            <span class="font-bold">TOTAL</span>
            <span class="text-xl font-black @if($order->isPaid()) text-green-700 @else text-amber-700 @endif">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="px-4 py-4 text-center text-xs text-gray-500 border-t border-gray-100">
            <p>Tunjukkan struk ini ke stand saat mengambil pesanan (setelah lunas & barang siap).</p>
            <p class="mt-1">Terima kasih berbelanja 🙏</p>
        </div>
    </div>

    <script>
        document.getElementById('btn-print')?.addEventListener('click', function () {
            window.print();
        });
    </script>
</body>
</html>
