{{--
========== SERVICES INDEX - HALAMAN DAFTAR UNIT KEAMANAN ==========

FUNGSI FILE INI:
Halaman admin untuk menampilkan daftar semua unit keamanan (services).
Admin bisa melihat, menambah, edit, dan hapus unit keamanan.

FITUR UTAMA:
1. Tabel daftar semua unit keamanan (nama, kategori, harga, spesifikasi, gambar)
2. Tombol Create buat tambah unit baru
3. Tombol Edit & Delete di setiap row
4. Preview gambar unit (thumbnail kecil di tabel)
5. Flash message (sukses/error) dari admin layout
6. Delete confirmation pakai JavaScript dari layout

KOMPONEN:
- Header dengan judul + tombol Create
- Tabel responsif dengan data unit keamanan
- Image thumbnail di kolom gambar
- Action buttons (Edit/Delete) di setiap row
- Empty state kalau belum ada data

DESIGN:
- Dark theme: bg-gray-900, text-gray-100
- Red accent: red-600 (tombol & link)
- Font: Chakra Petch (dari layout)
- Responsive: Mobile & Desktop
- Image preview: rounded, object-cover

ROUTE YANG DIPAKE:
- admin.services.index (GET) -> halaman ini
- admin.services.create (GET) -> form tambah
- admin.services.edit (GET) -> form edit
- admin.services.destroy (DELETE) -> hapus unit
--}}

@extends('layouts.admin')

