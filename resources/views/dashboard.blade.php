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

            <div class="px-4 sm:px-0 mb-4 flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Daftar Antrean</h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $incomingOrders->count() }} item
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 px-4 sm:px-0">
                @forelse($incomingOrders as $item)
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 flex flex-col">

                    <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-500">No. Order</p>
                            <p class="font-black text-gray-800">{{ $item->order->order_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Waktu</p>
                            <p class="text-xs font-bold text-gray-700">{{ $item->created_at->format('H:i') }} WIB</p>
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
                                <input type="hidden" name="delivery_status" value="{{ \App\Models\OrderItem::DELIVERY_READY }}">
                                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded-xl text-sm transition">
                                    Tandai siap diambil
                                </button>
                            </form>
                        @elseif($item->delivery_status === \App\Models\OrderItem::DELIVERY_READY)
                            <form method="POST" action="{{ route('umkm.order-items.update', $item) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="delivery_status" value="{{ \App\Models\OrderItem::DELIVERY_PICKED_UP }}">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-xl text-sm transition">
                                    Tandai sudah diambil pembeli
                                </button>
                            </form>
                            <form method="POST" action="{{ route('umkm.order-items.update', $item) }}" class="pt-1">
                                @csrf
                                @method('PATCH')
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
                                <input type="hidden" name="delivery_status" value="{{ \App\Models\OrderItem::DELIVERY_READY }}">
                                <button type="submit" class="w-full text-xs text-gray-500 hover:text-gray-700 py-1">
                                    Ubah kembali ke siap diambil (koreksi)
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
                @empty
                <div class="col-span-full text-center py-10 bg-white rounded-2xl shadow-sm">
                    <span class="text-4xl block mb-2">📭</span>
                    <p class="text-gray-500 font-medium">Belum ada pesanan masuk hari ini.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
