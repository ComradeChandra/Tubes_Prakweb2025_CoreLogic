{{--
========== CATEGORIES INDEX - HALAMAN DAFTAR KATEGORI ==========

FUNGSI FILE INI:
Halaman admin untuk menampilkan daftar semua kategori unit keamanan.
Admin bisa melihat, menambah, edit, dan hapus kategori.

FITUR UTAMA:
1. Tabel daftar semua kategori (nama, deskripsi, jumlah unit)
2. Tombol Create buat tambah kategori baru
3. Tombol Edit & Delete di setiap row
4. Flash message (sukses/error) dari admin layout
5. Delete confirmation pakai JavaScript dari layout

KOMPONEN:
- Header dengan judul + tombol Create
- Tabel responsif dengan data kategori
- Action buttons (Edit/Delete) di setiap row
- Empty state kalau belum ada data

DESIGN:
- Dark theme: bg-gray-900, text-gray-100
- Red accent: red-600 (tombol & link)
- Font: Chakra Petch (dari layout)
- Responsive: Mobile & Desktop

ROUTE YANG DIPAKE:
- admin.categories.index (GET) -> halaman ini
- admin.categories.create (GET) -> form tambah
- admin.categories.edit (GET) -> form edit
- admin.categories.destroy (DELETE) -> hapus kategori
--}}

@extends('layouts.admin')

@section('title', 'Kelola Kategori Unit')

@section('content')
<div class="space-y-6">

    {{-- ===== HEADER SECTION ===== --}}
    {{--
    Header berisi:
    - Judul halaman
    - Deskripsi singkat
    - Tombol Create di kanan
    --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Kelola Kategori Unit</h1>
            <p class="mt-1 text-sm text-gray-400">
                Kategori digunakan untuk mengelompokkan unit keamanan berdasarkan jenisnya
            </p>
        </div>

        {{-- Tombol Create New Category --}}
        <div class="mt-4 md:mt-0">
            <a
                href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition"
            >
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Tambah Kategori Baru
            </a>
        </div>
    </div>

    {{-- ===== TABLE SECTION ===== --}}
    {{--
    Tabel menampilkan semua kategori dengan kolom:
    1. No - Nomor urut (loop index)
    2. Nama Kategori - Nama kategori
    3. Deskripsi - Deskripsi kategori (truncated kalau terlalu panjang)
    4. Jumlah Unit - Berapa banyak unit di kategori ini (relationship count)
    5. Aksi - Tombol Edit & Delete

    Responsive:
    - Desktop: Tabel penuh
    - Mobile: Tabel bisa scroll horizontal (overflow-x-auto)
    --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow">

        {{-- Wrapper buat responsive scroll --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">

                {{-- TABLE HEADER --}}
                <thead class="text-xs text-gray-400 uppercase bg-gray-900 border-b border-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama Kategori</th>
                        <th scope="col" class="px-6 py-3">Jumlah Unit</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- TABLE BODY --}}
                <tbody>
                    {{--
                    Loop semua kategori dari controller
                    $categories di-pass dari CategoryController@index
                    --}}
                    @forelse($categories as $index => $category)
                        <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-750 transition">

                            {{-- Kolom No (Index) --}}
                            <td class="px-6 py-4 font-medium text-white">
                                {{ $index + 1 }}
                            </td>

                            {{-- Kolom Nama Kategori --}}
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $category->name }}
                            </td>

                            {{-- Kolom Jumlah Unit --}}
                            {{--
                            Hitung jumlah unit yang punya kategori ini
                            Pakai relationship: $category->services()->count()
                            atau kalau di controller udah withCount: $category->services_count
                            --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                    {{ $category->services_count ?? $category->services->count() }} Unit
                                </span>
                            </td>

                            {{-- Kolom Aksi (Edit & Delete) --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">

                                    {{-- Tombol Edit --}}
                                    {{--
                                    Klik tombol edit -> ke halaman edit kategori
                                    Route: admin.categories.edit
                                    Parameter: id kategori
                                    --}}
                                    <a
                                        href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                        Edit
                                    </a>

                                    {{-- Tombol Delete --}}
                                    {{--
                                    Form hapus pakai method DELETE
                                    Konfirmasi dulu pakai JavaScript confirmDelete() dari layout
                                    ID form: delete-form-{id} biar unik tiap row
                                    --}}
                                    <form
                                        id="delete-form-{{ $category->id }}"
                                        action="{{ route('admin.categories.destroy', $category->id) }}"
                                        method="POST"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        {{--
                                        Tombol hapus dikasih onclick confirmDelete()
                                        Parameter:
                                        1. ID form
                                        2. Nama kategori (buat ditampilin di konfirmasi)
                                        --}}
                                        <button
                                            type="button"
                                            onclick="confirmDelete('delete-form-{{ $category->id }}', '{{ $category->name }}')"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    {{--
                    EMPTY STATE
                    Kalau belum ada kategori sama sekali, tampilkan pesan kosong
                    --}}
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16 mb-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-300">Belum Ada Kategori</p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Klik tombol "Tambah Kategori Baru" untuk membuat kategori pertama
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
========== CATATAN UNTUK DEVELOPER ==========

1. DATA DARI CONTROLLER:
   File ini expect variable $categories dari CategoryController@index
   Contoh di controller:
   $categories = Category::withCount('services')->get();
   return view('admin.categories.index', compact('categories'));

2. RELATIONSHIP:
   - $category->services_count -> withCount('services') di controller
   - $category->services->count() -> lazy load (kalau gak ada withCount)

3. DELETE CONFIRMATION:
   Function confirmDelete() udah disediakan di layouts/admin.blade.php
   Parameter: (formId, itemName)
   Alert: "Yakin hapus {itemName}?"

4. FLASH MESSAGES:
   Success/Error message udah ditangani di admin layout
   Controller tinggal return:
   return redirect()->route('admin.categories.index')
       ->with('success', 'Kategori berhasil dihapus!');

5. RESPONSIVE DESIGN:
   - Desktop: Tabel penuh dengan semua kolom
   - Mobile: Tabel bisa di-scroll horizontal (overflow-x-auto)
   - Header flex: Mobile stack vertikal, Desktop horizontal

6. EMPTY STATE:
   @forelse...@empty -> otomatis handle kalau $categories kosong
   Tampilkan icon + pesan friendly biar user ngerti harus ngapain

7. SECURITY:
   - @csrf -> Laravel CSRF protection
   - @method('DELETE') -> Method spoofing buat DELETE request
   - confirmDelete() -> User harus konfirmasi dulu sebelum hapus

8. STYLING CONSISTENCY:
   - Dark theme: bg-gray-800, text-gray-300
   - Red accent: bg-red-600 (tombol create/delete)
   - Blue accent: bg-blue-600 (tombol edit)
   - Hover states: hover:bg-{color}-700
   - Focus rings: focus:ring-4

9. ACCESSIBILITY:
   - scope="col" di th -> screen reader friendly
   - aria-labels bisa ditambahkan kalau perlu
   - Button type="button" buat mencegah form submit ganda

10. PERFORMANCE:
    - withCount('services') di controller -> cegah N+1 query problem
    - Str::limit() -> cegah tabel melebar karena deskripsi panjang

END OF FILE
--}}
