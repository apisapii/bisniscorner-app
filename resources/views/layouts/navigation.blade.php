@php
    $role = Auth::user()->role;
    $homeRoute = match ($role) {
        'super_admin' => route('admin.tenants.index'),
        'admin_umkm' => route('dashboard'),
        default => route('catalog'),
    };
    $linkBase = 'px-3 py-2 rounded-xl text-sm font-semibold transition';
    $linkInactive = 'text-indigo-200 hover:bg-white/10 hover:text-white';
    $linkActive = 'bg-white/15 text-white shadow-inner';
@endphp

{{-- Navbar admin: burger di bawah lg (tablet & hp). Profil hanya ada di dropdown desktop lg+ atau di dalam panel burger --}}
<nav x-data="{ open: false }" @keydown.escape.window="open = false" class="border-b border-white/10 bg-gradient-to-r from-slate-900 via-indigo-900 to-slate-900 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-4 lg:gap-8 min-w-0">
                <a href="{{ $homeRoute }}" class="flex items-center gap-2 shrink-0 group">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-lg backdrop-blur group-hover:bg-white/20 transition">🚀</span>
                    <span class="font-black text-white tracking-tight truncate max-w-[10rem] sm:max-w-none">Bazar PCR</span>
                </a>

                <div class="hidden lg:flex items-center gap-1">
                    @if ($role === 'admin_umkm')
                        <a href="{{ route('dashboard') }}" class="{{ $linkBase }} {{ request()->routeIs('dashboard') ? $linkActive : $linkInactive }}">Pesanan</a>
                        <a href="{{ route('products.index') }}" class="{{ $linkBase }} {{ request()->routeIs('products.*') ? $linkActive : $linkInactive }}">Produk</a>
                    @elseif ($role === 'super_admin')
                        <a href="{{ route('admin.categories.index') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.categories.*') ? $linkActive : $linkInactive }}">Kategori</a>
                        <a href="{{ route('admin.tenants.index') }}" class="{{ $linkBase }} {{ request()->routeIs('admin.tenants.*') ? $linkActive : $linkInactive }}">Tenant</a>
                    @else
                        <a href="{{ route('catalog') }}" class="{{ $linkBase }} {{ request()->routeIs('catalog') && !request()->routeIs('catalog.umkm') ? $linkActive : $linkInactive }}">Katalog</a>
                        <a href="{{ route('customer.history') }}" class="{{ $linkBase }} {{ request()->routeIs('customer.*') ? $linkActive : $linkInactive }}">Riwayat</a>
                    @endif
                </div>
            </div>

            <div class="hidden lg:flex lg:items-center shrink-0">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-white hover:bg-white/10 transition">
                            <span class="max-w-[10rem] truncate">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 opacity-70 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        @if ($role === 'admin_umkm')
                            <x-dropdown-link :href="route('profile.edit')">Profil & kontak toko</x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center lg:hidden">
                <button type="button" @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-indigo-200 hover:bg-white/10 hover:text-white transition"
                        :aria-expanded="open.toString()"
                        :aria-label="open ? 'Tutup menu' : 'Buka menu'">
                    <svg x-show="!open" class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Panel burger (hp & tablet < lg): menu utama + akun lengkap --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         style="display: none;"
         @click.outside="open = false"
         class="border-t border-white/10 bg-slate-900/98 backdrop-blur-md lg:hidden">
        <div class="pt-2 space-y-1 px-4 max-w-7xl mx-auto">
            @if ($role === 'admin_umkm')
                <a href="{{ route('dashboard') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold text-white hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-white/15' : '' }}" @click="open = false">Pesanan</a>
                <a href="{{ route('products.index') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold text-white hover:bg-white/10 {{ request()->routeIs('products.*') ? 'bg-white/15' : '' }}" @click="open = false">Produk</a>
            @elseif ($role === 'super_admin')
                <a href="{{ route('admin.categories.index') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold text-white hover:bg-white/10 {{ request()->routeIs('admin.categories.*') ? 'bg-white/15' : '' }}" @click="open = false">Kategori</a>
                <a href="{{ route('admin.tenants.index') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold text-white hover:bg-white/10 {{ request()->routeIs('admin.tenants.*') ? 'bg-white/15' : '' }}" @click="open = false">Tenant</a>
            @else
                <a href="{{ route('catalog') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold text-white hover:bg-white/10" @click="open = false">Katalog</a>
                <a href="{{ route('customer.history') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold text-white hover:bg-white/10" @click="open = false">Riwayat</a>
            @endif

            <div class="border-t border-white/10 mt-4 pt-3 pb-4 space-y-2">
                <p class="px-3 text-xs font-bold uppercase tracking-wide text-indigo-300">Akun</p>
                <p class="px-3 text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="px-3 text-xs text-indigo-200 truncate">{{ Auth::user()->email }}</p>
                @if ($role === 'admin_umkm')
                    <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold bg-white/10 text-white hover:bg-white/20" @click="open = false">
                        Profil & kontak toko UMKM
                    </a>
                @else
                    <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold bg-white/10 text-white hover:bg-white/20" @click="open = false">
                        Profil
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left rounded-xl px-3 py-2.5 text-sm font-bold text-red-200 hover:bg-red-500/20">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
