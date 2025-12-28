{{--
========== SERVICES INDEX - HALAMAN DAFTAR UNIT KEAMANAN ==========

[CATATAN ORIGINAL FIRDA]
FUNGSI FILE INI:
Halaman admin untuk menampilkan daftar semua unit keamanan (services) yang tersedia.
Admin bisa melihat status, harga, dan melakukan aksi edit/hapus.

FITUR UTAMA:
1. Tabel Unit: Menampilkan visual, nama, kategori, harga, dan status.
2. Status Badge: Indikator visual (READY / DEPLOYED) biar admin tau unit mana yang available.
3. Tombol Aksi: Edit & Delete dengan konfirmasi keamanan.
4. Format Mata Uang: Menggunakan Dollar ($) sesuai standar internasional CoreLogic.

DESIGN SYSTEM:
- Theme: Dark Mode (bg-gray-900) khas CoreLogic REDFOR.
- Accent: Red-600 (Primary Action), Green-400 (Money/Success), Blue-500 (Edit).
- Font: Chakra Petch (Military Style).

--------------------------------------------------------------------------

[UPDATES & CATATAN LOGIKA - BY CHANDRA]
1. PERBAIKAN FORMAT HARGA ($):
   - Urg ganti format Rupiah jadi Dollar ($) biar sesuai tema Internasional 'CoreLogic'.
   - Logic: number_format($service->price, 0, ',', '.')

2. LOGIKA GAMBAR (Storage::url):
   - Tadi pake asset() sebenernya bisa, tapi lebih 'Laravel Way' pake Storage::url().
   - Ini bakal otomatis nyari ke folder 'storage/app/public' yang udah dilink.
   - Jadi kalo kita ganti driver penyimpanan (misal ke AWS S3), kodingan ini gak perlu diubah.

3. STATUS BADGE (READY/DEPLOYED):
   - Urg tambahin logika visual buat kolom Status.
   - Available -> Hijau (READY)
   - Lainnya -> Merah (DEPLOYED)
   - Biar admin bisa scanning status unit dengan cepet.

4. NOMOR TABEL:
   - Urg ganti $index + 1 jadi $loop->iteration.
   - Ini best practice di Blade kalau kita nge-loop data, biar urutannya selalu bener.

5. FIX LAYOUT KATEGORI (UPDATE TERBARU):
   - Urg kasih 'whitespace-nowrap' di badge kategori biar teksnya gak kepotong/turun baris.
   - Urg kasih lebar fix (w-48) di header kategori biar lega.

6. PENAMBAHAN KOLOM DESKRIPSI:
   - Urg tampilin deskripsi singkat pake Str::limit() biar tabel gak kepanjangan tapi tetep informatif.
--}}

@extends('layouts.admin')

@section('title', 'Kelola Unit Keamanan')

