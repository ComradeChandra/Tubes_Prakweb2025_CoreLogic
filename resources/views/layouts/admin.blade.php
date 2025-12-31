<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Token CSRF bawaan Laravel, wajib ada biar form gak error 419 --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Judul halaman, defaultnya Admin Dashboard --}}
    <title>@yield('title', 'Admin Dashboard') - CoreLogic</title>

    {{-- Font chakra petch biar sesuai tema --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Script Tailwind --}}
    @if(app()->environment('local') && !file_exists(public_path('build/manifest.json')))
        <script src="https://cdn.tailwindcss.com"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body { font-family: 'Chakra Petch', sans-serif; }
    </style>
</head>
<body class="h-full bg-gray-900 text-gray-100">

    {{-- Navbar Atas --}}
    <nav class="fixed top-0 z-50 w-full bg-gray-800 border-b border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                
                <div class="flex items-center justify-start">
                    {{-- Tombol Burger buat HP --}}
                    <button id="sidebar-toggle" type="button" class="inline-flex items-center p-2 text-sm text-gray-400 rounded-lg md:hidden hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600">
                        <span class="sr-only">Toggle sidebar</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    {{-- Logo CoreLogic --}}
                    <a href="{{ route('admin.dashboard') }}" class="flex ml-2 md:mr-24">
                        <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap text-white">
                            CORE<span class="text-red-600">LOGIC</span>
                            <span class="ml-2 text-xs font-normal text-gray-400">ADMIN</span>
                        </span>
                    </a>
                </div>

                {{-- Bagian Kanan: Nama User & Logout --}}
                <div class="flex items-center">
                    <div class="flex items-center ml-3">
                        <div class="flex items-center space-x-3">
                            <span class="text-sm font-medium text-gray-300">
                                {{ Auth::user()->name }}
                                <span class="text-xs text-red-500 uppercase">{{ Auth::user()->role }}</span>
                            </span>

                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Sidebar Kiri --}}
    <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-gray-800 border-r border-gray-700 md:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-gray-800">
            <ul class="space-y-2 font-medium">

                {{-- Menu Dashboard --}}
                <li>
                    {{-- 
                        Logic warna tombol:
                        Cek dulu kita lagi di halaman dashboard bukan?
                        Pake request()->routeIs(). Kalau iya kasih warna merah, kalau bukan warna abu biasa.
                    --}}
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center p-2 {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-lg group">
                        <svg class="w-5 h-5 transition duration-75" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                        <span class="ml-3">Dashboard Overview</span>
                    </a>
                </li>

                {{-- Menu Kategori --}}
                <li>
                    <a href="{{ route('admin.categories.index') }}"
                        class="flex items-center p-2 {{ request()->routeIs('admin.categories.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-lg group">
                        <svg class="w-5 h-5 transition duration-75" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                        </svg>
                        <span class="ml-3">Kategori Unit</span>
                    </a>
                </li>

                {{-- Menu Services --}}
                <li>
                    <a href="{{ route('admin.services.index') }}"
                        class="flex items-center p-2 {{ request()->routeIs('admin.services.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-lg group">
                        <svg class="w-5 h-5 transition duration-75" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Unit Keamanan</span>
                    </a>
                </li>

                {{-- Menu Users --}}
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center p-2 {{ request()->routeIs('admin.users.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-lg group">
                        <svg class="w-5 h-5 transition duration-75" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Manage Users</span>
                    </a>
                </li>

                <li class="pt-4 mt-4 space-y-2 border-t border-gray-700">
                    <span class="text-xs font-semibold text-gray-500 uppercase">Shortcut</span>
                </li>

                {{-- Link ke web depan --}}
                <li>
                    <a href="{{ url('/') }}" target="_blank" class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 transition duration-75" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        <span class="ml-3">Lihat Website</span>
                    </a>
                </li>

            </ul>
        </div>
    </aside>

    {{-- Konten Utama Disini --}}
    <div class="p-4 md:ml-64 mt-14">
        <div class="p-4">

            {{-- Cek session buat nampilin alert sukses/gagal --}}
            @if(session('success'))
                <div id="flash-message" class="mb-4 p-4 text-sm text-green-100 bg-green-800 border border-green-600 rounded-lg">
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div id="flash-message" class="mb-4 p-4 text-sm text-red-100 bg-red-800 border border-red-600 rounded-lg">
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Area ini bakal diisi sama konten dari view lain --}}
            @yield('content')

        </div>
    </div>

    {{-- Script JS Sederhana --}}
    <script>
        // Buat buka tutup sidebar di HP
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // Script buat ngilangin alert otomatis (copas dari stackoverflow)
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.style.opacity = '0';
                setTimeout(() => flashMessage.remove(), 500);
            }, 5000);
        }

        // Alert konfirmasi hapus biar gak kepencet
        function confirmDelete(formId, itemName) {
            if (confirm(`Yakin mau hapus "${itemName}"? Gak bisa balik lagi loh datanya.`)) {
                document.getElementById(formId).submit();
            }
        }
    </script>
    
    @stack('scripts')

</body>
</html>

<?php
/*
    Catatan Developer (Chandra):
    
    1. Layout Admin
       Ini file template utama (Master). Semua halaman admin (dashboard, services, category)
       bakal make kerangka ini (extends) biar gak usah copas navbar berkali-kali.

    2. Navigasi Sidebar
       Linknya pake route() biar aman kalo url ganti.
       Warna merah di menu itu pake logika request()->routeIs(), ngecek url sekarang apa.
    
    3. Alert
       Pake session flash message punya laravel. Ilang sendiri pake setTimeout JS di bawah.
*/
?>