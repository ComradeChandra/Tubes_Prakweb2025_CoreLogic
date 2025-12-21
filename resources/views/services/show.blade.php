@extends('layouts.app')

@section('content')

{{-- 
    LOGIKA PEMILIHAN GAMBAR
    ----------------------------------------------------
    Mengambil gambar dari database (Carousel + Thumbnail).
--}}
@php
    $displayImages = [];
    
    // 1. Ambil gambar dari Carousel (Gallery)
    foreach($service->images as $img) {
        $displayImages[] = asset('storage/' . $img->image_path);
    }
    
    // 2. Kalau Carousel kosong, pakai Thumbnail utama
    if (empty($displayImages) && $service->image) {
        $displayImages[] = asset('storage/' . $service->image);
    }
    
    // 3. Kalau masih kosong juga, pakai Placeholder default
    if (empty($displayImages)) {
        $displayImages[] = 'https://via.placeholder.com/800x600?text=No+Image+Available';
    }
@endphp

<section class="bg-gray-900 py-16 min-h-screen flex items-center">
    <div class="max-w-7xl mx-auto px-6 w-full">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            <!-- LEFT COLUMN: DETAILS -->
            <div class="space-y-8">
                <div>
                    <span class="bg-red-900 text-red-200 text-xs font-medium px-2.5 py-0.5 rounded border border-red-800 mb-4 inline-block">
                        {{ strtoupper($service->category->name ?? 'SERVICE') }}
                    </span>
                    <h1 class="text-5xl font-extrabold text-white tracking-tight leading-tight">
                        {{ $service->name }}
                    </h1>
                    <div class="flex items-center mt-4 space-x-4">
                        <span class="flex items-center text-green-400 font-semibold">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            {{ strtoupper($service->status) }}
                        </span>
                        <span class="text-gray-500">|</span>
                        <span class="text-3xl text-red-500 font-bold">
                            $ {{ number_format($service->price, 2, '.', ',') }}
                        </span>
                    </div>
                </div>

                <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
                    <h3 class="text-xl font-bold text-white mb-3 border-b border-gray-600 pb-2">Service Details</h3>
                    <p class="text-gray-300 leading-relaxed text-lg">
                        {{ $service->description }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <!-- TOMBOL HIRE NOW (Added by Chandra) -->
                    <!-- Biar user gak usah balik ke katalog dulu buat nyewa -->
                    <a href="{{ url('/services/' . $service->id . '/order') }}"
                       class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white text-lg font-bold px-8 py-4 rounded-lg transition transform hover:scale-105 shadow-red-900/50 shadow-lg">
                        HIRE NOW
                    </a>
                    <a href="/catalog"
                       class="flex-1 text-center bg-gray-700 hover:bg-gray-600 text-white text-lg font-medium px-8 py-4 rounded-lg transition border border-gray-600">
                        ← Back to Catalog
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: CAROUSEL -->
            <div class="relative w-full h-96 lg:h-[500px] bg-gray-800 rounded-2xl overflow-hidden shadow-2xl border border-gray-700 group">
                <!-- Carousel Wrapper -->
                <div id="default-carousel" class="relative w-full h-full" data-carousel="slide">
                    <!-- Carousel wrapper -->
                    <div class="relative h-full overflow-hidden rounded-lg">
                        
                        @foreach($displayImages as $index => $imgUrl)
                        <!-- Item {{ $index + 1 }} -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item="{{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ $imgUrl }}" class="absolute block w-full h-full object-cover" alt="Service Image {{ $index + 1 }}">
                            <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black/90 to-transparent w-full p-6">
                                <p class="text-white font-mono text-sm">
                                    FIG 1.{{ $index + 1 }}: {{ strtoupper($service->name) }} - VIEW {{ $index + 1 }}
                                </p>
                            </div>
                        </div>
                        @endforeach

                    </div>
                    
                    <!-- Slider indicators -->
                    <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                        @foreach($displayImages as $index => $imgUrl)
                            <button type="button" class="w-3 h-3 rounded-full bg-white/50 hover:bg-white" aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}" data-carousel-slide-to="{{ $index }}"></button>
                        @endforeach
                    </div>
                    
                    <!-- Slider controls -->
                    <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                            </svg>
                            <span class="sr-only">Previous</span>
                        </span>
                    </button>
                    <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                            <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="sr-only">Next</span>
                        </span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

