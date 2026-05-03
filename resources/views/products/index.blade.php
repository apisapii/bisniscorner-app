<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-black text-xl text-gray-900">📦 Produk saya</h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola katalog toko & kategori untuk filter pembeli.</p>
            </div>
            <a href="{{ route('products.create') }}"
               class="inline-flex justify-center items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg hover:shadow-xl hover:scale-[1.02] transition">
                + Tambah produk
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($products as $product)
                    <div class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:shadow-lg hover:border-blue-200">
                        <div class="relative h-36 bg-gradient-to-br from-slate-50 to-slate-100">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="" class="h-full w-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="flex h-full items-center justify-center text-3xl text-gray-300">📷</div>
                            @endif
                            @if($product->category)
                                <span class="absolute bottom-2 left-2 rounded-lg bg-emerald-500/95 px-2 py-0.5 text-[10px] font-bold text-white shadow">
                                    {{ $product->category->icon }} {{ $product->category->name }}
                                </span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 leading-snug">{{ $product->name }}</h3>
                            <p class="mt-1 text-lg font-black text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="mt-2 text-xs font-semibold text-gray-500">Stok: <span class="text-gray-800">{{ $product->stock }}</span></p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-3xl border-2 border-dashed border-gray-200 bg-white py-16 text-center">
                        <span class="text-4xl">📭</span>
                        <p class="mt-2 font-medium text-gray-600">Belum ada produk.</p>
                        <a href="{{ route('products.create') }}" class="mt-4 inline-block text-sm font-bold text-blue-600 hover:underline">Tambah produk pertama</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
