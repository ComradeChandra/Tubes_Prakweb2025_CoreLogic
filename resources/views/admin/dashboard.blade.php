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
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- CARD 1: TOTAL ACTIVE SERVICES --}}
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg flex items-center hover:border-blue-500/50 transition duration-300">
            <div class="p-3 rounded-full bg-blue-900/30 text-blue-400 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Total Active Services</p>
                {{-- LOGIKA: Hitung total baris di tabel services --}}
                <p class="text-2xl font-bold text-white">
                    {{ \App\Models\Service::count() }}
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
                    {{ \App\Models\Category::count() }}
                </p>
            </div>
        </div>

        {{-- CARD 3: INCOMING ORDERS (UPDATED BY CHANDRA) --}}
        <div class="p-6 bg-gray-800 border border-gray-700 rounded-lg flex items-center hover:border-red-500/50 transition duration-300">
            <div class="p-3 rounded-full bg-red-900/30 text-red-500 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Pending Orders</p>
                {{-- LOGIKA: Hitung order yang statusnya PENDING --}}
                <p class="text-2xl font-bold text-white">
                    {{ \App\Models\Order::where('status', 'PENDING')->count() }}
                </p>
            </div>
        </div>
    </div>


    {{-- 3. QUICK ACTIONS (SHORTCUT MENU)           --}}
   
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
   Di sini urg pake cara `\App\Models\Service::count()` langsung di dalam View.
   Kenapa gak lewat Controller?
   - Karena dashboard ini rutenya simple (Closure Route di web.php).
   - Datanya cuma butuh angka total (Count), jadi query-nya enteng.
   - Lebih praktis daripada bikin Controller baru cuma buat 2 baris kode hitung.

3. STRUKTUR & DESAIN:
   - Layout pake Grid (1 kolom di HP, 3 kolom di Desktop).
   - Warna ngikutin tema global: Dark Mode (Gray-800) dengan aksen Merah CoreLogic.
   - Status 'OPERATIONAL' itu hardcoded buat indikator UI aja.
*/
?>