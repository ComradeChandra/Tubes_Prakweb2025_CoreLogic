@extends('layouts.admin')

@section('title', 'Kelola Kategori Unit')

@section('content')
<div class="space-y-6">

    {{-- 
        [HEADER PAGE]
        Judul Halaman + Tombol "Tambah Kategori"
    --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Kelola Kategori Unit</h1>
            <p class="mt-1 text-sm text-gray-400">
                Kategori digunakan untuk mengelompokkan unit keamanan berdasarkan jenisnya
            </p>
        </div>

        {{-- Tombol Create (Warna Merah Khas CoreLogic) --}}
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition shadow-lg shadow-red-900/50">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Tambah Kategori Baru
            </a>
        </div>
    </div>

    {{-- 
        [TABEL DATA]
        Wadah utama buat nampilin list kategori.
        Pake overflow-x-auto biar bisa discroll samping kalo di HP.
    --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">

                {{-- Header Tabel --}}
                <thead class="text-xs text-gray-400 uppercase bg-gray-900 border-b border-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-16 text-center">No</th>
                        <th scope="col" class="px-6 py-3">Nama Kategori</th>
                        <th scope="col" class="px-6 py-3">Jumlah Unit</th>
                        <th scope="col" class="px-6 py-3 text-center w-48">Aksi</th>
                    </tr>
                </thead>

                {{-- Body Tabel --}}
                <tbody>
                    {{-- 
                        @forelse: Loop cerdas.
                        Kalau ada datanya -> Jalanin loop.
                        Kalau kosong -> Jalanin @empty.
                    --}}
                    @forelse($categories as $category)
                        <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-750 transition duration-200">

                            {{-- 1. NOMOR URUT (Pake loop iteration biar otomatis) --}}
                            <td class="px-6 py-4 text-center font-medium text-gray-500">
                                {{ $loop->iteration }}
                            </td>

                            {{-- 2. NAMA KATEGORI --}}
                            <td class="px-6 py-4 font-bold text-white text-lg tracking-wide">
                                {{ $category->name }}
                            </td>

                            {{-- 3. JUMLAH UNIT (Relasi ke tabel services) --}}
                            <td class="px-6 py-4">
                                {{-- Logic: Cek dulu ada hitungan otomatis gak? Kalo gak ada, hitung manual --}}
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-700 text-gray-300 border border-gray-600">
                                    {{ $category->services_count ?? $category->services->count() }} Unit
                                </span>
                            </td>

                            {{-- 4. TOMBOL AKSI (Edit & Hapus) --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    
                                    {{-- Tombol Edit (Biru) --}}
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                       class="font-medium text-blue-500 hover:text-blue-400 transition-colors">
                                        Edit
                                    </a>

                                    {{-- Tombol Hapus (Merah) --}}
                                    <form id="delete-form-{{ $category->id }}" 
                                          action="{{ route('admin.categories.destroy', $category->id) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        
                                        {{-- Pake JavaScript confirmDelete biar gak kepencet --}}
                                        <button type="button" 
                                                onclick="confirmDelete('delete-form-{{ $category->id }}', '{{ $category->name }}')"
                                                class="font-medium text-red-500 hover:text-red-400 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    {{-- KONDISI KOSONG (Empty State) --}}
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    {{-- Icon Folder Kosong --}}
                                    <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-400">Belum Ada Kategori</p>
                                    <p class="mt-1 text-sm">
                                        Data masih kosong, silakan tambah kategori dulu.
                                    </p>
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

{{-- 
========== CATATAN LOGIKA  ==========

1. PERUBAHAN UTAMA ($loop->iteration):
   Tadi Firda pake `$index + 1`, urg ganti jadi `$loop->iteration`.
   Kenapa? Karena ini fitur bawaan Blade Laravel yang lebih aman.
   Kalau nanti kita pake Pagination (Halaman 1, 2, 3), `$index` bakal reset jadi 0 lagi di halaman 2.
   Tapi `$loop->iteration` selalu ngitung urut 1, 2, 3... dst.

2. LOGIKA JUMLAH UNIT:
   Liat baris: `{{ $category->services_count ?? $category->services->count() }}`
   - `services_count`: Ini data titipan dari Controller (kalau pake withCount).
   - `??`: Artinya "ATAU".
   - `services->count()`: Hitung manual kalau data titipan gak ada.
   Ini namanya "Defensive Programming", biar gak error merah kalau controller lupa ngirim data count.

3. LOGIKA DELETE (JavaScript):
   Di tombol hapus, urg tetep pake `onclick="confirmDelete(...)"`.
   Fungsi ini ada di file `layouts/admin.blade.php`.
   Jadi pas diklik, dia bakal nahan form-nya -> Munculin Popup Alert -> Kalau Yes baru dikirim ke server.

4. UI/UX:
   Urg rapihin styling tombol "Tambah" pake shadow merah (`shadow-red-900/50`) biar kerasa 'mahal' dan taktis sesuai tema CoreLogic.
--}}