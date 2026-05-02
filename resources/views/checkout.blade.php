<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout Pesanan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased text-gray-900">

    <div class="bg-white p-4 shadow-sm flex items-center sticky top-0 z-50">
        <a href="{{ route('cart.index') }}" class="text-gray-500 mr-4 font-bold text-xl">←</a>
        <h1 class="font-bold text-lg">Detail Pesanan</h1>
    </div>

    <main class="max-w-md mx-auto p-4 mt-2 mb-24">
        <div class="bg-white p-5 rounded-xl shadow-sm mb-6">
            <h2 class="font-bold text-gray-800 mb-4">Informasi Pengambilan</h2>
            
            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="customer_name" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" placeholder="Contoh: Hafiz Ramadhan">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email (Untuk Struk)</label>
                    <input type="email" name="customer_email" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" placeholder="Contoh: hafiz@email.com">
                </div>
            </form>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm">
            <h2 class="font-bold text-gray-800 mb-3">Ringkasan Belanja</h2>
            <div class="space-y-2 mb-3 border-b pb-3">
                @foreach($cart as $id => $details)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $details['quantity'] }}x {{ $details['name'] }}</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            <div class="flex justify-between items-center font-bold text-lg">
                <span>Total Tagihan</span>
                <span class="text-orange-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
    </main>

    <div class="fixed bottom-0 w-full max-w-md left-1/2 transform -translate-x-1/2 bg-white border-t p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
        <button type="submit" form="checkout-form" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold shadow-md transition text-center">
            Lanjut Bayar 💳
        </button>
    </div>

</body>
</html>