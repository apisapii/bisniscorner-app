@php
    $miniCart = session('cart', []);
    $miniCartCount = collect($miniCart)->sum(fn ($item) => (int) ($item['quantity'] ?? 0));
@endphp

<nav x-data="{ menuOpen: false, cartOpen: false }"
     @keydown.escape.window="menuOpen = false; cartOpen = false"
     class="sticky top-0 z-50 border-b border-white/10 bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-700 text-white shadow-lg">
    @if (session('warning'))
        <div class="max-w-md mx-auto px-3 pt-3">
            <div class="bg-amber-100 text-amber-900 text-xs font-semibold px-3 py-2 rounded-xl border border-amber-200">
                {{ session('warning') }}
            </div>
        </div>
    @endif

    <div class="max-w-md mx-auto flex items-center justify-between gap-2 p-3 sm:p-4">
        <a href="{{ route('catalog') }}" class="font-black text-base sm:text-lg whitespace-nowrap shrink-0 hover:opacity-90 transition min-w-0">
            🚀 Bazar
        </a>

        <div class="flex items-center gap-2 shrink-0">
            <div class="relative">
                <button type="button" @click="cartOpen = !cartOpen; menuOpen = false" :aria-expanded="cartOpen.toString()"
                        class="relative rounded-full border border-white/30 p-2 text-white/95 hover:bg-white/10 transition"
                        aria-label="Keranjang">
                    <span class="text-lg leading-none">🛒</span>
                    @if($miniCartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold min-w-[1.1rem] h-4 px-1 rounded-full flex items-center justify-center border border-white">
                            {{ $miniCartCount }}
                        </span>
                    @endif
                </button>
                <div x-show="cartOpen" @click.away="cartOpen = false" style="display: none;"
                     class="absolute right-0 mt-2 w-72 max-w-[85vw] bg-white rounded-2xl shadow-xl border border-gray-100 text-gray-800 z-50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-black">Keranjang</p>
                    </div>
                    @if(count($miniCart) > 0)
                        <div class="max-h-72 overflow-y-auto">
                            @foreach($miniCart as $id => $item)
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-xs text-blue-600 font-semibold">{{ $item['umkm'] }}</p>
                                    <p class="text-sm font-bold leading-tight">{{ $item['name'] }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <p class="text-xs font-semibold text-orange-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                        <div class="flex items-center gap-1">
                                            <form action="{{ route('cart.decrease', $id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="h-7 w-7 rounded-lg border border-gray-300 bg-white text-gray-700 font-bold hover:bg-gray-50">-</button>
                                            </form>
                                            <span class="min-w-7 text-center text-xs font-bold">{{ $item['quantity'] }}</span>
                                            <form action="{{ route('cart.increase', $id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="h-7 w-7 rounded-lg border border-gray-300 bg-white text-gray-700 font-bold hover:bg-gray-50">+</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-3 bg-gray-50">
                            <a href="{{ route('cart.index') }}" class="block w-full text-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 text-sm font-bold">
                                Lihat Keranjang
                            </a>
                        </div>
                    @else
                        <div class="px-4 py-8 text-center">
                            <p class="text-sm text-gray-500">Keranjang masih kosong.</p>
                        </div>
                    @endif
                </div>
            </div>

            <button type="button"
                    @click="menuOpen = !menuOpen; cartOpen = false"
                    class="rounded-lg border border-white/30 p-2 hover:bg-white/10 transition"
                    :aria-expanded="menuOpen.toString()"
                    :aria-label="menuOpen ? 'Tutup menu' : 'Buka menu'">
                <svg x-show="!menuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="menuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="menuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         @click.away="menuOpen = false"
         style="display: none;"
         class="border-t border-white/15 bg-indigo-950/95 backdrop-blur-md text-white shadow-inner">
        <div class="max-w-md mx-auto px-4 py-4 space-y-1 text-sm font-semibold">
            <a href="{{ route('catalog') }}" @click="menuOpen = false" class="block rounded-xl px-3 py-3 hover:bg-white/10 transition">Beranda katalog</a>
            <a href="{{ route('catalog') }}#filter-panel" @click="menuOpen = false" class="block rounded-xl px-3 py-3 hover:bg-white/10 transition">Cari & filter</a>
            <a href="{{ route('catalog') }}#tenants" @click="menuOpen = false" class="block rounded-xl px-3 py-3 hover:bg-white/10 transition">Semua tenant</a>
            <a href="{{ route('cart.index') }}" @click="menuOpen = false" class="block rounded-xl px-3 py-3 hover:bg-white/10 transition flex items-center justify-between">
                <span>Keranjang</span>
                @if($miniCartCount > 0)
                    <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs">{{ $miniCartCount }}</span>
                @endif
            </a>

            <div class="my-3 border-t border-white/10"></div>

            @auth
                <p class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-blue-200">Akun</p>
                <p class="px-3 py-1 text-xs text-blue-100 truncate">{{ Auth::user()->name }}</p>
                @if(Auth::user()->role === 'customer')
                    <a href="{{ route('customer.history') }}" @click="menuOpen = false" class="block rounded-xl px-3 py-3 hover:bg-white/10 transition">Riwayat pesanan</a>
                @elseif(Auth::user()->role === 'admin_umkm')
                    <a href="{{ route('dashboard') }}" @click="menuOpen = false" class="block rounded-xl px-3 py-3 hover:bg-white/10 transition">Dashboard UMKM</a>
                @elseif(Auth::user()->role === 'super_admin')
                    <a href="{{ route('admin.tenants.index') }}" @click="menuOpen = false" class="block rounded-xl px-3 py-3 hover:bg-white/10 transition">Panel super admin</a>
                @endif
                <a href="{{ route('profile.edit') }}" @click="menuOpen = false" class="block rounded-xl px-3 py-3 hover:bg-white/10 transition">Profil</a>
                <form method="POST" action="{{ route('logout') }}" class="px-0 pt-1">
                    @csrf
                    <button type="submit" class="w-full text-left rounded-xl px-3 py-3 font-bold text-red-200 hover:bg-red-500/20 transition">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" @click="menuOpen = false" class="block rounded-xl px-3 py-3 hover:bg-white/10 transition">Masuk (customer)</a>
                <a href="{{ route('login') }}" @click="menuOpen = false" class="block rounded-xl bg-white/15 px-3 py-3 text-center font-bold hover:bg-white/25 transition">Masuk</a>
                <a href="{{ route('register') }}" @click="menuOpen = false" class="block rounded-xl bg-white/15 px-3 py-3 text-center font-bold hover:bg-white/25 transition">Daftar</a>
            @endauth

            <div class="pt-3">
                <a href="https://www.instagram.com/bisniscorner.pcr/" target="_blank" rel="noopener noreferrer"
                   class="flex items-center justify-center gap-2 rounded-xl border border-white/20 px-3 py-2 text-xs text-pink-100 hover:bg-white/10 transition">
                    IG @bisniscorner.pcr
                </a>
            </div>
        </div>
    </div>
</nav>
