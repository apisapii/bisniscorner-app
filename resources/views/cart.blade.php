<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased text-gray-900 pb-24">

    <div class="bg-white p-4 shadow-sm flex items-center sticky top-0 z-50">
        <a href="{{ route('catalog') }}" class="text-gray-500 mr-4 font-bold text-xl">←</a>
        <h1 class="font-bold text-lg">Keranjang Saya</h1>
    </div>

    <main class="max-w-md mx-auto p-4 mt-2">
        @if(count($cart) > 0)
            <div class="space-y-4">
                @foreach($cart as $id => $details)
                <div class="bg-white p-4 rounded-xl shadow-sm flex justify-between items-center">
                    <div>
                        <span class="text-xs text-blue-500 font-semibold">{{ $details['umkm'] }}</span>
                        <h3 class="font-bold text-gray-800">{{ $details['name'] }}</h3>
                        <p class="text-orange-500 font-semibold">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                    </div>
                    <div class="font-bold text-gray-700 bg-gray-100 px-3 py-1 rounded-lg">
                        x{{ $details['quantity'] }}
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center mt-10 text-gray-500">
                <p>Keranjang kamu masih kosong.</p>
                <a href="{{ route('catalog') }}" class="text-blue-500 font-bold mt-2 inline-block">Mulai Belanja</a>
            </div>
        @endif
    </main>

    @if(count($cart) > 0)
    <div class="fixed bottom-0 w-full max-w-md left-1/2 transform -translate-x-1/2 bg-white border-t p-4 flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
        <div>
            <p class="text-xs text-gray-500">Total Harga</p>
            <p class="font-bold text-xl text-orange-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('checkout.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-md transition text-center">
    Checkout
</a>
    </div>
    @endif

</body>
</html>