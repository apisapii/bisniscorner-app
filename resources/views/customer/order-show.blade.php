<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $order->order_number }} — Detail pesanan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-gray-100 antialiased text-gray-900">
    <div class="print:hidden shrink-0">
        <x-navbar />
    </div>

    <main class="flex-1 max-w-md mx-auto w-full p-4 pb-8">
        <a href="{{ route('customer.history') }}" class="text-sm font-semibold text-blue-600 mb-4 inline-block">← Riwayat</a>

        @if (session('success'))
            <div class="mb-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-semibold px-4 py-3 print:hidden">
                {{ session('success') }}
            </div>
        @endif
        @if (session('info'))
            <div class="mb-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm font-semibold px-4 py-3 print:hidden">
                {{ session('info') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
            <div class="@if($order->isPaid()) bg-blue-600 @else bg-amber-500 @endif text-white p-4">
                <p class="text-xs opacity-90">Nomor pesanan</p>
                <p class="text-2xl font-black tracking-wider">{{ $order->order_number }}</p>
                <p class="text-xs opacity-90 mt-2">{{ $order->created_at->format('d/m/Y H:i') }} WIB</p>
                <p class="text-sm font-bold mt-3">Pembayaran: {{ $order->paymentLabel() }}</p>
            </div>
            <div class="p-4 border-b border-gray-100">
                <p class="text-xs text-gray-500">Pemesan</p>
                <p class="font-semibold text-gray-900">{{ $order->customer_name }}</p>
                <p class="text-sm text-gray-600">{{ $order->customer_email }}</p>
            </div>
        </div>

        @if(!$order->isPaid())
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-4 print:hidden">
                <p class="text-sm text-amber-900 font-semibold mb-3">Pesanan belum lunas. Setelah Xendit terhubung, pembayaran akan terupdate otomatis. Untuk uji coba:</p>
                <form action="{{ route('checkout.dummy-pay', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-xl transition">
                        Simulasikan pembayaran sukses (dummy)
                    </button>
                </form>
            </div>
        @endif

        <h2 class="font-bold text-gray-800 mb-2">Rincian item</h2>
        <ul class="space-y-3 mb-6">
            @foreach($order->items as $item)
                <li class="bg-white rounded-xl border border-gray-100 p-3 flex justify-between gap-3 text-sm">
                    <div>
                        <p class="font-bold text-gray-900">{{ $item->quantity }}× {{ $item->product?->name ?? 'Produk tidak tersedia' }}</p>
                        <p class="text-xs text-blue-600 font-semibold mt-0.5">{{ $item->product?->umkm?->name ?? '—' }}</p>
                        <p class="text-xs text-gray-600 mt-1">
                            <span class="font-semibold text-gray-700">Barang:</span> {{ $item->deliveryLabel() }}
                        </p>
                    </div>
                    <p class="font-bold text-gray-800 shrink-0">
                        Rp {{ number_format($item->price_at_time * $item->quantity, 0, ',', '.') }}
                    </p>
                </li>
            @endforeach
        </ul>

        <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-6">
            <div class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal produk</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($order->subtotalAmount(), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Biaya layanan</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($order->service_fee_amount, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="flex justify-between items-center font-bold text-lg border-t pt-3 mt-3">
                <span>Total</span>
                <span class="@if($order->isPaid()) text-green-600 @else text-amber-600 @endif">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 print:hidden">
            <a href="{{ route('customer.orders.receipt', $order) }}"
               class="flex-1 text-center bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition">
                Buka halaman struk
            </a>
            <button type="button" onclick="window.print()"
                    class="flex-1 text-center bg-white border-2 border-gray-200 text-gray-800 font-bold py-3 rounded-xl hover:bg-gray-50 transition">
                Cetak halaman ini
            </button>
        </div>
    </main>

    <style>
        @media print {
            .print\:hidden { display: none !important; }
        }
    </style>

    <x-site-footer />
</body>
</html>