@section('content')
<div class="space-y-6">

    {{-- 
        [HEADER SECTION]
        Judul Halaman & Tombol Aksi Utama
    --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Daftar Unit Keamanan</h1>
            <p class="mt-1 text-sm text-gray-400">
                Kelola inventaris unit keamanan, penetapan harga, dan status operasional.
            </p>
        </div>

        {{-- Tombol "Add New Unit" (Create) --}}
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.services.create') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition shadow-lg shadow-red-900/50">
                {{-- Icon Plus --}}
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Add New Unit
            </a>
        </div>
    </div>

    {{-- FORM SEARCH & FILTER --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
        <form id="filterForm" action="{{ route('admin.services.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            {{-- Search Input --}}
            <div class="flex-1">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau deskripsi unit..."
                       class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>

            {{-- Category Filter Dropdown --}}
            <div class="md:w-64">
                <select name="category"
                        id="categoryFilter"
                        onchange="document.getElementById('filterForm').submit()"
                        class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                Search
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('admin.services.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- INFO HASIL PENCARIAN --}}
    @if(request('search') || request('category'))
        <div class="bg-gray-700 border border-gray-600 rounded-lg p-3 text-sm">
            <span class="text-gray-300">
                Menampilkan <span class="font-bold text-white">{{ $services->count() }}</span> hasil
                @if(request('search'))
                    untuk pencarian "<span class="font-bold text-red-400">{{ request('search') }}</span>"
                @endif
                @if(request('category'))
                    @php
                        $selectedCategory = $categories->firstWhere('id', request('category'));
                    @endphp
                    @if($selectedCategory)
                        di kategori "<span class="font-bold text-red-400">{{ $selectedCategory->name }}</span>"
                    @endif
                @endif
            </span>
        </div>
    @endif

    {{--
        [TABLE SECTION]
        Tabel utama yang menampilkan data services.
        Menggunakan 'overflow-x-auto' agar responsif di mobile.
    --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">
                
                {{-- Header Kolom --}}
                <thead class="text-xs text-gray-400 uppercase bg-gray-900 border-b border-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-12 text-center">No</th>
                        <th scope="col" class="px-6 py-3 w-24">Visual</th>
                        <th scope="col" class="px-6 py-3">Nama Unit</th>
                        
                        {{-- UPDATE: Kasih lebar w-48 biar kategori lega --}}
                        <th scope="col" class="px-6 py-3 w-48">Kategori</th>
                        
                        <th scope="col" class="px-6 py-3">Harga / Misi</th>
                        
                        {{-- UPDATE: Kolom Deskripsi Ditambahin --}}
                        <th scope="col" class="px-6 py-3 w-64">Deskripsi</th> 
                        
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- Isi Data (Body) --}}
                <tbody>
                    {{-- 
                        @forelse: Loop yang aman. 
                        Jika data ada -> Loop biasa.
                        Jika data kosong -> Tampilkan blok @empty.
                    --}}
                    @forelse($services as $service)
                        <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-750 transition duration-200">
                            
                            {{-- 1. NOMOR URUT --}}
                            <td class="px-6 py-4 text-center font-medium text-gray-500">
                                {{-- $loop->iteration: Fitur Blade buat nomor urut otomatis (1, 2, 3...) --}}
                                {{ $loop->iteration }}
                            </td>

                            {{-- 2. VISUAL (GAMBAR UNIT) --}}
                            <td class="px-6 py-4">
                                @if($service->image)
                                    {{-- 
                                        Storage::url() -> Mengubah path 'public/services/foto.jpg' jadi URL browser yang valid.
                                        Pastikan sudah 'php artisan storage:link'.
                                    --}}
                                    <img src="{{ Storage::url($service->image) }}" 
                                         class="w-12 h-12 rounded object-cover border border-gray-600" 
                                         alt="Unit Image">
                                @else
                                    {{-- Placeholder jika tidak ada gambar --}}
                                    <div class="w-12 h-12 rounded bg-gray-700 flex items-center justify-center text-xs text-gray-500">
                                        N/A
                                    </div>
                                @endif
                            </td>

                            {{-- 3. NAMA UNIT --}}
                            <td class="px-6 py-4 font-bold text-white text-base">
                                {{ $service->name }}
                            </td>

                            {{-- 4. KATEGORI (UPDATE: whitespace-nowrap) --}}
                            <td class="px-6 py-4">
                                {{-- 
                                    $service->category->name: Mengambil nama kategori dari relasi tabel.
                                    ?? 'Uncategorized': Fallback kalau kategorinya udah dihapus.
                                --}}
                                <span class="bg-gray-700 text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-600 whitespace-nowrap">
                                    {{ $service->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>

                            {{-- 5. HARGA (DOLLAR) --}}
                            <td class="px-6 py-4 font-mono text-green-400 font-bold">
                                {{-- Format angka jadi Dollar (Contoh: $1,500) --}}
                                ${{ number_format($service->price, 0, ',', '.') }}
                            </td>

                            {{-- 6. DESKRIPSI --}}
                            <td class="px-6 py-4 text-gray-400 text-xs italic">
                                {{ Str::limit($service->description, 50) }}
                            </td>

                            {{-- 7. STATUS OPERASIONAL --}}
                            <td class="px-6 py-4 text-center">
                                {{-- Logika Badge Warna --}}
                                @if($service->status == 'available')
                                    <span class="bg-green-900 text-green-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-green-800">
                                        READY
                                    </span>
                                @else
                                    <span class="bg-red-900 text-red-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-red-800">
                                        DEPLOYED
                                    </span>
                                @endif
                            </td>

                            {{-- 8. TOMBOL AKSI --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.services.edit', $service->id) }}" 
                                       class="text-blue-500 hover:text-blue-400 font-medium transition-colors">
                                        Edit
                                    </a>
                                    
                                    {{-- Hapus (Dengan Form & Konfirmasi) --}}
                                    <form id="delete-service-{{ $service->id }}" 
                                          action="{{ route('admin.services.destroy', $service->id) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        
                                        {{-- Panggil fungsi confirmDelete() dari Layout --}}
                                        <button type="button" 
                                                onclick="confirmDelete('delete-service-{{ $service->id }}', '{{ $service->name }}')"
                                                class="text-red-500 hover:text-red-400 font-medium transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Empty State (Kalau data kosong) --}}
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    @if(request('search') || request('category'))
                                        <p class="text-lg font-medium">Tidak ada hasil yang ditemukan</p>
                                        <p class="text-sm mt-1">Coba ubah kata kunci pencarian atau filter kategori</p>
                                    @else
                                        <p class="text-lg font-medium">No tactical units available.</p>
                                        <p class="text-sm mt-1">Sistem belum memiliki data unit. Silakan tambah unit baru.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection