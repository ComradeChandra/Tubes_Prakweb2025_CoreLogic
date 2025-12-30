@extends('layouts.app')

@section('content')
<section class="bg-gray-900 py-12 min-h-screen">
    <div class="px-4 mx-auto max-w-3xl">
        <h2 class="text-2xl font-extrabold text-white mb-6">Order Detail</h2>
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6">
            <div class="mb-4">
                <span class="font-bold text-gray-400">Order ID:</span> #{{ $order->id }}
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-400">Client:</span> {{ $order->user->name }} ({{ $order->user->email }})
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-400">Service:</span> {{ $order->service->name }}
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-400">Quantity:</span> {{ $order->quantity }}
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-400">Contract Dates:</span> {{ $order->start_date }} - {{ $order->end_date }}
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-400">Address:</span> {{ $order->address }}
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-400">Notes:</span> {{ $order->notes ?? '-' }}
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-400">Total Price:</span> ${{ number_format($order->total_price, 2) }}
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-400">Status:</span> {{ $order->status }}
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-400">User KTP:</span>
                @if($order->user->id_card_path)
                    <img src="{{ asset('storage/' . $order->user->id_card_path) }}" alt="KTP" class="w-64 border rounded mt-2">
                @else
                    <span class="text-red-500">Belum upload KTP</span>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="mt-6 inline-block text-gray-400 hover:text-white">&larr; Back to Orders</a>
    </div>
</section>
@endsection
