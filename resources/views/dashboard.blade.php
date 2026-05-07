<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-black text-xl text-gray-900 tracking-tight">📊 Pesanan masuk</h2>
            <p class="text-sm text-gray-500 mt-1">Tandai siap diambil setelah pembayaran lunas.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-md mx-auto sm:max-w-7xl sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 mx-4 sm:mx-0 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="mb-4 mx-4 sm:mx-0 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-900">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-4 mx-4 sm:mx-0 mb-5 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between mb-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wide text-gray-500">Laporan GMV penjualan lunas</p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Mengacu pada tanggal <strong>dibayar</strong>.
                            @if($filterHint)
                                <span class="text-emerald-700 font-semibold">Rentang dipilih: {{ $filterHint }}</span>
                            @else
                                <span class="font-semibold text-gray-700">Tanpa filter: semua waktu.</span>
                            @endif
                            <span class="block mt-1 text-gray-600">Daftar antrean di bawah otomatis menyamakan rentang ini — reset untuk menampilkan semua pesanan.</span>
                        </p>
                    </div>
                </div>
                <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 sm:grid-cols-[1fr_1fr_auto] gap-2 items-end">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400">Mulai tanggal</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $tanggalMulai ?? '') }}"
                               class="mt-1 w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400">Sampai tanggal</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $tanggalSelesai ?? '') }}"
                               class="mt-1 w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white shadow hover:bg-emerald-700 shrink-0">Terapkan</button>
                        <a href="{{ route('dashboard') }}" class="rounded-xl border border-gray-200 px-3 py-2 text-sm font-bold text-gray-600 hover:bg-gray-50 whitespace-nowrap">Reset</a>
                    </div>
                </form>
                <div class="mt-3 flex flex-wrap gap-2 text-[11px]">
                    <span class="text-gray-400">Cepat:</span>
                    <a href="{{ route('dashboard', ['tanggal_mulai' => now()->toDateString(), 'tanggal_selesai' => now()->toDateString()]) }}" class="font-semibold text-blue-600 hover:underline">Hari ini</a>
                    <a href="{{ route('dashboard', ['tanggal_mulai' => now()->subDay()->toDateString(), 'tanggal_selesai' => now()->subDay()->toDateString()]) }}" class="font-semibold text-blue-600 hover:underline">Kemarin</a>
                    <a href="{{ route('dashboard', ['tanggal_mulai' => now()->subDays(6)->toDateString(), 'tanggal_selesai' => now()->toDateString()]) }}" class="font-semibold text-blue-600 hover:underline">7 hari</a>
                    <a href="{{ route('dashboard', ['tanggal_mulai' => now()->startOfMonth()->toDateString(), 'tanggal_selesai' => now()->toDateString()]) }}" class="font-semibold text-blue-600 hover:underline">Bulan ini</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 px-4 sm:px-0 mb-5">
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-700">Total omzet (GMV)</p>
                    <p class="mt-1 text-xl font-black text-emerald-900">Rp {{ number_format($financials['gmv'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-blue-700">Pesanan lunas</p>
                    <p class="mt-1 text-xl font-black text-blue-900">{{ $financials['paid_orders_count'] }}</p>
                </div>
                <div class="rounded-2xl border border-violet-100 bg-violet-50 p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-violet-700">Produk paling laku</p>
                    @if($topProduct && $topProduct->product)
                        <p class="mt-1 text-sm font-black text-violet-900 line-clamp-2">{{ $topProduct->product->name }}</p>
                        <p class="text-xs font-semibold text-violet-700">{{ (int) $topProduct->sold_qty }} item terjual</p>
                    @else
                        <p class="mt-1 text-sm font-semibold text-violet-700">Belum ada data lunas</p>
                    @endif
                </div>
            </div>

            <div class="mx-4 sm:mx-0 mb-6 rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 px-4 py-3 bg-slate-50">
                    <h3 class="text-sm font-black text-gray-900">Riwayat harian (≤90 hari dengan penjualan)</h3>
                    <p class="text-[11px] text-gray-500 mt-0.5">GMV dari item Anda di pesanan yang sudah lunas, dikelompokkan per tanggal bayar.</p>
                </div>
                @if($dailyGmvs->isEmpty())
                    <p class="px-4 py-8 text-center text-sm text-gray-500">Belum ada penjualan lunas di data terakhir.</p>
                @else
                    <div class="max-h-72 overflow-y-auto">
                        <table class="w-full text-sm">
                            <thead class="sticky top-0 bg-white text-[10px] font-bold uppercase text-gray-400 border-b">
                                <tr>
                                    <th class="text-left px-4 py-2">Tanggal</th>
                                    <th class="text-right px-4 py-2">GMV</th>
                                    <th class="text-right px-4 py-2">Pesanan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($dailyGmvs as $row)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-2 font-mono text-xs">{{ $row['date'] }}</td>
                                        <td class="px-4 py-2 text-right font-bold text-emerald-700">Rp {{ number_format($row['gmv'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 text-right text-gray-600">{{ $row['paid_orders_count'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="px-4 sm:px-0 mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-bold text-gray-900">Daftar antrean</h3>
                    @if(($ordersDateFilterActive ?? false) && ($filterHint ?? ''))
                        <p class="text-xs text-emerald-700 font-semibold">Filter aktif · {{ $filterHint }}</p>
                    @elseif(!($ordersDateFilterActive ?? false))
                        <p class="text-xs text-gray-500">Semua tanggal pesanan</p>
                    @endif
                </div>
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full w-fit">{{ $incomingOrders->count() }} item</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 px-4 sm:px-0">
                @forelse($incomingOrders as $item)
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 flex flex-col">

                    <div class="bg-gray-50 px-4 py-3 border-b flex justify-between gap-3 items-start">
                        <div class="min-w-0">
                            <p class="text-xs text-gray-500">No. Order</p>
                            <p class="font-black text-gray-800">{{ $item->order->order_number }}</p>
                        </div>
                        <div class="text-right shrink-0 text-[11px] leading-snug font-semibold text-gray-700">
                            <span class="block text-gray-500 font-normal">Dipesan</span>
                            <span>{{ $item->order->created_at->format('d/m/Y') }}</span><br>
                            <span>{{ $item->order->created_at->format('H:i') }} WIB</span>
                            @if($item->order->isPaid() && $item->order->payment_paid_at)
                                <span class="block text-gray-500 font-normal mt-1 pt-1 border-t border-gray-200">Lunas</span>
                                <span>{{ $item->order->payment_paid_at->format('d/m/Y') }}</span><br>
                                <span>{{ $item->order->payment_paid_at->format('H:i') }} WIB</span>
                            @endif
                        </div>
                    </div>

                    <div class="px-4 pt-3">
                        @if($item->order->isPaid())
                            <span class="inline-block text-[10px] font-black uppercase tracking-wide bg-green-100 text-green-800 px-2 py-1 rounded-md mb-2">Pembayaran lunas</span>
                        @else
                            <span class="inline-block text-[10px] font-black uppercase tracking-wide bg-amber-100 text-amber-900 px-2 py-1 rounded-md mb-2">Menunggu pembayaran</span>
                        @endif
                    </div>

                    <div class="p-4 flex-grow">
                        <p class="text-sm text-gray-600 mb-2">Pemesan: <strong class="text-gray-900">{{ $item->order->customer_name }}</strong></p>

                        <div class="flex items-center gap-3 bg-blue-50 p-3 rounded-xl mb-3">
                            <div class="bg-blue-600 text-white font-black w-8 h-8 flex items-center justify-center rounded-lg">
                                {{ $item->quantity }}x
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 leading-tight">{{ $item->product?->name ?? 'Produk' }}</p>
                                <p class="text-xs text-blue-600 font-semibold">Total: Rp {{ number_format($item->price_at_time * $item->quantity, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mb-1">Status barang (untuk pembeli)</p>
                        <p class="text-sm font-bold text-gray-800">{{ $item->deliveryLabel() }}</p>
                    </div>

                    <div class="p-4 border-t bg-gray-50 space-y-2">
                        @if(!$item->order->isPaid())
                            <p class="text-xs text-center text-gray-500 py-2">Penjual bisa menandai siap setelah pembayaran lunas.</p>
                        @elseif($item->delivery_status === \App\Models\OrderItem::DELIVERY_PENDING)
                            <form method="POST" action="{{ route('umkm.order-items.update', $item) }}">
                                @csrf
                                @method('PATCH')
                                @if(filled($tanggalMulai ?? null)) <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}"> @endif
                                @if(filled($tanggalSelesai ?? null)) <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}"> @endif
                                <input type="hidden" name="delivery_status" value="{{ \App\Models\OrderItem::DELIVERY_READY }}">
                                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded-xl text-sm transition">
                                    Tandai siap diambil
                                </button>
                            </form>
                        @elseif($item->delivery_status === \App\Models\OrderItem::DELIVERY_READY)
                            <form method="POST" action="{{ route('umkm.order-items.update', $item) }}">
                                @csrf
                                @method('PATCH')
                                @if(filled($tanggalMulai ?? null)) <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}"> @endif
                                @if(filled($tanggalSelesai ?? null)) <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}"> @endif
                                <input type="hidden" name="delivery_status" value="{{ \App\Models\OrderItem::DELIVERY_PICKED_UP }}">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-xl text-sm transition">
                                    Tandai sudah diambil pembeli
                                </button>
                            </form>
                            <form method="POST" action="{{ route('umkm.order-items.update', $item) }}" class="pt-1">
                                @csrf
                                @method('PATCH')
                                @if(filled($tanggalMulai ?? null)) <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}"> @endif
                                @if(filled($tanggalSelesai ?? null)) <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}"> @endif
                                <input type="hidden" name="delivery_status" value="{{ \App\Models\OrderItem::DELIVERY_PENDING }}">
                                <button type="submit" class="w-full text-xs text-gray-500 hover:text-gray-700 py-1">
                                    Batalkan status siap (kembali ke disiapkan)
                                </button>
                            </form>
                        @else
                            <p class="text-center text-sm font-bold text-green-700 py-2">✅ Sudah diambil</p>
                            <form method="POST" action="{{ route('umkm.order-items.update', $item) }}">
                                @csrf
                                @method('PATCH')
                                @if(filled($tanggalMulai ?? null)) <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}"> @endif
                                @if(filled($tanggalSelesai ?? null)) <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}"> @endif
                                <input type="hidden" name="delivery_status" value="{{ \App\Models\OrderItem::DELIVERY_READY }}">
                                <button type="submit" class="w-full text-xs text-gray-500 hover:text-gray-700 py-1">
                                    Ubah kembali ke siap diambil (koreksi)
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
                @empty
                <div class="col-span-full text-center py-10 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <span class="text-4xl block mb-2">📭</span>
                    @if(($ordersDateFilterActive ?? false) && ($filterHint ?? ''))
                        <p class="text-gray-600 font-medium px-4">Belum ada item pesanan di rentang tanggal ini ({{ $filterHint }}).</p>
                        <a href="{{ route('dashboard') }}" class="inline-block mt-3 text-sm font-bold text-emerald-600 hover:underline">Tampilkan semua pesanan</a>
                    @else
                        <p class="text-gray-500 font-medium">Belum ada pesanan masuk untuk toko Anda.</p>
                    @endif
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
