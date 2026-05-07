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

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-indigo-700">Total kategori</p>
                    <p class="mt-1 text-2xl font-black text-indigo-900">{{ $categories->count() }}</p>
                </div>
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-blue-700">Kategori dengan ikon</p>
                    <p class="mt-1 text-2xl font-black text-blue-900">{{ $categories->filter(fn($c) => filled($c->icon))->count() }}</p>
                </div>
                <div class="rounded-2xl border border-violet-100 bg-violet-50 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-violet-700">Tanpa ikon</p>
                    <p class="mt-1 text-2xl font-black text-violet-900">{{ $categories->filter(fn($c) => blank($c->icon))->count() }}</p>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                @forelse($categories as $cat)
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
                @empty
                    <div class="col-span-full rounded-3xl border-2 border-dashed border-gray-200 py-14 text-center bg-white">
                        <span class="text-4xl">🏷️</span>
                        <p class="mt-2 text-sm text-gray-500">Belum ada kategori produk.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
