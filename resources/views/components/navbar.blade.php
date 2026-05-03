<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-white/10 bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-700 text-white shadow-lg">
    @if (session('warning'))
        <div class="max-w-md mx-auto px-3 pt-3">
            <div class="bg-amber-100 text-amber-900 text-xs font-semibold px-3 py-2 rounded-xl border border-amber-200">
                {{ session('warning') }}
            </div>
        </div>
    @endif
    <div class="max-w-md mx-auto flex justify-between items-center p-3 sm:p-4">
        <div class="flex items-center gap-2 sm:gap-4 min-w-0">
            <a href="{{ route('catalog') }}" class="font-black text-base sm:text-lg whitespace-nowrap shrink-0 hover:opacity-90 transition">
                🚀 Bazar
            </a>
            <div class="hidden sm:flex items-center gap-2 text-xs sm:text-sm font-semibold text-blue-100">
                <a href="{{ route('catalog') }}#filter-panel" class="hover:text-white transition">Cari</a>
                <a href="{{ route('catalog') }}#tenants" class="hover:text-white transition">Toko</a>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            @auth
                @if(Auth::user()->role === 'customer')
                    <a href="{{ route('customer.history') }}" class="text-xs font-semibold text-blue-100 hover:text-white transition hidden sm:inline">Riwayat</a>
                @elseif(Auth::user()->role === 'admin_umkm')
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-blue-100 hover:text-white transition">UMKM</a>
                @elseif(Auth::user()->role === 'super_admin')
                    <a href="{{ route('admin.tenants.index') }}" class="text-xs font-semibold text-blue-100 hover:text-white transition">Admin</a>
                @endif
                <div class="relative">
                    <button type="button" @click="open = !open" class="flex items-center focus:outline-none transition hover:scale-105">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=EBF8FF&color=2B6CB0&bold=true"
                             alt="" class="w-9 h-9 rounded-full border-2 border-white/80 shadow-md object-cover">
                    </button>
                    <div x-show="open" @click.away="open = false" style="display: none;"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl py-2 border border-gray-100 text-gray-800 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm font-medium hover:bg-gray-50">Profil</a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50">Keluar</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-blue-100 hover:text-white">Masuk</a>
                <a href="{{ route('register') }}" class="text-xs sm:text-sm bg-white text-indigo-700 hover:bg-blue-50 shadow-md px-3 sm:px-4 py-2 rounded-full font-bold transition">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</nav>
