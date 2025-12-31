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

    {{-- Flash messages (error/success) --}}
    @if(session('error'))
        <div class="mb-4 p-4 text-sm rounded bg-red-800 text-red-100 border border-red-700">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="mb-4 p-4 text-sm rounded bg-green-800 text-green-100 border border-green-700">{{ session('success') }}</div>
    @endif

    <!-- User Profile Card -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl p-6">
        <div class="flex items-start space-x-6">
            <img class="w-24 h-24 rounded-full border-4 border-gray-700" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
            <div class="ml-6">
                <span class="block text-xs text-gray-500 uppercase mb-1">KTP User</span>
                @if($user->id_card_path)
                    <img src="{{ asset('storage/' . $user->id_card_path) }}" alt="KTP" class="w-40 border rounded mb-2">
                    @if(!$user->ktp_verified)
                        <span class="inline-block bg-yellow-900 text-yellow-300 text-xs font-bold px-3 py-1 rounded">Belum Terverifikasi</span>

                        {{-- Admin controls: Verify / Unverify --}}
                        <div class="mt-3 flex gap-2">
                            <!--
                            CATATAN PENGEMBANG (ADMIN VERIFICATION):
                            - Tombol di bawah ini akan memanggil route yang mengubah flag `ktp_verified`.
                            - Setelah tindakan, sistem otomatis membuat notifikasi bagi user.
                            - Pastikan admin sudah memeriksa file KTP secara visual sebelum menekan Verify.
                        -->
                        <form action="{{ route('admin.users.verifyKtp', $user->id) }}" method="POST" onsubmit="return confirm('Mark this user KTP as verified?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 text-xs bg-green-700 hover:bg-green-800 text-white rounded">Verify</button>
                            </form>

                            <form action="{{ route('admin.users.unverifyKtp', $user->id) }}" method="POST" onsubmit="return confirm('Mark this user KTP as unverified?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 text-xs bg-red-700 hover:bg-red-800 text-white rounded">Unverify</button>
                            </form>
                        </div>
                    @else
                        <span class="inline-block bg-green-900 text-green-300 text-xs font-bold px-3 py-1 rounded">Terverifikasi</span>

                        <div class="mt-3">
                            {{-- Allow admin to revoke verification if needed --}}
                            <form action="{{ route('admin.users.unverifyKtp', $user->id) }}" method="POST" onsubmit="return confirm('Revoke verification?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1 text-xs bg-yellow-700 hover:bg-yellow-800 text-white rounded">Revoke Verification</button>
                            </form>
                        </div>
                    @endif
                @else
                    <span class="inline-block bg-red-900 text-red-300 text-xs font-bold px-3 py-1 rounded">Belum upload KTP</span>
                @endif
            </div>
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