{{-- 
    ============================================================================
    CATATAN PRIBADI (CHANDRA) - JANGAN DIHAPUS!
    ============================================================================
    
    1. SOAL GAMBAR CAROUSEL:
       - Akhirnya gw bikin dinamis ambil dari database (`$service->images`).
       - Jadi admin bisa upload foto banyak-banyak di halaman edit.
       - Kalo admin males upload, dia bakal fallback ke thumbnail utama.
       - Kalo thumbnail juga gak ada, yaudah pake placeholder abu-abu biar gak rusak layoutnya.
       - Bye-bye Unsplash ID yang ngaco! (Fiat 500 jadi APC itu aib wkwk).

    2. LOGIKA TAMPILAN:
       - Grid 2 kolom: Kiri buat teks, Kanan buat gambar.
       - Mobile friendly? Aman, bakal jadi tumpuk (stacked) kalo dibuka di HP.
       - Tombol "HIRE NOW" sengaja digedein biar klien langsung klik tanpa mikir panjang.

    3. KENAPA PAKE BLADE PHP DI ATAS?
       - Biar logic-nya gak ngotorin view HTML di bawah.
       - Jadi variabel `$displayImages` udah siap saji tinggal di-looping.
       - Codingan gw makin rapi kan? Lumayan lah buat pemula hehe.
--}}

{{-- 
    ============================================================================
    CATATAN PENGEMBANG (DEV NOTES)
    ============================================================================
    
    1. LOGIKA LAYOUT (GRID SYSTEM):
       - Halaman ini menggunakan CSS Grid dengan 2 kolom pada layar besar (lg:grid-cols-2).
       - Kolom Kiri: Berisi detail teks, harga, status, dan tombol aksi.
       - Kolom Kanan: Berisi Carousel gambar (Flowbite).
       - Pada layar mobile (default), grid akan menjadi 1 kolom (stacked).

    2. LOGIKA CAROUSEL (FLOWBITE):
       - Carousel ini menggunakan library Flowbite JS.
       - Atribut `data-carousel="slide"` mengaktifkan fitur auto-slide.
       - Gambar yang digunakan saat ini adalah placeholder dari Unsplash.
       - Nanti bisa diganti dengan gambar dinamis dari database jika fitur upload gambar sudah ada.
       - Struktur: Wrapper -> Item (Hidden by default) -> Image + Caption.

    3. LOGIKA TOMBOL AKSI:
       - Tombol "HIRE NOW" langsung mengarah ke route order (`/services/{id}/order`).
       - Ini memudahkan user untuk langsung memesan tanpa harus kembali ke katalog.
       - Tombol "Back to Catalog" untuk navigasi standar.

    4. DATA BINDING (BLADE):
       - `{{ $service->name }}`: Menampilkan nama layanan.
       - `{{ $service->price }}`: Harga diformat dengan `number_format` biar ada komanya.
       - `{{ $service->status }}`: Status layanan (Available/Deployed).
       - `{{ $service->category->name }}`: Relasi ke tabel categories. Pake `??` buat jaga-jaga kalo null.

    5. CATATAN PRIBADI:
       - Teks tombol udah diganti jadi lebih manusiawi ("Hire Now" & "Back to Catalog").
       - Komentar "Added by Chandra" udah dibalikin ke posisinya di atas tombol Hire.
       - Jangan lupa jalanin `npm run dev` kalo ubah class Tailwind, tapi karena ini Blade biasa, aman.
--}}
