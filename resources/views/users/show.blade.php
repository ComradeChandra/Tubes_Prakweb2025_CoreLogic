@extends('layouts.app')

@section('content')
<section class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-white mb-4">Order Detail</h2>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-400">Order ID</p>
                        <div class="font-mono text-white">#{{ $order->id }}</div>

                        <p class="text-sm text-gray-400 mt-4">Service</p>
                        <div class="text-white font-bold">{{ $order->service->name }}</div>

                        <p class="text-sm text-gray-400 mt-4">Quantity</p>
                        <div class="text-white">{{ $order->quantity }}</div>

                        <p class="text-sm text-gray-400 mt-4">Contract Dates</p>
                        <div class="text-gray-300">Start: {{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') }}</div>
                        <div class="text-gray-300">End: {{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}</div>

                        {{-- Customer-provided address (dimasukkan saat order) --}}
                        <p class="text-sm text-gray-400 mt-4">Deployment Address</p>
                        <div class="text-gray-300">{{ $order->address ?? '-' }}</div>
                        @if($order->latitude && $order->longitude)
                            <div class="text-xs text-gray-500 mt-1">Coords: {{ $order->latitude }}, {{ $order->longitude }}</div>
                        @endif

                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Price</p>
                        <div class="text-green-400 font-mono">${{ number_format($order->total_price, 2) }}</div>

                        <p class="text-sm text-gray-400 mt-4">Status</p>
                        <div class="mt-1">
                            @if($order->status == 'PENDING')
                                <span class="bg-yellow-900 text-yellow-300 text-xs font-medium px-2 py-1 rounded">PENDING</span>
                            @elseif($order->status == 'APPROVED')
                                <span class="bg-green-900 text-green-300 text-xs font-medium px-2 py-1 rounded">APPROVED</span>
                            @else
                                <span class="bg-red-900 text-red-300 text-xs font-medium px-2 py-1 rounded">REJECTED</span>
                            @endif
                        </div>

                        <p class="text-sm text-gray-400 mt-4">Notes</p>
                        <div class="text-gray-300 italic">{{ $order->notes ?? '-' }}</div>

                        <div class="mt-6 flex gap-3">
                            {{-- Tombol download PDF: selalu tersedia untuk user agar bisa simpan bukti pesanan --}}
                            <a href="{{ route('orders.exportPdf', $order->id) }}" target="_blank" class="bg-blue-700 hover:bg-blue-800 text-white text-sm px-4 py-2 rounded">Download / Print PDF</a>

                            <a href="{{ route('orders.history') }}" class="bg-gray-700 hover:bg-gray-600 text-white text-sm px-4 py-2 rounded">Back to My Orders</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
