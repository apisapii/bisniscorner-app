<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-900">Kategori baru</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <a href="{{ route('admin.categories.index') }}" class="text-sm font-semibold text-indigo-600 hover:underline mb-4 inline-block">← Kembali</a>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                        <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-bold text-gray-700">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700">Slug (opsional)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="otomatis dari nama"
                               class="mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700">Ikon (emoji)</label>
                        <input type="text" name="icon" value="{{ old('icon') }}" placeholder="🍜"
                               class="mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-gray-700">Urutan tampil</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 py-3 font-bold text-white shadow-lg hover:shadow-xl transition">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
