<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏢 Kelola Tenant UMKM
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-md mx-auto sm:max-w-4xl sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 mx-4 sm:mx-0 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
                    <span>🎉</span> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-5 mb-6 mx-4 sm:mx-0">
                <h3 class="font-bold text-gray-800 mb-3 text-base">➕ Daftarkan Tenant Baru</h3>
                
                <form action="{{ route('admin.tenants.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nama UMKM / Stand</label>
                        <input type="text" name="name" required placeholder="Contoh: BoMaK!" 
                               class="w-full mt-1 border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl text-sm p-2.5">
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email Akun</label>
                        <input type="email" name="email" required placeholder="Contoh: bomak@bazaar.com" 
                               class="w-full mt-1 border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl text-sm p-2.5">
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Password</label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter" 
                               class="w-full mt-1 border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl text-sm p-2.5">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition duration-200">
                        Simpan Akun Tenant
                    </button>
                </form>
            </div>

            <div class="px-4 sm:px-0 mb-3 flex justify-between items-center">
                <h3 class="font-bold text-gray-700">List Tenant Aktif</h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $tenants->count() }} Tenant
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-4 sm:px-0">
                @forelse($tenants as $tenant)
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-4 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-bold text-gray-800 text-base leading-tight">{{ $tenant->name }}</h4>
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-2 py-0.5 rounded-md uppercase tracking-wider">
                                    Seller
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 break-all mb-4">{{ $tenant->email }}</p>
                        </div>
                        
                        <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus tenant ini? Semua produknya juga akan hilang!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 rounded-xl text-xs transition duration-200">
                                🗑️ Hapus Tenant
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <span class="text-4xl block mb-2">🏪</span>
                        <p class="text-gray-500 font-medium text-sm">Belum ada tenant yang didaftarkan.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>