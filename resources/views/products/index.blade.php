<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📦 Produk Saya
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-4 inline-block">
                    + Tambah Produk
                </a>

                <div class="grid grid-cols-1 gap-4 mt-4">
                    @foreach($products as $product)
                    <div class="border p-4 rounded-lg flex justify-between items-center shadow-sm">
                        <div>
                            <h3 class="font-bold text-lg text-gray-700">{{ $product->name }}</h3>
                            <p class="text-blue-600 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-500">Stok: {{ $product->stock }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>