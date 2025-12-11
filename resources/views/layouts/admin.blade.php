{{--
========== ADMIN LAYOUT - MASTER TEMPLATE ==========

FUNGSI FILE INI:
Master layout khusus buat halaman Admin Dashboard CoreLogic.
Layout ini dipake buat semua halaman admin (Categories, Services, Users, dll).

KOMPONEN UTAMA:
1. Navbar Admin (dengan nama user + logout)
2. Sidebar Menu (navigasi admin)
3. Main Content Area (tempat konten dinamis)
4. Flash Messages (notifikasi sukses/error)

DESIGN:
- Dark theme konsisten dengan brand CoreLogic
- Warna: Gray-900 (background) + Red-600 (accent)
- Font: Chakra Petch (military/futuristic)
- Responsive: Support mobile & desktop

CARA PAKAI:
Di view admin, extend layout ini:
@extends('layouts.admin')
@section('title', 'Judul Halaman')
@section('content')
    [Konten halaman admin disini]
@endsection
--}}

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - CoreLogic Defense</title>

    {{-- Google Fonts: Chakra Petch (Military Theme) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite Assets (Tailwind CSS) --}}
    {{-- TEMPORARY: Use CDN for quick testing. Run 'npm run build' for production --}}
    @if(app()->environment('local') && !file_exists(public_path('build/manifest.json')))
        <script src="https://cdn.tailwindcss.com"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Custom Admin Styles --}}
    <style>
        body { font-family: 'Chakra Petch', sans-serif; }
    </style>
</head>
<body class="h-full bg-gray-900 text-gray-100">

    {{-- NAVBAR ADMIN (Fixed Top) --}}
    <nav class="fixed top-0 z-50 w-full bg-gray-800 border-b border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                {{-- Logo & Burger Menu --}}
                <div class="flex items-center justify-start">
                    {{-- Mobile Menu Toggle Button --}}
                    <button
                        id="sidebar-toggle"
                        type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-400 rounded-lg md:hidden hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600"
                    >
                        <span class="sr-only">Toggle sidebar</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    {{-- Logo --}}
                    <a href="{{ route('admin.categories.index') }}" class="flex ml-2 md:mr-24">
                        <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap text-white">
                            CORE<span class="text-red-600">LOGIC</span>
                            <span class="ml-2 text-xs font-normal text-gray-400">ADMIN</span>
                        </span>
                    </a>
                </div>

                {{-- User Menu --}}
                <div class="flex items-center">
                    <div class="flex items-center ml-3">
                        <div class="flex items-center space-x-3">
                            {{-- User Name --}}
                            <span class="text-sm font-medium text-gray-300">
                                {{ Auth::user()->name }}
                                <span class="text-xs text-red-500 uppercase">{{ Auth::user()->role }}</span>
                            </span>

                            {{-- Logout Button --}}
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- SIDEBAR MENU --}}
    <aside
        id="sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-gray-800 border-r border-gray-700 md:translate-x-0"
        aria-label="Sidebar"
    >
        <div class="h-full px-3 pb-4 overflow-y-auto bg-gray-800">
            <ul class="space-y-2 font-medium">

                {{-- Menu: Dashboard (Future) --}}
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-400 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 transition duration-75" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                {{-- Menu: Categories --}}
                <li>
                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="flex items-center p-2 {{ request()->routeIs('admin.categories.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-lg group"
                    >
                        <svg class="w-5 h-5 transition duration-75" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                        </svg>
                        <span class="ml-3">Kategori Unit</span>
                    </a>
                </li>

                {{-- Menu: Services --}}
                <li>
                    <a
                        href="{{ route('admin.services.index') }}"
                        class="flex items-center p-2 {{ request()->routeIs('admin.services.*') ? 'bg-red-600 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-lg group"
                    >
                        <svg class="w-5 h-5 transition duration-75" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Unit Keamanan</span>
                    </a>
                </li>

                {{-- Divider --}}
                <li class="pt-4 mt-4 space-y-2 border-t border-gray-700">
                    <span class="text-xs font-semibold text-gray-500 uppercase">Other</span>
                </li>

                {{-- Menu: Back to Website --}}
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

    {{-- MAIN CONTENT AREA --}}
    <div class="p-4 md:ml-64 mt-14">
        <div class="p-4">

            {{-- FLASH MESSAGES (Success/Error) --}}
            {{--
            Flash messages ditampilkan di atas konten utama.
            Messages ini muncul sekali lalu hilang (one-time).

            Success: Background hijau
            Error: Background merah

            Auto-hide setelah 5 detik pakai JavaScript
            --}}
            @if(session('success'))
                <div id="flash-message" class="mb-4 p-4 text-sm text-green-100 bg-green-800 border border-green-600 rounded-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div id="flash-message" class="mb-4 p-4 text-sm text-red-100 bg-red-800 border border-red-600 rounded-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            {{-- DYNAMIC CONTENT AREA --}}
            {{--
            Section 'content' akan diisi oleh view child
            Contoh di categories/index.blade.php:
            @section('content')
                [Tabel kategori disini]
            @endsection
            --}}
            @yield('content')

        </div>
    </div>

    {{-- JAVASCRIPT: Sidebar Toggle & Flash Message Auto-Hide --}}
    <script>
        // ===== SIDEBAR TOGGLE (MOBILE) =====
        // Saat klik burger menu, sidebar slide in/out
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // ===== FLASH MESSAGE AUTO-HIDE =====
        // Flash message hilang otomatis setelah 5 detik
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            setTimeout(function() {
                flashMessage.style.transition = 'opacity 0.5s ease';
                flashMessage.style.opacity = '0';
                setTimeout(function() {
                    flashMessage.remove();
                }, 500);
            }, 5000); // 5000ms = 5 detik
        }

        // ===== DELETE CONFIRMATION =====
        // Fungsi konfirmasi hapus data (dipake di view dengan onclick)
        function confirmDelete(formId, itemName) {
            if (confirm(`Yakin hapus "${itemName}"?\n\nData yang sudah dihapus tidak bisa dikembalikan!`)) {
                document.getElementById(formId).submit();
            }
        }
    </script>

    {{-- ADDITIONAL SCRIPTS --}}
    @stack('scripts')

