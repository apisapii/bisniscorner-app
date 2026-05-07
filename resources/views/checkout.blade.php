<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout Pesanan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-slate-50 antialiased text-gray-900">

    <div class="shrink-0 bg-white/95 backdrop-blur p-4 shadow-sm flex items-center sticky top-0 z-50 border-b border-gray-100">
        <a href="{{ route('cart.index') }}" class="text-gray-500 mr-4 font-bold text-xl">←</a>
        <h1 class="font-bold text-lg">Detail Pesanan</h1>
    </div>

    <main class="flex-1 max-w-md mx-auto w-full p-4 mt-2 pb-28">
        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
            <h2 class="font-bold text-gray-800 mb-4">Informasi Pengambilan</h2>

            @auth
                @if(Auth::user()->role === 'customer')
                    <p class="text-xs text-gray-500 mb-3">Pesanan ini akan tercatat di <strong>Riwayat</strong> akunmu.</p>
                @endif
            @endauth
            
            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="customer_name" required
                           value="{{ old('customer_name', Auth::user()?->name ?? '') }}"
                           class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" placeholder="Contoh: Hafiz Ramadhan">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email (Untuk Struk)</label>
                    <input type="email" name="customer_email" required
                           value="{{ old('customer_email', Auth::user()?->email ?? '') }}"
                           class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" placeholder="Contoh: hafiz@email.com">
                </div>
            </form>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="font-bold text-gray-800 mb-3">Ringkasan Belanja</h2>
            <div class="space-y-2 mb-3 border-b pb-3">
                @foreach($cart as $id => $details)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $details['quantity'] }}x {{ $details['name'] }}</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            <div class="space-y-2 border-t pt-3 mt-1">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal produk</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Biaya layanan aplikasi</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="flex justify-between items-center font-bold text-lg mt-3">
                <span>Total Tagihan</span>
                <span class="text-orange-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <p class="text-[11px] text-gray-500 mt-3">Termasuk biaya layanan aplikasi untuk kas panitia bazar.</p>
        </div>
    </main>

    <div class="fixed bottom-0 w-full max-w-md left-1/2 transform -translate-x-1/2 bg-white/95 backdrop-blur border-t p-4 shadow-[0_-10px_28px_rgba(0,0,0,0.12)]">
        <button type="submit" form="checkout-form" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold shadow-md transition text-center">
            Lanjut Bayar 💳
        </button>
    </div>

    <x-site-footer class="pb-28" />

</body>
</html>