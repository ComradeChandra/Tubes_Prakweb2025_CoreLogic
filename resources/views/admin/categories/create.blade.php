{{--
========== CATEGORIES CREATE - FORM TAMBAH KATEGORI BARU ==========

FUNGSI FILE INI:
Halaman admin untuk menambahkan kategori unit keamanan baru.
Form sederhana dengan 2 field: nama dan deskripsi.

FITUR UTAMA:
1. Form input nama kategori (required)
2. Form input deskripsi kategori (required, textarea)
3. Validation error display (per field)
4. Tombol Cancel & Submit
5. CSRF protection

KOMPONEN:
- Header dengan judul
- Form card dengan 2 input fields
- Error messages di bawah setiap input
- Action buttons (Cancel/Submit)

DESIGN:
- Dark theme: bg-gray-900, text-gray-100
- Red accent: red-600 (tombol submit)
- Form validation: red-500 (error border & text)
- Responsive: Mobile & Desktop

ROUTE YANG DIPAKE:
- admin.categories.create (GET) -> halaman ini (form kosong)
- admin.categories.store (POST) -> submit form (save ke DB)
- admin.categories.index (GET) -> redirect setelah sukses/cancel
--}}

@extends('layouts.admin')

@section('title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- ===== HEADER SECTION ===== --}}
    {{--
    Header berisi:
    - Judul halaman
    - Breadcrumb / back link
    --}}
    <div>
        <h1 class="text-2xl font-bold text-white">Tambah Kategori Baru</h1>
        <p class="mt-1 text-sm text-gray-400">
            Buat kategori untuk mengelompokkan unit keamanan berdasarkan jenisnya
        </p>
    </div>

    {{-- ===== FORM CARD ===== --}}
    {{--
    Card berisi form dengan:
    - Input nama kategori
    - Textarea deskripsi
    - Validation errors (tampil kalau ada error dari controller)
    --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow">
        <div class="p-6">

            {{-- FORM START --}}
            {{--
            Form POST ke route admin.categories.store
            Enctype: application/x-www-form-urlencoded (default)
            Tidak perlu enctype multipart karena tidak ada file upload
            --}}
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- ===== FIELD: NAMA KATEGORI ===== --}}
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-300">
                        Nama Kategori
                        <span class="text-red-500">*</span>
                    </label>

                    {{--
                    Input nama kategori
                    - value="{{ old('name') }}" -> restore input kalau ada validation error
                    - required -> HTML5 validation (optional, server-side validation lebih penting)
                    - Error state: border-red-500 kalau ada error
                    --}}
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Personel, Kendaraan, Senjata, dll"
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('name') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition"
                        required
                    >

                    {{--
                    VALIDATION ERROR MESSAGE
                    Tampilkan error khusus untuk field 'name' kalau ada
                    Error di-pass dari controller: $validator->errors()
                    --}}
                    @error('name')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== ACTION BUTTONS ===== --}}
                {{--
                Tombol Cancel & Submit
                Cancel -> kembali ke index (route)
                Submit -> kirim form (type="submit")
                --}}
                <div class="flex items-center space-x-3 pt-4 border-t border-gray-700">

                    {{-- Tombol Cancel --}}
                    {{--
                    Klik cancel -> kembali ke halaman index
                    Tidak perlu konfirmasi karena data belum disimpan
                    --}}
                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-300 bg-gray-700 rounded-lg hover:bg-gray-600 focus:ring-4 focus:ring-gray-600 transition"
                    >
                        Batal
                    </a>

                    {{-- Tombol Submit --}}
                    {{--
                    Type submit -> trigger form submit
                    Background red -> konsisten dengan brand CoreLogic
                    --}}
                    <button
                        type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition"
                    >
                        Simpan Kategori
                    </button>

                </div>

            </form>
            {{-- FORM END --}}

        </div>
    </div>

</div>
@endsection

{{--
========== CATATAN UNTUK DEVELOPER ==========

1. FORM SUBMISSION:
   Form POST ke CategoryController@store
   Controller harus validate & save data:

   public function store(Request $request) {
       $validated = $request->validate([
           'name' => 'required|string|max:255|unique:categories',
           'description' => 'required|string',
       ]);

       Category::create($validated);

       return redirect()->route('admin.categories.index')
           ->with('success', 'Kategori berhasil ditambahkan!');
   }

2. OLD INPUT VALUES:
   old('name') -> restore nilai input kalau ada validation error
   Jadi user gak perlu isi ulang dari awal
   Laravel otomatis flash old input ke session kalau validation gagal

3. VALIDATION ERRORS:
   @error('name') -> cek apakah ada error untuk field 'name'
   $message -> pesan error dari validator
   Controller return back()->withErrors($validator)->withInput();

4. CSRF PROTECTION:
   @csrf -> generate hidden input dengan CSRF token
   Wajib ada di semua form POST/PUT/DELETE
   Kalau tidak ada -> 419 Page Expired error

5. DYNAMIC BORDER COLOR:
   @error('name') border-red-500 @else border-gray-600 @enderror
   Kalau ada error -> border merah
   Kalau normal -> border abu

6. REQUIRED FIELDS:
   <span class="text-red-500">*</span> -> visual indicator
   required attribute -> HTML5 validation (browser-side)
   Server-side validation di controller -> lebih penting (security)

7. PLACEHOLDER TEXT:
   Kasih contoh konkret biar user paham format yang diharapkan
   "Contoh: Personel, Kendaraan, Senjata"

8. TEXTAREA STYLING:
   resize-none -> cegah user resize (opsional, bisa dihapus kalau mau allow resize)
   rows="4" -> tinggi default

9. BUTTON LAYOUT:
   flex items-center space-x-3 -> tombol berjajar horizontal
   Cancel di kiri (gray), Submit di kanan (red)

10. RESPONSIVE:
    max-w-3xl -> limit width form agar tidak terlalu lebar di desktop
    w-full di input -> full width di container-nya

11. ACCESSIBILITY:
    - <label for="name"> -> connect label ke input
    - id di input -> biar bisa diklik via label
    - placeholder -> hint untuk user

12. SECURITY BEST PRACTICES:
    - CSRF token wajib
    - Server-side validation wajib (jangan cuma HTML5)
    - Sanitize input di controller (Laravel otomatis escape di Blade)

END OF FILE
--}}