</body>
</html>

{{--
========== CATATAN UNTUK DEVELOPER ==========

1. SIDEBAR RESPONSIVE:
   Desktop (md+): Sidebar selalu muncul (translate-x-0)
   Mobile: Sidebar tersembunyi (-translate-x-full), muncul saat klik burger

2. ACTIVE MENU HIGHLIGHT:
   Menu aktif dikasih bg-red-600 (merah)
   Pakai helper request()->routeIs('admin.categories.*')
   Tanda * artinya cocok dengan semua route categories (index, create, edit)

3. FLASH MESSAGES:
   Success: session('success') -> green
   Error: session('error') -> red
   Auto-hide setelah 5 detik pakai JavaScript

4. CSRF TOKEN:
   Meta tag csrf-token dipake buat AJAX request (kalau nanti ada)
   Di form biasa, tetap pakai @csrf directive

5. DELETE CONFIRMATION:
   Function confirmDelete() di JavaScript
   Cara pakai di view:
   <button onclick="confirmDelete('delete-form-{{ $id }}', '{{ $name }}')">

6. SECURITY:
   Layout ini cuma bisa diakses kalau user udah login (Auth::user())
   Nanti di routes harus dipake middleware: auth + role:admin

7. EXTENSIBILITY:
   @stack('scripts') -> buat view child bisa inject JavaScript tambahan
   @yield('title') -> dynamic page title

8. DESIGN CONSISTENCY:
   Dark theme: bg-gray-900, text-gray-100
   Accent: red-600 (brand color CoreLogic)
   Font: Chakra Petch (military/futuristic theme)

END OF FILE
--}}
