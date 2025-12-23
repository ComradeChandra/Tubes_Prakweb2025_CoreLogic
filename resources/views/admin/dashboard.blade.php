{{-- 
    EXTENDS LAYOUT:
    Urg ambil kerangka dari 'layouts.admin'.
    Jadi Sidebar & Navbar udah otomatis kepanggil, tinggal isi kontennya aja.
--}}
@extends('layouts.admin')

{{-- JUDUL HALAMAN --}}
@section('title', 'Dashboard Overview')

{{-- AREA KONTEN UTAMA --}}
@section('content')

<div class="space-y-6">
    

    {{-- 1. WELCOME BANNER (INFO USER LOGIN)        --}}
    
    <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-3xl font-bold text-white mb-2">DASHBOARD OVERVIEW</h2>
            <p class="text-gray-400">
                Welcome back, 
                {{-- Panggil nama user yang lagi login saat ini --}}
                <span class="text-red-500 font-bold">{{ Auth::user()->name }}</span>.
                Here is your daily operational summary for CoreLogic Services.
            </p>
        </div>
        {{-- Aksen background merah transparan di kanan biar gak flat --}}
        <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-red-900/20 to-transparent pointer-events-none"></div>
    </div>

    {{-- 2. GRID STATISTIK (DATA REALTIME)          --}}
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- CARD 1: TOTAL ACTIVE SERVICES --}}
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg flex items-center hover:border-blue-500/50 transition duration-300">
            <div class="p-3 rounded-full bg-blue-900/30 text-blue-400 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Total Active Services</p>
                {{-- LOGIKA: Hitung total baris di tabel services --}}
                <p class="text-2xl font-bold text-white">
                    {{ $totalServices }}
                </p>
            </div>
        </div>

        {{-- CARD 2: TOTAL CATEGORIES --}}
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg flex items-center hover:border-green-500/50 transition duration-300">
            <div class="p-3 rounded-full bg-green-900/30 text-green-400 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Service Categories</p>
                {{-- LOGIKA: Hitung total baris di tabel categories --}}
                <p class="text-2xl font-bold text-white">
                    {{ $totalCategories }}
                </p>
            </div>
        </div>

        {{-- CARD 3: PENDING ORDERS (UPDATED BY CHANDRA) --}}
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg flex items-center hover:border-red-500/50 transition duration-300">
            <div class="p-3 rounded-full bg-red-900/30 text-red-500 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Pending Orders</p>
                {{-- LOGIKA: Hitung order yang statusnya PENDING --}}
                <p class="text-2xl font-bold text-white">
                    {{ $pendingOrders }}
                </p>
            </div>
        </div>

        {{-- CARD 4: TOTAL ORDERS (NEW) --}}
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg flex items-center hover:border-purple-500/50 transition duration-300">
            <div class="p-3 rounded-full bg-purple-900/30 text-purple-400 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Total Orders</p>
                <p class="text-2xl font-bold text-white">
                    {{ $totalOrders }}
                </p>
            </div>
        </div>

        {{-- CARD 5: TOTAL USERS (NEW) --}}
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg flex items-center hover:border-yellow-500/50 transition duration-300">
            <div class="p-3 rounded-full bg-yellow-900/30 text-yellow-400 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Total Users</p>
                <p class="text-2xl font-bold text-white">
                    {{ $totalUsers }}
                </p>
            </div>
        </div>

        {{-- CARD 6: TOTAL REVENUE (NEW) --}}
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg flex items-center hover:border-pink-500/50 transition duration-300">
            <div class="p-3 rounded-full bg-pink-900/30 text-pink-400 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Total Revenue</p>
                {{-- FORMAT: Tampilkan dalam format USD --}}
                <p class="text-2xl font-bold text-white">
                    ${{ number_format($totalRevenue, 2) }}
                </p>
            </div>
        </div>
    </div>




    {{-- 3. RECENT ORDERS WIDGET (NEW BY CHANDRA)     --}}
    
    <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-white">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-red-500 hover:text-red-400 underline">View All →</a>
        </div>
        
        @if($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-700">
                        <tr>
                            <th class="pb-3 text-gray-400 font-semibold">Order ID</th>
                            <th class="pb-3 text-gray-400 font-semibold">Customer</th>
                            <th class="pb-3 text-gray-400 font-semibold">Service</th>
                            <th class="pb-3 text-gray-400 font-semibold">Total Price</th>
                            <th class="pb-3 text-gray-400 font-semibold">Status</th>
                            <th class="pb-3 text-gray-400 font-semibold">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr class="border-b border-gray-700/50 hover:bg-gray-700/30 transition">
                            <td class="py-3 text-gray-300">#{{ $order->id }}</td>
                            <td class="py-3 text-white">{{ $order->user->name }}</td>
                            <td class="py-3 text-gray-300">{{ Str::limit($order->service->name, 30) }}</td>
                            <td class="py-3 text-white font-semibold">${{ number_format($order->total_price, 2) }}</td>
                            <td class="py-3">
                                @if($order->status === 'PENDING')
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-900/30 text-yellow-400 border border-yellow-700">PENDING</span>
                                @elseif($order->status === 'APPROVED')
                                    <span class="px-2 py-1 text-xs rounded bg-green-900/30 text-green-400 border border-green-700">APPROVED</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-red-900/30 text-red-400 border border-red-700">REJECTED</span>
                                @endif
                            </td>
                            <td class="py-3 text-gray-400 text-xs">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p>No orders yet</p>
            </div>
        @endif
    </div>


    {{-- 4. QUICK ACTIONS (SHORTCUT MENU)           --}}
   
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Panel Kiri: Tombol Tambah Data --}}
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg">
            <h3 class="text-lg font-bold text-white mb-4">Management Tools</h3>
            <div class="flex flex-col space-y-3">
                <a href="{{ route('admin.orders.index') }}" class="block w-full py-3 px-4 bg-red-700 hover:bg-red-600 text-white rounded text-center transition font-bold border border-red-600 hover:border-red-500">
                    ⚠ VIEW INCOMING ORDERS
                </a>
                <a href="{{ route('admin.services.create') }}" class="block w-full py-3 px-4 bg-gray-700 hover:bg-gray-600 text-white rounded text-center transition border border-gray-600 hover:border-gray-500">
                    + Add New Service
                </a>
                <a href="{{ route('admin.categories.create') }}" class="block w-full py-3 px-4 bg-gray-700 hover:bg-gray-600 text-white rounded text-center transition border border-gray-600 hover:border-gray-500">
                    + Add New Category
                </a>
            </div>
        </div>
        
        {{-- Panel Kanan: Info Kontak Support --}}
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg flex flex-col justify-center items-center text-center">
            <div class="mb-4 text-gray-500">
                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <p class="text-gray-400 mb-2">Need technical assistance?</p>
            <a href="#" class="text-red-500 hover:text-red-400 underline text-sm">Contact IT Support (Aria)</a>
        </div>
    </div>

</div>
@endsection

<?php
/*
========== CATATAN PRIBADI ==========

1. FUNGSI HALAMAN INI:
   Ini halaman landing page buat Admin pas baru login.
   Urg sengaja pisahin dari halaman tabel (CRUD) biar Admin dapet ringkasan data dulu (Overview).

2. LOGIKA DATA STATISTIK:
   Data statistik (Total Services, Categories, Pending Orders) dikirim dari AdminController.
   Ini lebih clean daripada query langsung di View.

3. STRUKTUR & DESAIN:
   - Layout pake Grid (1 kolom di HP, 3 kolom di Desktop).
   - Warna ngikutin tema global: Dark Mode (Gray-800) dengan aksen Merah CoreLogic.
   - Status 'OPERATIONAL' itu hardcoded buat indikator UI aja.
*/
?>