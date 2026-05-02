<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Berhasil!</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-blue-600 antialiased text-gray-900 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden text-center">
        <div class="bg-green-500 p-6 text-white">
            <div class="text-5xl mb-2">🎉</div>
            <h1 class="text-2xl font-bold">Pesanan Berhasil!</h1>
            <p class="text-green-100 text-sm">Tunjukkan layar ini ke stand untuk mengambil pesananmu.</p>
        </div>

        <div class="p-6 border-b border-dashed border-gray-300">
            <p class="text-gray-500 text-sm mb-1">Nomor Pesanan</p>
            <p class="text-3xl font-black text-gray-800 tracking-wider">{{ $order->order_number }}</p>
        </div>

        <div class="p-6 text-left bg-gray-50">
            <p class="text-sm text-gray-600 mb-4">Atas Nama: <strong>{{ $order->customer_name }}</strong></p>
            
            <div class="space-y-3 mb-4">
                @foreach($order->items as $item)
                <div class="flex justify-between items-start text-sm">
                    <div>
                        <span class="font-bold text-gray-800">{{ $item->quantity }}x {{ $item->product->name }}</span>
                        <br>
                        <span class="text-xs text-blue-500 font-semibold">{{ $item->product->umkm->name }}</span>
                    </div>
                    <span class="font-semibold text-gray-700">Rp {{ number_format($item->price_at_time * $item->quantity, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>

            <div class="flex justify-between items-center font-bold text-lg border-t pt-4">
                <span>Total Lunas</span>
                <span class="text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="p-6 bg-white">
            <a href="{{ route('catalog') }}" class="block w-full bg-blue-100 text-blue-700 font-bold py-3 rounded-xl hover:bg-blue-200 transition">
                Kembali ke Katalog Belanja
            </a>
        </div>
    </div>

</body>
</html>