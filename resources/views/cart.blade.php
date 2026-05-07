<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 antialiased text-gray-900 flex flex-col">

    <div class="bg-white/95 backdrop-blur p-4 shadow-sm flex items-center shrink-0 sticky top-0 z-50 border-b border-gray-100">
        <a href="{{ route('catalog') }}" class="text-gray-500 mr-4 font-bold text-xl">←</a>
        <h1 class="font-bold text-lg">Keranjang Saya</h1>
    </div>

    <main class="flex-1 max-w-md w-full mx-auto p-4 mt-2">
        @if (session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="mb-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-4 shadow-md">
                <p class="text-xs text-blue-100 uppercase tracking-wide font-semibold">Ringkasan keranjang</p>
                <p class="text-2xl font-black mt-1">{{ collect($cart)->sum('quantity') }} item</p>
            </div>
            <div class="space-y-4">
                @foreach($cart as $id => $details)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                    <div>
                        <span class="text-xs text-blue-500 font-semibold">{{ $details['umkm'] }}</span>
                        <h3 class="font-bold text-gray-800">{{ $details['name'] }}</h3>
                        <p class="text-orange-500 font-semibold">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('cart.decrease', $id) }}" method="POST">
                            @csrf
                            <button type="submit" class="h-8 w-8 rounded-lg border border-gray-300 bg-white text-gray-700 font-bold hover:bg-gray-50">-</button>
                        </form>
                        <div class="font-bold text-gray-700 bg-gray-100 px-3 py-1 rounded-lg min-w-12 text-center">
                            {{ $details['quantity'] }}
                        </div>
                        <form action="{{ route('cart.increase', $id) }}" method="POST">
                            @csrf
                            <button type="submit" class="h-8 w-8 rounded-lg border border-gray-300 bg-white text-gray-700 font-bold hover:bg-gray-50">+</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center mt-10 text-gray-500 bg-white border border-dashed border-gray-200 rounded-3xl p-8">
                <p>Keranjang kamu masih kosong.</p>
                <a href="{{ route('catalog') }}" class="text-blue-500 font-bold mt-2 inline-block">Mulai Belanja</a>
            </div>
        @endif
    </main>

    @if(count($cart) > 0)
    <div class="fixed bottom-0 w-full max-w-md left-1/2 transform -translate-x-1/2 bg-white/95 backdrop-blur border-t p-4 flex justify-between items-center shadow-[0_-10px_28px_rgba(0,0,0,0.12)]">
        <div>
            <p class="text-xs text-gray-500">Total Harga</p>
            <p class="font-bold text-xl text-orange-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('checkout.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-md transition text-center">
            Checkout
        </a>
    </div>
    @endif

    <x-site-footer class="{{ count($cart) > 0 ? 'pb-28' : '' }}" />

</body>
</html>