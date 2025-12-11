<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoreLogic Defense Solutions</title>
    
    <!-- 
        TAILWIND CSS:
        Urg pake CDN biar cepet develop-nya.
        Nanti kalau udah fix semua, baru di-build pake Vite biar performa maksimal.
    -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- 
        FLOWBITE:
        Ini library komponen UI tambahan buat Tailwind.
        Urg pake ini buat bikin Navbar yang responsif (bisa di-klik di HP) & komponen interaktif lain.
    -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    
    <style>
        /* 
           CUSTOM FONT: Chakra Petch
           Font ini bentuknya kotak-kotak futuristik, cocok banget buat tema Militer/Sci-Fi.
           Biar gak bosen pake font default Arial/Roboto terus.
        */
        @import url('https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;700&display=swap');
        body { font-family: 'Chakra Petch', sans-serif; }
    </style>
</head>

<!-- Body dikasih warna gelap (gray-900) biar mata gak sakit pas coding malem + sesuai tema Dark Mode -->
<body class="bg-gray-900 text-gray-200">

    <!-- NAVBAR: Fixed di atas (sticky) -->
    <nav class="bg-gray-900 border-b border-red-900 fixed w-full z-20 top-0 start-0">
      <div class="max-w-7xl flex flex-wrap items-center justify-between mx-auto p-4">
        
        <!-- LOGO KIRI -->
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <div class="w-8 h-8 bg-red-600 rounded-sm flex items-center justify-center text-white font-bold">CL</div>
            <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">CoreLogic <span class="text-red-600">Defense Solutions</span></span>
        </a>
        
        <!-- TOMBOL KANAN (LOGIN & HAMBURGER MENU) -->
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            
            <!-- Tombol Login -->
            <a href="{{ route('login') }}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded-lg text-sm px-4 py-2 text-center">
                CLIENT LOGIN
            </a>

            <!-- Tombol Hamburger (Muncul cuma di HP) -->
            <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-400 rounded-lg md:hidden hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>
        </div>
        
        <!-- MENU TENGAH (Home, Catalog, Contact) -->
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
          <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-700 rounded-lg bg-gray-800 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-gray-900">
            <li><a href="/" class="block py-2 px-3 text-white bg-red-700 md:bg-transparent md:text-red-500 md:p-0" aria-current="page">Home</a></li>
            <li><a href="/catalog" class="block py-2 px-3 text-gray-300 hover:bg-gray-700 md:hover:bg-transparent md:hover:text-red-500 md:p-0">Unit Catalog</a></li>
            <li><a href="#" class="block py-2 px-3 text-gray-300 hover:bg-gray-700 md:hover:bg-transparent md:hover:text-red-500 md:p-0">Contact Command</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <!-- mt-16 dikasih biar konten gak ketutupan Navbar yang fixed di atas -->
    <main class="mt-16">
        <!-- 
            @yield('content')
            Ini LUBANG KUNCI-nya.
            Semua halaman lain (Home, Catalog, dll) bakal "disuntikkan" ke sini.
            Jadi kita gak perlu copas navbar & footer di setiap file. Cukup extend layout ini.
        -->
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-800 rounded-lg shadow m-4 border-t border-red-900/30">
        <div class="w-full mx-auto max-w-7xl p-4 md:flex md:items-center md:justify-between">
          <span class="text-sm text-gray-400 sm:text-center">© 2025 <a href="#" class="hover:underline text-red-500">CoreLogic Defense™</a>. All Rights Reserved.</span>
          <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-400 sm:mt-0">
              <li><a href="#" class="hover:underline me-4 md:me-6">Privacy Protocol</a></li>
              <li><a href="#" class="hover:underline">Licensing</a></li>
          </ul>
        </div>
    </footer>

    <!-- Script Flowbite buat interaksi JS (Dropdown, Navbar Toggle, dll) -->
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
*/
?>