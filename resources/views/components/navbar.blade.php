<nav x-data="{ open: false }" class="bg-blue-600 text-white p-4 shadow-md sticky top-0 z-50">
    <div class="max-w-md mx-auto flex justify-between items-center">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('catalog') }}" class="font-bold text-lg flex items-center">
                🚀 Bazar PCR
            </a>
            
            <div class="hidden sm:flex items-center gap-3 ml-2">
                <a href="{{ route('catalog') }}" class="text-sm font-medium text-blue-100 hover:text-white transition">
                    Home
                </a>
                <a href="{{ route('catalog') }}" class="text-sm font-medium text-blue-100 hover:text-white transition">
                    Product
                </a>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @auth
                <div class="relative">
                    <button @click="open = !open" class="flex items-center focus:outline-none transition transform hover:scale-105">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=EBF8FF&color=2B6CB0&bold=true" 
                             alt="Profile" 
                             class="w-9 h-9 rounded-full border-2 border-white shadow-sm object-cover">
                    </button>

                    <div x-show="open" 
                         @click.away="open = false" 
                         style="display: none;" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100 text-gray-800 z-50">
                        
                        <div class="px-4 py-2 border-b border-gray-100 mb-1">
                            <p class="text-sm font-bold truncate text-gray-800">{{ Auth::user()->name }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm font-medium hover:bg-gray-100 hover:text-blue-600 transition">
                            👤 Profile
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-gray-100">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50 transition">
                                🚪 Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-sm bg-white text-blue-600 hover:bg-blue-50 shadow-sm px-4 py-1.5 rounded-full font-bold transition">
                    Login
                </a>
            @endauth
        </div>
        
    </div>
</nav>