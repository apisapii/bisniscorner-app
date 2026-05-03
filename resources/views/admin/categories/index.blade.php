<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-black text-xl text-gray-900">🏷️ Kategori produk</h2>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex justify-center items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-lg hover:shadow-xl transition">
                + Kategori baru
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-3 sm:grid-cols-2">
                @foreach($categories as $cat)
                    <div class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-indigo-200">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-slate-100 to-slate-50 text-2xl ring-1 ring-gray-100">
                                    {{ $cat->icon ?: '📁' }}
                                </span>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $cat->name }}</h3>
                                    <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $cat->slug }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">Urutan: {{ $cat->sort_order }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('admin.categories.edit', $cat) }}"
                                   class="rounded-lg bg-blue-50 px-3 py-1.5 text-center text-xs font-bold text-blue-700 hover:bg-blue-100 transition">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                      onsubmit="return confirm('Hapus kategori ini? Produk akan kehilangan kategori.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-100 transition">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
