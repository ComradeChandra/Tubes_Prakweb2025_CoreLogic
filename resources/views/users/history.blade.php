@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
            <div class="p-6 text-gray-100">
                <h2 class="text-2xl font-bold mb-6 text-red-500 tracking-wider uppercase">My Mission Orders</h2>

                @if($orders->isEmpty())
                    <div class="text-center py-10">
                        <p class="text-gray-400 mb-4">No active missions found.</p>
                        <a href="{{ url('/catalog') }}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-900 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 focus:outline-none">
                            BROWSE CATALOG
                        </a>
                    </div>
                @else
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Order ID</th>
                                    <th scope="col" class="px-6 py-3">Service Unit</th>
                                    <th scope="col" class="px-6 py-3">Duration</th>
                                    <th scope="col" class="px-6 py-3">Total Price</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Notes</th>
                                    <th scope="col" class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700">
                                        <td class="px-6 py-4 font-mono">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-white">
                                            {{ $order->service->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-500">START: {{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-500">END: {{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-green-400">
                                            ${{ number_format($order->total_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($order->status == 'PENDING')
                                                <span class="bg-yellow-900 text-yellow-300 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-yellow-800">PENDING</span>
                                            @elseif($order->status == 'APPROVED')
                                                <span class="bg-green-900 text-green-300 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-green-800">APPROVED</span>
                                            @elseif($order->status == 'REJECTED')
                                                <span class="bg-red-900 text-red-300 text-xs font-medium me-2 px-2.5 py-0.5 rounded border border-red-800">REJECTED</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 italic text-gray-500">
                                            {{ Str::limit($order->notes, 30) ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($order->status == 'APPROVED')
                                                <a href="#" class="font-medium text-blue-500 hover:underline">📄 Invoice</a>
                                            @else
                                                <span class="text-gray-600">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection