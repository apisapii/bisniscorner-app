<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@if($order->isPaid()) Pesanan lunas @else Menunggu pembayaran @endif</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-blue-600 antialiased text-gray-900">

    <div class="flex-1 flex flex-col items-center justify-center p-4 w-full min-h-0">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden text-center">
        @if (session('success'))
            <div class="bg-green-50 text-green-800 text-sm font-semibold px-4 py-3 border-b border-green-100">
                {{ session('success') }}
            </div>
        @endif
        @if (session('info'))
            <div class="bg-blue-50 text-blue-800 text-sm font-semibold px-4 py-3 border-b border-blue-100">
                {{ session('info') }}
            </div>
        @endif

        @if($order->isPaid())
            <div class="bg-green-500 p-6 text-white">
                <div class="text-5xl mb-2">🎉</div>
                <h1 class="text-2xl font-bold">Pembayaran lunas</h1>
                <p class="text-green-100 text-sm">Penjual akan menyiapkan pesananmu. Pantau status di riwayat.</p>
            </div>
        @else
            <div class="bg-amber-500 p-6 text-white">
                <div class="text-5xl mb-2">⏳</div>
                <h1 class="text-2xl font-bold">Menunggu pembayaran</h1>
                <p class="text-amber-100 text-sm">Selesaikan pembayaran (nanti via Xendit). Untuk sementara pakai tombol demo di bawah.</p>
            </div>
        @endif

        <div class="p-6 border-b border-dashed border-gray-300">
            <p class="text-gray-500 text-sm mb-1">Nomor Pesanan</p>
            <p class="text-3xl font-black text-gray-800 tracking-wider">{{ $order->order_number }}</p>
            <p class="text-xs text-gray-500 mt-2">Status bayar: <strong>{{ $order->paymentLabel() }}</strong></p>
        </div>

        <div class="p-6 text-left bg-gray-50">
            <p class="text-sm text-gray-600 mb-4">Atas Nama: <strong>{{ $order->customer_name }}</strong></p>

            <div class="space-y-3 mb-4">
                @foreach($order->items as $item)
                <div class="flex justify-between items-start text-sm">
                    <div>
                        <span class="font-bold text-gray-800">{{ $item->quantity }}x {{ $item->product?->name ?? 'Produk' }}</span>
                        <br>
                        <span class="text-xs text-blue-500 font-semibold">{{ $item->product?->umkm?->name ?? '—' }}</span>
                    </div>
                    <span class="font-semibold text-gray-700">Rp {{ number_format($item->price_at_time * $item->quantity, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="space-y-1 text-sm border-t pt-4">
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
                <span>@if($order->isPaid()) Total (lunas) @else Total tagihan @endif</span>
                <span class="@if($order->isPaid()) text-green-600 @else text-amber-600 @endif">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        @if(!$order->isPaid())
            <div class="p-6 text-left bg-amber-50 border-t border-amber-100">
                <p class="text-xs text-amber-900 font-semibold mb-3">Demo: simulasi sukses pembayaran (ganti webhook Xendit nanti).</p>
                <form action="{{ route('checkout.dummy-pay', $order) }}" method="POST" class="space-y-3">
                    @csrf
                    @guest
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Konfirmasi email checkout</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', $order->customer_email) }}" required
                                   class="w-full border-gray-300 rounded-lg text-sm p-2.5">
                            @error('customer_email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endguest
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-xl transition">
                        Simulasikan pembayaran sukses
                    </button>
                </form>
            </div>
        @endif

        <div class="p-6 bg-white space-y-2">
            @auth
                @if(Auth::user()->role === 'customer')
                    <a href="{{ route('customer.history') }}" class="block w-full bg-gray-100 text-gray-800 font-bold py-3 rounded-xl hover:bg-gray-200 transition">
                        Lihat riwayat pesanan
                    </a>
                @endif
            @endauth
            <a href="{{ route('catalog') }}" class="block w-full bg-blue-100 text-blue-700 font-bold py-3 rounded-xl hover:bg-blue-200 transition">
                Kembali ke Katalog Belanja
            </a>
        </div>
    </div>
    </div>

    <x-site-footer />

</body>
</html>
