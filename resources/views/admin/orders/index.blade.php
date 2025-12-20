@extends('layouts.app')

@section('content')
<section class="bg-gray-900 py-12 min-h-screen">
    <div class="px-4 mx-auto max-w-7xl">
        
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-extrabold text-white">
                INCOMING MISSIONS (ORDERS)
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition">
                &larr; Back to Dashboard
            </a>
        </div>

        <!-- ALERT SUCCESS -->
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-100 rounded-lg bg-green-800 border border-green-600" role="alert">
                <span class="font-bold">SYSTEM UPDATE:</span> {{ session('success') }}
            </div>
        @endif

        <!-- TABLE CONTAINER -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-700">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-400 uppercase bg-gray-800 border-b border-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Client</th>
                        <th scope="col" class="px-6 py-3">Unit / Service</th>
                        <th scope="col" class="px-6 py-3">Contract Date</th>
                        <th scope="col" class="px-6 py-3">Total Price</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="bg-gray-900 border-b border-gray-800 hover:bg-gray-800 transition">
                        <!-- ID -->
                        <td class="px-6 py-4 font-mono text-xs">
                            #{{ $order->id }}
                        </td>

                        <!-- CLIENT INFO -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-white">{{ $order->user->name }}</div>
                            <div class="text-xs">{{ $order->user->email }}</div>
                        </td>

                        <!-- SERVICE INFO -->
                        <td class="px-6 py-4">
                            <div class="font-bold text-red-400">{{ $order->service->name }}</div>
                            <div class="text-xs">Qty: {{ $order->quantity }} Unit(s)</div>
                        </td>

                        <!-- DATE RANGE -->
                        <td class="px-6 py-4 text-xs">
                            <div>Start: {{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') }}</div>
                            <div>End: {{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}</div>
                        </td>

                        <!-- PRICE -->
                        <td class="px-6 py-4 font-bold text-green-400">
                            $ {{ number_format($order->total_price, 2) }}
                        </td>

                        <!-- STATUS BADGE -->
                        <td class="px-6 py-4">
                            @if($order->status == 'PENDING')
                                <span class="bg-yellow-900 text-yellow-300 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-700">
                                    PENDING
                                </span>
                            @elseif($order->status == 'APPROVED')
                                <span class="bg-green-900 text-green-300 text-xs font-medium px-2.5 py-0.5 rounded border border-green-700">
                                    APPROVED
                                </span>
                            @else
                                <span class="bg-red-900 text-red-300 text-xs font-medium px-2.5 py-0.5 rounded border border-red-700">
                                    REJECTED
                                </span>
                            @endif
                        </td>

                        <!-- ACTION BUTTONS -->
                        <td class="px-6 py-4 text-center">
                            @if($order->status == 'PENDING')
                                <div class="flex justify-center gap-2">
                                    <!-- APPROVE FORM -->
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" onsubmit="return confirm('Approve this mission?');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="APPROVED">
                                        <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-900 font-medium rounded-lg text-xs px-3 py-2 text-center">
                                            ✓ Accept
                                        </button>
                                    </form>

                                    <!-- REJECT FORM -->
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" onsubmit="return confirm('Reject this mission?');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="REJECTED">
                                        <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded-lg text-xs px-3 py-2 text-center">
                                            ✕ Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-500 text-xs italic">No Action</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <p class="text-xl">No incoming missions yet.</p>
                            <p class="text-sm">Wait for clients to submit orders.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</section>
@endsection