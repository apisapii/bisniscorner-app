<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $umkm->name }} — Bazar PCR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-slate-50 antialiased text-gray-900">
    <div class="shrink-0"><x-navbar /></div>

    <div class="flex-1 flex flex-col w-full min-h-0">
    <header class="max-w-md mx-auto w-full relative overflow-hidden rounded-b-[2rem] bg-gradient-to-br from-slate-800 via-blue-900 to-indigo-900 text-white shadow-xl mb-6">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_30%_20%,white,transparent_50%)]"></div>
        <div class="relative px-5 pt-8 pb-8">
            <a href="{{ route('catalog') }}#tenants" class="inline-flex items-center text-xs font-bold text-blue-200 hover:text-white mb-4 transition">
                ← Semua tenant
            </a>
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center text-2xl font-black border border-white/20">
                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($umkm->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-black leading-tight">{{ $umkm->name }}</h1>
                    <p class="text-blue-200 text-sm mt-1 leading-snug">{{ $umkm->description ?: 'Produk dari tenant ini.' }}</p>
                    @if($umkm->contact_name || $umkm->contact_phone)
                        @php
                            $rawPhone = (string) ($umkm->contact_phone ?? '');
                            $normalizedDigits = preg_replace('/\D+/', '', $rawPhone) ?? '';
                            if (str_starts_with($normalizedDigits, '0')) {
                                $normalizedDigits = '62'.substr($normalizedDigits, 1);
                            } elseif (str_starts_with($normalizedDigits, '8')) {
                                $normalizedDigits = '62'.$normalizedDigits;
                            }
                            $waUrl = $normalizedDigits !== ''
                                ? 'https://wa.me/'.$normalizedDigits.'?text='.urlencode('Halo '.$umkm->name.', saya mau tanya produk di toko ini.')
                                : null;
                        @endphp
                        <div class="mt-3 inline-flex flex-col gap-1 rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-xs text-blue-50">
                            <span class="font-semibold">Kontak UMKM</span>
                            @if($umkm->contact_name)
                                <span>{{ $umkm->contact_name }}</span>
                            @endif
                            @if($umkm->contact_phone)
                                @if($waUrl)
                                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-semibold text-emerald-200 hover:text-emerald-100">
                                        💬 WhatsApp: {{ $umkm->contact_phone }}
                                    </a>
                                @else
                                    <span>{{ $umkm->contact_phone }}</span>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-md mx-auto w-full px-4 pb-28" x-data="{ open: false }">
        <form method="GET" action="{{ route('catalog.umkm', $umkm) }}" class="space-y-4 mb-6">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari di toko ini..."
                       class="w-full pl-10 pr-4 py-3 rounded-2xl border border-gray-200 shadow-sm focus:ring-2 focus:ring-blue-500 text-sm bg-white">
            </div>
            <button type="button" @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-2xl bg-white border border-gray-200 shadow-sm text-sm font-bold text-gray-700">
                <span>Filter di toko ini</span>
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
                        <label class="text-xs font-bold text-gray-500 uppercase">Min</label>
                        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" class="mt-1 w-full rounded-xl border-gray-200 text-sm py-2.5">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Max</label>
                        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" class="mt-1 w-full rounded-xl border-gray-200 text-sm py-2.5">
                    </div>
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
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-2.5 rounded-xl text-sm">Terapkan</button>
                    <a href="{{ route('catalog.umkm', $umkm) }}" class="flex-1 text-center border-2 border-gray-200 font-bold py-2.5 rounded-xl text-sm">Reset</a>
                </div>
            </div>
        </form>

        <p class="text-xs text-gray-500 mb-3">{{ $products->count() }} produk di toko ini</p>
        <div class="grid grid-cols-2 gap-3">
            @forelse($products as $product)
                <x-catalog.product-card :product="$product" />
            @empty
                <div class="col-span-2 text-center py-12 rounded-3xl bg-white border border-dashed border-gray-200">
                    <p class="text-gray-500 text-sm">Belum ada produk yang cocok.</p>
                </div>
            @endforelse
        </div>
    </main>
    </div>

    @php
        $cartCount = 0;
        if (session('cart')) {
            foreach (session('cart') as $item) {
                $cartCount += $item['quantity'];
            }
        }
    @endphp
    <div class="fixed bottom-0 w-full max-w-md left-1/2 -translate-x-1/2 bg-white/95 backdrop-blur border-t p-3 flex justify-between items-center shadow-lg z-50">
        <a href="{{ route('catalog') }}" class="text-sm font-bold text-blue-600">← Katalog</a>
        <a href="{{ route('cart.index') }}" class="relative bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-2 rounded-2xl font-bold">
            Keranjang @if($cartCount) ({{ $cartCount }}) @endif
        </a>
    </div>

    <x-site-footer class="pb-28" />
</body>
</html>
