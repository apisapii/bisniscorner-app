@props(['product'])

<div {{ $attributes->merge(['class' => 'group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-lg hover:border-blue-200 hover:-translate-y-0.5 transition-all duration-300']) }}>
    <div class="h-40 bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden relative">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 text-3xl">📷</div>
        @endif
        <div class="absolute top-2 left-2 flex flex-wrap gap-1">
            <a href="{{ route('catalog.umkm', $product->umkm) }}" @click.stop
               class="bg-white/95 backdrop-blur px-2 py-1 rounded-lg text-[9px] font-black text-blue-600 shadow-sm uppercase tracking-wide hover:bg-blue-600 hover:text-white transition">
                {{ $product->umkm->name }}
            </a>
            @if($product->category)
                <span class="bg-emerald-500/90 text-white px-2 py-1 rounded-lg text-[9px] font-bold shadow-sm">
                    {{ $product->category->icon }} {{ $product->category->name }}
                </span>
            @endif
        </div>
    </div>
    <div class="p-3 flex-grow flex flex-col justify-between">
        <div>
            <h4 class="font-bold text-gray-800 text-sm leading-tight mb-1 group-hover:text-blue-700 transition-colors">
                {{ $product->name }}
            </h4>
            <p class="text-[11px] text-gray-500 line-clamp-2 mb-3 min-h-[2.5rem]">
                {{ $product->description ?: '—' }}
            </p>
        </div>
        <div x-data="{ qty: 1 }">
            <p class="font-bold text-orange-500 mb-2 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <div class="mb-2 flex items-center justify-between gap-2 rounded-xl bg-slate-50 border border-slate-200 px-2 py-1.5">
                    <span class="text-[11px] font-semibold text-slate-600">Jumlah</span>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="qty = Math.max(1, qty - 1)" class="h-7 w-7 rounded-lg bg-white border border-slate-200 text-sm font-bold text-slate-700 hover:bg-slate-100">-</button>
                        <input type="number" name="quantity" x-model.number="qty" min="1" max="999" class="w-12 h-7 rounded-lg border-slate-200 text-center text-xs font-bold p-1">
                        <button type="button" @click="qty = Math.min(999, qty + 1)" class="h-7 w-7 rounded-lg bg-white border border-slate-200 text-sm font-bold text-slate-700 hover:bg-slate-100">+</button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-2 rounded-xl text-sm hover:from-blue-700 hover:to-indigo-700 shadow-md active:scale-[0.98] transition">
                    + Tambah ke Keranjang
                </button>
            </form>
        </div>
    </div>
</div>
