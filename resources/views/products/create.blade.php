<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-black text-xl text-gray-900">➕ Produk baru</h2>
            <p class="text-sm text-gray-500 mt-1">Pilih kategori agar pembeli bisa filter di katalog.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                
                <a href="{{ route('products.index') }}" class="text-sm text-gray-500 mb-4 inline-block">
                    ← Kembali ke Daftar Produk
                </a>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-5 rounded-r-xl shadow-sm">
                        <p class="text-xs font-black uppercase tracking-wider mb-1">Cek lagi data kamu:</p>
                        <ul class="list-disc pl-5 text-sm font-semibold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" name="name" required 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: Basreng Pedas Daun Jeruk"
                            value="{{ old('name') }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">— Pilih (disarankan) —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Master kategori diatur super admin.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" name="price" required 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="10000"
                            value="{{ old('price') }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Stok Awal</label>
                        <input type="number" name="stock" required 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="50"
                            value="{{ old('stock') }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700">Deskripsi Produk</label>
                        <textarea name="description" class="w-full rounded-xl border-gray-200 mt-1" placeholder="Cth: Pedas gurih daun jeruk...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700">Foto Produk</label>
                        <input type="file" name="image" class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition duration-200">
                        Simpan Produk
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>