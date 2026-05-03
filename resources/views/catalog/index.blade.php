<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog — Bazar PCR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 antialiased text-gray-900 min-h-screen">
    <x-navbar />

    <header class="relative max-w-md mx-auto overflow-hidden rounded-b-[2rem] bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-700 text-white shadow-xl mb-6">
        <div class="absolute inset-0 opacity-30 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.08\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        <div class="relative px-5 pt-8 pb-10 text-center">
            <p class="text-blue-200 text-xs font-semibold tracking-widest uppercase mb-1">Business Corner 2026</p>
            <h1 class="text-2xl font-black mb-2">Jelajahi tenant kampus</h1>
            <p class="text-blue-100 text-sm leading-relaxed max-w-xs mx-auto">
                Cari, filter harga & kategori, atau kunjungi tokonya langsung.
            </p>
        </div>
    </header>

    <main class="max-w-md mx-auto px-4 pb-28" x-data="{ open: false }" id="filter-panel">
        <form method="GET" action="{{ route('catalog') }}" class="space-y-4 mb-6">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari produk atau nama tenant..."
                       class="w-full pl-10 pr-4 py-3 rounded-2xl border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
            </div>

            <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-2xl bg-white border border-gray-200 shadow-sm text-sm font-bold text-gray-700 hover:border-blue-300 transition">
                <span>Filter & urutan</span>
                <span class="text-blue-600" x-text="open ? '▲' : '▼'"></span>
            </button>

            <div x-show="open" x-transition class="space-y-3 bg-white rounded-2xl border border-gray-100 p-4 shadow-sm" style="display: none;">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Kategori</label>
                    <select name="category_id" class="mt-1 w-full rounded-xl border-gray-200 text-sm py-2.5">
                        <option value="">Semua</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(($filters['category_id'] ?? '') == $cat->id)>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Harga min</label>
                        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="0"
                               class="mt-1 w-full rounded-xl border-gray-200 text-sm py-2.5">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Harga max</label>
                        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="∞"
                               class="mt-1 w-full rounded-xl border-gray-200 text-sm py-2.5">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Tenant (UMKM)</label>
                    <select name="umkm_id" class="mt-1 w-full rounded-xl border-gray-200 text-sm py-2.5">
                        <option value="">Semua tenant</option>
                        @foreach($umkms as $u)
                            <option value="{{ $u->id }}" @selected(($filters['umkm_id'] ?? '') == $u->id)>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Urutkan</label>
                    <select name="sort" class="mt-1 w-full rounded-xl border-gray-200 text-sm py-2.5">
                        <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Terbaru</option>
                        <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Harga ↑</option>
                        <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Harga ↓</option>
                        <option value="name" @selected(($filters['sort'] ?? '') === 'name')>Nama A–Z</option>
                    </select>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-2.5 rounded-xl text-sm hover:bg-blue-700 transition">
                        Terapkan
                    </button>
                    <a href="{{ route('catalog') }}" class="flex-1 text-center border-2 border-gray-200 text-gray-700 font-bold py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <section class="mb-8" id="tenants">
            <h2 class="text-lg font-black text-gray-800 mb-3 flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white text-sm">🏪</span>
                Pilih tenant
            </h2>
            <div class="flex gap-3 overflow-x-auto pb-2 -mx-1 px-1 snap-x snap-mandatory scrollbar-hide" style="scrollbar-width: none;">
                @foreach($umkms as $u)
                    <a href="{{ route('catalog.umkm', $u) }}"
                       class="snap-start shrink-0 w-36 rounded-2xl bg-gradient-to-br from-white to-blue-50 border border-blue-100 p-3 shadow-sm hover:shadow-md hover:border-blue-300 transition text-center group">
                        <div class="w-12 h-12 mx-auto rounded-full bg-blue-600 text-white flex items-center justify-center text-lg font-black mb-2 group-hover:scale-110 transition-transform">
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($u->name, 0, 1)) }}
                        </div>
                        <p class="text-xs font-bold text-gray-800 line-clamp-2 leading-tight">{{ $u->name }}</p>
                        <p class="text-[10px] text-blue-600 font-semibold mt-1">Lihat produk →</p>
                    </a>
                @endforeach
            </div>
        </section>

        <div class="flex justify-between items-end mb-4">
            <div>
                <h2 class="text-xl font-black text-gray-800">Produk</h2>
                <p class="text-xs text-gray-500">{{ $products->count() }} hasil</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            @forelse($products as $product)
                <x-catalog.product-card :product="$product" />
            @empty
                <div class="col-span-2 text-center py-16 rounded-3xl bg-white border-2 border-dashed border-gray-200">
                    <span class="text-4xl block mb-2">🔎</span>
                    <p class="text-gray-600 font-medium text-sm">Tidak ada produk yang cocok.</p>
                    <a href="{{ route('catalog') }}" class="inline-block mt-3 text-sm font-bold text-blue-600">Reset filter</a>
                </div>
            @endforelse
        </div>
    </main>

    @php
        $cartCount = 0;
        if (session('cart')) {
            foreach (session('cart') as $item) {
                $cartCount += $item['quantity'];
            }
        }
    @endphp
    <div class="fixed bottom-0 w-full max-w-md left-1/2 -translate-x-1/2 bg-white/95 backdrop-blur border-t border-gray-200 p-3 flex justify-between items-center shadow-[0_-8px_30px_rgba(0,0,0,0.08)] z-50">
        <div>
            <p class="text-xs text-gray-500">Keranjang</p>
            <p class="font-bold text-sm text-gray-900">{{ $cartCount }} item</p>
        </div>
        <a href="{{ route('cart.index') }}" class="relative bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2.5 rounded-2xl font-bold shadow-lg hover:shadow-xl transition">
            Checkout 🛒
            @if($cartCount > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold min-w-[1.25rem] h-5 px-1 rounded-full flex items-center justify-center border-2 border-white">{{ $cartCount }}</span>
            @endif
        </a>
    </div>
</body>
</html>
