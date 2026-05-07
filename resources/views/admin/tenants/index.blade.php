<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-black text-xl text-gray-900">🏢 Kelola tenant UMKM</h2>
            <p class="text-sm text-gray-500 mt-1">Buat akun penjual — slug toko otomatis untuk halaman publik.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-900 flex items-center gap-2">
                    <span>🎉</span> {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-blue-700">Total GMV bazar</p>
                    <p class="mt-1 text-xl font-black text-blue-900">Rp {{ number_format($totalGmv, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-indigo-700">Service fee terkumpul</p>
                    <p class="mt-1 text-xl font-black text-indigo-900">Rp {{ number_format($totalServiceFee, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-indigo-700 mt-1">Rp 500 × {{ $totalPaidTransactions }} transaksi lunas</p>
                </div>
                <div class="rounded-2xl border border-purple-100 bg-purple-50 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-purple-700">Produk paling laku</p>
                    @if($topSellingProducts->isNotEmpty() && $topSellingProducts->first()->product)
                        <p class="mt-1 text-sm font-black text-purple-900 line-clamp-2">{{ $topSellingProducts->first()->product->name }}</p>
                        <p class="text-xs font-semibold text-purple-700">{{ (int) $topSellingProducts->first()->sold_qty }} item</p>
                    @else
                        <p class="mt-1 text-sm font-semibold text-purple-700">Belum ada transaksi lunas</p>
                    @endif
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-gradient-to-br from-white to-blue-50/50 p-6 shadow-sm">
                <h3 class="font-black text-gray-900 mb-4 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white text-sm">+</span>
                    Tenant baru
                </h3>

                <form action="{{ route('admin.tenants.store') }}" method="POST" class="grid gap-4 sm:grid-cols-2">
                    @csrf
                    <div class="sm:col-span-2">
                        <label class="text-xs font-bold uppercase tracking-wide text-gray-500">Nama UMKM / stand</label>
                        <input type="text" name="name" required placeholder="Contoh: BoMaK!"
                               class="mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wide text-gray-500">Email akun</label>
                        <input type="email" name="email" required
                               class="mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-wide text-gray-500">Password</label>
                        <input type="password" name="password" required minlength="6"
                               class="mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="sm:col-span-2">
                        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 py-3 font-bold text-white shadow-lg hover:shadow-xl transition">
                            Simpan akun tenant
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-between px-1">
                <h3 class="font-bold text-gray-800">Tenant aktif</h3>
                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-black text-blue-800">{{ $tenants->count() }}</span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                @forelse($tenants as $tenant)
                    <div class="group relative overflow-hidden rounded-3xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-lg hover:border-blue-200">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h4 class="font-black text-gray-900">{{ $tenant->name }}</h4>
                                <p class="text-xs text-gray-500 break-all mt-1">{{ $tenant->email }}</p>
                                @if($tenant->umkm)
                                    <a href="{{ route('catalog.umkm', $tenant->umkm) }}" target="_blank"
                                       class="mt-3 inline-flex items-center gap-1 rounded-lg bg-blue-50 px-2 py-1 text-[10px] font-bold text-blue-700 hover:bg-blue-100 transition">
                                        Lihat toko ↗
                                    </a>
                                    <p class="text-[10px] text-gray-400 font-mono mt-1">/toko/{{ $tenant->umkm->slug }}</p>
                                @endif
                            </div>
                            <span class="shrink-0 rounded-lg bg-indigo-50 px-2 py-1 text-[10px] font-black uppercase tracking-wide text-indigo-700">UMKM</span>
                        </div>
                        <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="mt-4"
                              onsubmit="return confirm('Hapus tenant ini beserta UMKM & produk terkait?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full rounded-xl bg-red-50 py-2 text-xs font-bold text-red-600 hover:bg-red-100 transition">
                                Hapus tenant
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-full rounded-3xl border-2 border-dashed border-gray-200 py-14 text-center">
                        <span class="text-4xl">🏪</span>
                        <p class="mt-2 text-sm text-gray-500">Belum ada tenant.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
