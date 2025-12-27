@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">User Details</h1>
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white">
            &larr; Back to List
        </a>
    </div>

    <!-- User Profile Card -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
        <div class="flex items-start space-x-6">
            <img class="w-24 h-24 rounded-full border-4 border-gray-700" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-gray-400">{{ $user->email }}</p>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Username</span>
                        <span class="text-white">{{ $user->username }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Role</span>
                        <span class="text-white">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">NIK</span>
                        <span class="text-white">{{ $user->nik ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Phone</span>
                        <span class="text-white">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="block text-xs text-gray-500 uppercase">Address</span>
                        <span class="text-white">{{ $user->address ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="mb-2">
                    <span class="block text-xs text-gray-500 uppercase">Current Tier</span>
                    <span class="text-xl font-bold text-blue-400">{{ ucfirst($user->tier ?? 'Standard') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 uppercase">Total Spent</span>
                    <span class="text-xl font-bold text-green-400">$ {{ number_format($totalSpent, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-bold text-white">Order History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900 border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-3">Order ID</th>
                        <th class="px-6 py-3">Service</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->orders as $order)
                        <tr class="bg-gray-800 border-b border-gray-700">
                            <td class="px-6 py-4">#{{ $order->id }}</td>
                            <td class="px-6 py-4">{{ $order->service->name }}</td>
                            <td class="px-6 py-4">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">$ {{ number_format($order->total_price, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-bold rounded-full 
                                    {{ $order->status == 'APPROVED' ? 'bg-green-900 text-green-200' : 
                                      ($order->status == 'REJECTED' ? 'bg-red-900 text-red-200' : 'bg-yellow-900 text-yellow-200') }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection