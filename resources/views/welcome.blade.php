<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bazaar Digital Kampus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased text-gray-900">
    <x-navbar />

    <div class="bg-blue-600 text-white rounded-b-3xl shadow-md mb-4 max-w-md mx-auto overflow-hidden">
        <div class="pb-6 pt-4 px-4 text-center">
            <h2 class="text-xl font-extrabold mb-1">Business Corner 2026</h2>
            <p class="text-blue-100 text-sm mb-4 leading-tight">
                Pesan online, langsung ambil di tenant GSG Lantai 2 tanpa antre panjang!
            </p>
            
            <div class="flex justify-between bg-blue-800 bg-opacity-40 rounded-xl p-3 text-center text-xs font-semibold">
                <div class="flex-1">
                    <span class="block text-xl mb-1">🛒</span>
                    Pilih
                </div>
                <div class="flex-1 border-x border-blue-500 border-opacity-30">
                    <span class="block text-xl mb-1">💳</span>
                    QRIS
                </div>
                <div class="flex-1">
                    <span class="block text-xl mb-1">🏃‍♂️</span>
                    Ambil
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-md mx-auto p-4 pb-20">
        
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Jelajahi Tenant</h2>
            <p class="text-gray-500 text-sm">Pesan sekarang, ambil langsung di stand tanpa antre!</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            @forelse($products as $product)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden flex flex-col">
                <!-- FOTO/IMAGE PRODUCT - REPLACED HERE -->
                <div class="h-40 bg-gray-100 overflow-hidden relative">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs italic">
                            No Image
                        </div>
                    @endif
                    <div class="absolute top-2 left-2">
                        <span class="bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-[9px] font-black text-blue-600 shadow-sm uppercase">
                            {{ $product->umkm->name }}
                        </span>
                    </div>
                </div>
                
                <div class="p-3 flex-grow flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm leading-tight mb-1">
                            {{ $product->name }}
                        </h4>
                        <p class="text-[11px] text-gray-500 line-clamp-2 mb-3 h-8">
                            {{ $product->description }}
                        </p>
                    </div>
                    <div>
                        <p class="font-bold text-orange-500 mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-blue-100 text-blue-600 font-semibold py-1.5 rounded-lg text-sm hover:bg-blue-600 hover:text-white transition">
                                + Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-10 text-gray-500">
                Belum ada produk yang dijual hari ini. 😢
            </div>
            @endforelse
        </div>

    </main>

    <div class="fixed bottom-0 w-full max-w-md left-1/2 transform -translate-x-1/2 bg-white border-t p-3 flex justify-between items-center shadow-lg z-50">
        <div>
            <p class="text-xs text-gray-500">Sudah selesai milih?</p>
            <p class="font-bold text-sm text-gray-800">Cek belanjaanmu 👉</p>
        </div>
        
        @php
            $cartCount = 0;
            if(session('cart')) {
                foreach(session('cart') as $item) {
                    $cartCount += $item['quantity'];
                }
            }
        @endphp

        <a href="{{ route('cart.index') }}" class="relative bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-bold shadow-md transition flex items-center">
            Lihat Keranjang 🛒
            
            @if($cartCount > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full border-2 border-white shadow-sm">
                {{ $cartCount }}
            </span>
            @endif
        </a>
    </div>

</body>
</html>