@section('title', 'Kelola Unit Keamanan')

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
            <h1 class="text-2xl font-bold text-white">Kelola Unit Keamanan</h1>
            <p class="mt-1 text-sm text-gray-400">
                Unit keamanan yang ditawarkan CoreLogic Defense Systems
            </p>
        </div>

        {{-- Tombol Create New Service --}}
        <div class="mt-4 md:mt-0">
            <a
                href="{{ route('admin.services.create') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition"
            >
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Tambah Unit Baru
            </a>
        </div>
    </div>

    {{-- ===== TABLE SECTION ===== --}}
    {{--
    Tabel menampilkan semua unit keamanan dengan kolom:
    1. No - Nomor urut
    2. Gambar - Thumbnail preview
    3. Nama Unit - Nama unit keamanan
    4. Kategori - Nama kategori (relationship)
    5. Harga - Harga unit (formatted Rupiah)
    6. Spesifikasi - Spesifikasi singkat (truncated)
    7. Aksi - Tombol Edit & Delete

    Responsive:
    - Desktop: Tabel penuh
    - Mobile: Tabel bisa scroll horizontal
    --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow">

        {{-- Wrapper buat responsive scroll --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">

                {{-- TABLE HEADER --}}
                <thead class="text-xs text-gray-400 uppercase bg-gray-900 border-b border-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Gambar</th>
                        <th scope="col" class="px-6 py-3">Nama Unit</th>
                        <th scope="col" class="px-6 py-3">Kategori</th>
                        <th scope="col" class="px-6 py-3">Harga</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                {{-- TABLE BODY --}}
                <tbody>
                    {{--
                    Loop semua unit keamanan dari controller
                    $services di-pass dari ServiceController@index
                    --}}
                    @forelse($services as $index => $service)
                        <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-750 transition">

                            {{-- Kolom No (Index) --}}
                            <td class="px-6 py-4 font-medium text-white">
                                {{ $index + 1 }}
                            </td>

                            {{-- Kolom Gambar (Thumbnail) --}}
                            {{--
                            Tampilkan thumbnail gambar unit
                            - asset('storage/' . $service->image) -> path ke gambar
                            - w-16 h-16 -> ukuran thumbnail (64px x 64px)
                            - object-cover -> crop gambar biar proporsional
                            - rounded-lg -> border radius
                            --}}
                            <td class="px-6 py-4">
                                @if($service->image)
                                    <img
                                        src="{{ asset('storage/' . $service->image) }}"
                                        alt="{{ $service->name }}"
                                        class="w-16 h-16 object-cover rounded-lg border border-gray-600"
                                    >
                                @else
                                    {{-- Placeholder kalau gambar tidak ada --}}
                                    <div class="w-16 h-16 bg-gray-700 rounded-lg border border-gray-600 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>

                            {{-- Kolom Nama Unit --}}
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $service->name }}
                            </td>

                            {{-- Kolom Kategori --}}
                            {{--
                            Tampilkan nama kategori via relationship
                            $service->category->name
                            Dikasih badge dengan warna accent
                            --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                    {{ $service->category->name }}
                                </span>
                            </td>

                            {{-- Kolom Harga --}}
                            {{--
                            Format harga ke Rupiah
                            number_format($service->price, 0, ',', '.')
                            Contoh: 150000000 -> Rp 150.000.000
                            --}}
                            <td class="px-6 py-4 text-gray-300">
                                Rp {{ number_format($service->price, 0, ',', '.') }}
                            </td>

                            {{-- Kolom Spesifikasi --}}
                            {{--
                            Spesifikasi dipotong (Str::limit) biar gak kepanjangan
                            Full spesifikasi bisa dilihat di halaman edit
                            --}}
                            <td class="px-6 py-4 text-gray-300">
                                {{ Str::limit($service->description, 50) }}
                            </td>

                            {{-- Kolom Aksi (Edit & Delete) --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">

                                    {{-- Tombol Edit --}}
                                    {{--
                                    Klik tombol edit -> ke halaman edit unit
                                    Route: admin.services.edit
                                    Parameter: id unit
                                    --}}
                                    <a
                                        href="{{ route('admin.services.edit', $service->id) }}"
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
                                        id="delete-form-{{ $service->id }}"
                                        action="{{ route('admin.services.destroy', $service->id) }}"
                                        method="POST"
                                        class="inline"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        {{--
                                        Tombol hapus dikasih onclick confirmDelete()
                                        Parameter:
                                        1. ID form
                                        2. Nama unit (buat ditampilin di konfirmasi)
                                        --}}
                                        <button
                                            type="button"
                                            onclick="confirmDelete('delete-form-{{ $service->id }}', '{{ $service->name }}')"
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
                    Kalau belum ada unit sama sekali, tampilkan pesan kosong
                    --}}
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16 mb-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-300">Belum Ada Unit Keamanan</p>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Klik tombol "Tambah Unit Baru" untuk membuat unit pertama
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
   File ini expect variable $services dari ServiceController@index
   Contoh di controller:
   $services = Service::with('category')->get();
   return view('admin.services.index', compact('services'));

2. RELATIONSHIP:
   - $service->category->name -> eager load dengan with('category')
   - Cegah N+1 query problem

3. IMAGE HANDLING:
   - asset('storage/' . $service->image) -> akses file di storage/app/public
   - Pastikan symbolic link udah dibuat: php artisan storage:link
   - Kalau image null -> tampilkan placeholder icon

4. PRICE FORMATTING:
   number_format($service->price, 0, ',', '.')
   - Parameter 1: angka yang mau di-format
   - Parameter 2: jumlah desimal (0 = tanpa desimal)
   - Parameter 3: separator desimal (,)
   - Parameter 4: separator ribuan (.)
   - Contoh output: 150.000.000

5. DELETE CONFIRMATION:
   Function confirmDelete() udah disediakan di layouts/admin.blade.php
   Parameter: (formId, itemName)
   Alert: "Yakin hapus {itemName}?"

6. FLASH MESSAGES:
   Success/Error message udah ditangani di admin layout
   Controller tinggal return:
   return redirect()->route('admin.services.index')
       ->with('success', 'Unit berhasil dihapus!');

7. RESPONSIVE DESIGN:
   - Desktop: Tabel penuh dengan semua kolom
   - Mobile: Tabel bisa di-scroll horizontal (overflow-x-auto)
   - Image thumbnail: fixed size (w-16 h-16) biar konsisten

8. EMPTY STATE:
   @forelse...@empty -> otomatis handle kalau $services kosong
   Colspan="7" -> sesuai jumlah kolom di tabel

9. SECURITY:
   - @csrf -> Laravel CSRF protection
   - @method('DELETE') -> Method spoofing buat DELETE request
   - confirmDelete() -> User harus konfirmasi dulu sebelum hapus

10. IMAGE STYLING:
    - object-cover -> crop gambar biar proporsional (gak distorsi)
    - rounded-lg -> border radius
    - border border-gray-600 -> kasih border tipis
    - Placeholder: background gray + icon kalau image tidak ada

11. BADGE KATEGORI:
    - bg-red-900 text-red-300 -> red badge konsisten dengan theme
    - Bisa diganti warna per kategori kalau mau lebih variatif

12. ACCESSIBILITY:
    - Alt text di image: alt="{{ $service->name }}"
    - Scope="col" di th -> screen reader friendly
    - Semantic HTML (table, thead, tbody)

13. PERFORMANCE:
    - Eager loading: with('category') cegah N+1
    - Image optimization: gunakan thumbnail/resize di production
    - Str::limit() -> cegah tabel melebar

14. CONSISTENCY:
    - Button colors: Blue (edit), Red (delete)
    - Spacing: px-6 py-4 di td
    - Font: Chakra Petch dari layout

END OF FILE
--}}
