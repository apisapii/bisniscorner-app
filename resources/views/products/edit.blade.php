<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-black text-xl text-gray-900">✏️ Edit produk</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui info produk agar katalog selalu akurat.</p>
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

                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" name="name" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: Basreng Pedas Daun Jeruk"
                            value="{{ old('name', $product->name) }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">— Pilih (disarankan) —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" name="price" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('price', $product->price) }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stock" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('stock', $product->stock) }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700">Deskripsi Produk</label>
                        <textarea name="description" class="w-full rounded-xl border-gray-200 mt-1" placeholder="Cth: Pedas gurih daun jeruk...">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700">Foto Produk (opsional)</label>
                        <input type="file" name="image" class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="" class="mt-3 h-24 w-24 rounded-xl object-cover border border-gray-200">
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition duration-200">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
