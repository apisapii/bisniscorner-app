<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Pesanan Masuk
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-md mx-auto sm:max-w-7xl sm:px-6 lg:px-8">
            
            <div class="px-4 sm:px-0 mb-4 flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Daftar Antrean</h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $incomingOrders->count() }} Pesanan
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

                    <div class="p-4 flex-grow">
                        <p class="text-sm text-gray-600 mb-2">Pemesan: <strong class="text-gray-900">{{ $item->order->customer_name }}</strong></p>
                        
                        <div class="flex items-center gap-3 bg-blue-50 p-3 rounded-xl mb-3">
                            <div class="bg-blue-600 text-white font-black w-8 h-8 flex items-center justify-center rounded-lg">
                                {{ $item->quantity }}x
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 leading-tight">{{ $item->product->name }}</p>
                                <p class="text-xs text-blue-600 font-semibold">Total: Rp {{ number_format($item->price_at_time * $item->quantity, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-t bg-gray-50">
                        @if($item->delivery_status == 'pending')
                            <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded-xl text-sm transition">
                                Tandai Siap Diambil
                            </button>
                        @else
                            <button disabled class="w-full bg-green-100 text-green-700 font-bold py-2 rounded-xl text-sm opacity-70 cursor-not-allowed">
                                ✅ Sudah Selesai
                            </button>
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