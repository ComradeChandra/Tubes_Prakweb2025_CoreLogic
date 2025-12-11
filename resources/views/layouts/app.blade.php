<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoreLogic Defense Solutions</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    
    <style>
        /* CUSTOM FONT: Chakra Petch
           Font ini bentuknya kotak-kotak futuristik, cocok banget buat tema Militer/Sci-Fi.
           Biar gak bosen pake font default Arial/Roboto terus.
        */
        @import url('https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;700&display=swap');
        body { font-family: 'Chakra Petch', sans-serif; }
    </style>
</head>

<body class="bg-gray-900 text-gray-200">

    <nav class="bg-gray-900 border-b border-red-900 fixed w-full z-20 top-0 start-0">
      <div class="max-w-7xl flex flex-wrap items-center justify-between mx-auto p-4">
        
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <div class="w-8 h-8 bg-red-600 rounded-sm flex items-center justify-center text-white font-bold">CL</div>
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">CoreLogic <span class="text-red-600">Security & Defense Solutions</span></span>
        </a>
        
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse items-center">
            
            {{-- [UPDATE LOGIKA AUTH] --}}
            {{-- Kita ganti tombol statis jadi dinamis pake @auth --}}
            
            @auth
                {{-- SKENARIO 1: SUDAH LOGIN --}}
                <div class="flex items-center gap-4 mr-3 md:mr-0">
                    
                    {{-- Nama User (Disembunyikan di HP biar gak sempit) --}}
                    <div class="hidden md:block text-right mr-3">
                        <div class="text-[10px] text-gray-400 uppercase tracking-widest leading-none mb-1">WELCOME</div>
                        <div class="text-sm font-bold text-white font-mono uppercase leading-none">
                            {{ Auth::user()->name }}
                        </div>
                    </div>

                    {{-- Tombol Logout --}}
                    {{-- Mengarah ke route logout yang udah kita fix di web.php --}}
                    <a href="{{ route('logout') }}" 
                       class="text-white bg-red-900 border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded text-xs px-3 py-2 text-center tracking-widest uppercase">
                        LOGOUT
                    </a>
                </div>
            @else
                {{-- SKENARIO 2: TAMU (BELUM LOGIN) --}}
                <a href="{{ route('login') }}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded-lg text-sm px-4 py-2 text-center">
                    CLIENT LOGIN
                </a>
            @endauth

            <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-400 rounded-lg md:hidden hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>
        </div>
        
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
          <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-700 rounded-lg bg-gray-800 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-gray-900">
            <li><a href="/" class="block py-2 px-3 text-white bg-red-700 md:bg-transparent md:text-red-500 md:p-0" aria-current="page">HOME</a></li>
            <li><a href="/catalog" class="block py-2 px-3 text-gray-300 hover:bg-gray-700 md:hover:bg-transparent md:hover:text-red-500 md:p-0">CATALOG</a></li>
            <li><a href="#" class="block py-2 px-3 text-gray-300 hover:bg-gray-700 md:hover:bg-transparent md:hover:text-red-500 md:p-0">CONTACT CLIENT RELATIONS</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="mt-16">
        @yield('content')
    </main>

    <footer class="bg-gray-800 rounded-lg shadow m-4 border-t border-red-900/30">
        <div class="w-full mx-auto max-w-7xl p-4 md:flex md:items-center md:justify-between">
          <span class="text-sm text-gray-400 sm:text-center">© 2025 <a href="#" class="hover:underline text-red-500">CoreLogic Defense™</a>. All Rights Reserved.</span>
          <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-400 sm:mt-0">
              <li><a href="#" class="hover:underline me-4 md:me-6">Privacy Protocol</a></li>
              <li><a href="#" class="hover:underline">Licensing</a></li>
          </ul>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
</body>
</html>

<?php
/*
========== CATATAN PRIBADI (JANGAN DIHAPUS BIAR GAK LUPA) ==========

Ini file MASTER LAYOUT (app.blade.php).
Ibaratnya ini "Cangkang" atau "Kerangka" website urg.

1. KONSEP BLADE TEMPLATE:
   Urg pake sistem templating Blade-nya Laravel.
   Jadi Navbar sama Footer cukup ditulis SEKALI di sini.
   Halaman lain tinggal pake perintah @extends('layouts.app') terus isi bagian @section('content').
   Efisien banget, gak capek ngedit satu-satu kalau ada perubahan di menu.

2. KENAPA PAKE FLOWBITE?
   Flowbite itu temennya Tailwind. Dia nyediain komponen JS yang siap pake.
   Contoh: Navbar yang bisa nge-collapse jadi hamburger menu pas dibuka di HP.
   Kalau bikin manual pake JS vanilla, pusing coding-nya. Mending pake yang udah jadi.

3. DESAIN DARK MODE:
   Sesuai tema "Private Military Company" (PMC), urg dominan pake warna gelap (Gray-900)
   dan aksen Merah (Red-600/700). Kesannya tegas, berbahaya, tapi profesional.
   Font 'Chakra Petch' juga nambah kesan futuristik/teknikal.

----------------------------------------------------------------------
[UPDATE LOGIKA AUTHENTICATION - BY CHANDRA]
4. NAVBAR PINTAR (@AUTH):
   Urg tambahin logika Blade `@auth ... @else ... @endauth` di bagian tombol kanan.
   - Kalau User LOGIN: Muncul Nama User (Styled as OPERATOR) + Tombol Logout Merah Gelap.
   - Kalau User TAMU: Tetep muncul tombol "CLIENT LOGIN" kayak desain asli.
   
   Ini biar user tau status login mereka tanpa harus nebak-nebak.
*/
?>