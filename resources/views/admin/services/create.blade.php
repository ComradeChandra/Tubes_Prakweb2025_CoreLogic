{{--
========== SERVICES CREATE - FORM TAMBAH UNIT KEAMANAN BARU ==========

FUNGSI FILE INI:
Halaman admin untuk menambahkan unit keamanan baru.
Form lengkap dengan file upload untuk gambar unit.

FITUR UTAMA:
1. Form input nama unit (required)
2. Form select kategori (dropdown, required)
3. Form input harga (required, numeric)
4. Form textarea spesifikasi (required)
5. Form file upload gambar (required, with preview)
6. Validation error display (per field)
7. Image preview sebelum upload
8. Tombol Cancel & Submit
9. CSRF protection

KOMPONEN:
- Header dengan judul
- Form card dengan 5 input fields
- File upload dengan live preview
- Error messages di bawah setiap input
- Action buttons (Cancel/Submit)

DESIGN:
- Dark theme: bg-gray-900, text-gray-100
- Red accent: red-600 (tombol submit)
- Form validation: red-500 (error border & text)
- Image preview: rounded, border
- Responsive: Mobile & Desktop

ROUTE YANG DIPAKE:
- admin.services.create (GET) -> halaman ini (form kosong)
- admin.services.store (POST) -> submit form (save ke DB + upload file)
- admin.services.index (GET) -> redirect setelah sukses/cancel
--}}

@extends('layouts.admin')

@section('title', 'Tambah Unit Keamanan Baru')

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- ===== HEADER SECTION ===== --}}
    <div>
        <h1 class="text-2xl font-bold text-white">Tambah Unit Keamanan Baru</h1>
        <p class="mt-1 text-sm text-gray-400">
            Tambahkan unit keamanan yang ditawarkan CoreLogic Defense Systems
        </p>
    </div>

    {{-- ===== FORM CARD ===== --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow">
        <div class="p-6">

            {{-- FORM START --}}
            {{--
            Form POST ke route admin.services.store
            PENTING: enctype="multipart/form-data" wajib karena ada file upload
            Tanpa enctype ini, file tidak akan ter-upload
            --}}
            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- ===== FIELD: NAMA UNIT ===== --}}
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-300">
                        Nama Unit
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Pasukan Elite Omega, Humvee Armor, M4 Carbine, dll"
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('name') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition"
                        required
                    >

                    @error('name')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== FIELD: KATEGORI (SELECT DROPDOWN) ===== --}}
                <div>
                    <label for="category_id" class="block mb-2 text-sm font-medium text-gray-300">
                        Kategori
                        <span class="text-red-500">*</span>
                    </label>

                    {{--
                    Select dropdown kategori
                    - Loop semua kategori dari $categories (di-pass dari controller)
                    - option value -> category_id (foreign key)
                    - selected -> restore pilihan kalau ada validation error
                    --}}
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('category_id') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition"
                        required
                    >
                        <option value="" disabled selected>-- Pilih Kategori --</option>

                        {{--
                        Loop semua kategori
                        $categories di-pass dari ServiceController@create
                        --}}
                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('category_id')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== FIELD: HARGA ===== --}}
                <div>
                    <label for="price" class="block mb-2 text-sm font-medium text-gray-300">
                        Price (USD)
                        <span class="text-red-500">*</span>
                    </label>

                    {{--
                    Input harga
                    - type="number" -> cuma bisa input angka
                    - step="1" -> integer saja (tanpa desimal)
                    - min="0" -> tidak boleh negatif
                    --}}
                    <input
                        type="number"
                        id="price"
                        name="price"
                        value="{{ old('price') }}"
                        placeholder="Contoh: 150000000"
                        min="0"
                        step="1"
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('price') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition"
                        required
                    >

                    <p class="mt-1 text-xs text-gray-500">Masukkan harga dalam Rupiah tanpa titik atau koma</p>

                    @error('price')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== FIELD: DESKRIPSI ===== --}}
                <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-300">
                        Deskripsi
                        <span class="text-red-500">*</span>
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Jelaskan deskripsi lengkap unit keamanan ini (fitur, kemampuan, teknologi, dll)"
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('description') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition resize-none"
                        required
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== FIELD: STATUS ===== --}}
                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-300">
                        Status
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('status') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition"
                        required
                    >
                        <option value="" disabled {{ old('status') ? '' : 'selected' }}>-- Pilih Status --</option>
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available (Tersedia)</option>
                        <option value="deployed" {{ old('status') == 'deployed' ? 'selected' : '' }}>Deployed (Sedang Tugas)</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance (Perawatan)</option>
                    </select>

                    @error('status')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== FIELD: GAMBAR (FILE UPLOAD WITH PREVIEW) ===== --}}
                <div>
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-300">
                        Gambar Unit
                        <span class="text-red-500">*</span>
                    </label>

                    {{--
                    File input dengan preview
                    - accept="image/*" -> cuma terima file gambar
                    - onchange="previewImage(event)" -> trigger preview function
                    --}}
                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/*"
                        onchange="previewImage(event)"
                        class="block w-full text-sm text-gray-300 border @error('image') border-red-500 @else border-gray-600 @enderror rounded-lg cursor-pointer bg-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-red-600 file:text-white hover:file:bg-red-700 transition"
                        required
                    >

                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WebP. Maksimal 2MB. Rasio 16:9 recommended.</p>

                    @error('image')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror

                    {{--
                    IMAGE PREVIEW
                    Tampilkan preview gambar setelah user pilih file
                    - id="image-preview" -> target untuk preview
                    - hidden -> awalnya disembunyikan, muncul setelah ada gambar
                    --}}
                    <div id="image-preview-container" class="mt-4 hidden">
                        <p class="mb-2 text-sm font-medium text-gray-400">Preview Gambar:</p>
                        <img
                            id="image-preview"
                            src="#"
                            alt="Preview"
                            class="w-full max-w-md h-64 object-cover rounded-lg border-2 border-gray-600"
                        >
                    </div>
                </div>

                {{-- ===== ACTION BUTTONS ===== --}}
                <div class="flex items-center space-x-3 pt-4 border-t border-gray-700">

                    {{-- Tombol Cancel --}}
                    <a
                        href="{{ route('admin.services.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-300 bg-gray-700 rounded-lg hover:bg-gray-600 focus:ring-4 focus:ring-gray-600 transition"
                    >
                        Batal
                    </a>

                    {{-- Tombol Submit --}}
                    <button
                        type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition"
                    >
                        Simpan Unit
                    </button>

                </div>

            </form>
            {{-- FORM END --}}

        </div>
    </div>

</div>
@endsection

{{-- ===== CUSTOM JAVASCRIPT FOR IMAGE PREVIEW ===== --}}
{{--
@push('scripts') -> inject JavaScript ke layouts/admin.blade.php
Function previewImage() untuk menampilkan preview gambar
--}}
@push('scripts')
<script>
/**
 * FUNCTION: previewImage()
 *
 * Fungsi untuk menampilkan preview gambar sebelum upload.
 * Dipanggil saat user memilih file di input type="file"
 *
 * Flow:
 * 1. User pilih file -> trigger onchange
 * 2. Baca file pakai FileReader
 * 3. Convert ke base64 (data URL)
 * 4. Set src dari img#image-preview
 * 5. Tampilkan preview container
 *
 * Parameter:
 * - event: Event object dari input file
 */
function previewImage(event) {
    const input = event.target; // Input file element
    const preview = document.getElementById('image-preview'); // Img element
    const container = document.getElementById('image-preview-container'); // Container div

    // Cek apakah user memilih file
    if (input.files && input.files[0]) {
        const reader = new FileReader(); // FileReader API

        // Event handler saat file selesai dibaca
        reader.onload = function(e) {
            // Set src img dengan data URL (base64)
            preview.src = e.target.result;

            // Tampilkan preview container (remove class 'hidden')
            container.classList.remove('hidden');
        };

        // Baca file sebagai data URL (base64)
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

{{--
========== CATATAN UNTUK DEVELOPER ==========

1. FORM ENCTYPE:
   enctype="multipart/form-data" WAJIB untuk file upload
   Tanpa ini, file tidak akan ter-upload ke server
   Form biasa pakai enctype="application/x-www-form-urlencoded" (default)

2. DATA DARI CONTROLLER:
   File ini expect variable $categories dari ServiceController@create
   Contoh di controller:

   public function create() {
       $categories = Category::all();
       return view('admin.services.create', compact('categories'));
   }

3. FORM SUBMISSION:
   Form POST ke ServiceController@store
   Controller harus validate & save data + file:

   public function store(Request $request) {
       $validated = $request->validate([
           'name' => 'required|string|max:255',
           'category_id' => 'required|exists:categories,id',
           'price' => 'required|numeric|min:0',
           'specifications' => 'required|string',
           'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
       ]);

       // Upload file
       $imagePath = $request->file('image')->store('services', 'public');
       $validated['image'] = $imagePath;

       Service::create($validated);

       return redirect()->route('admin.services.index')
           ->with('success', 'Unit berhasil ditambahkan!');
   }

4. FILE UPLOAD HANDLING:
   - $request->file('image') -> ambil file dari request
   - ->store('services', 'public') -> simpan ke storage/app/public/services
   - Return path: services/filename.jpg
   - Simpan path ini ke database (bukan full URL)

5. STORAGE SYMLINK:
   Jangan lupa buat symbolic link: php artisan storage:link
   Link: public/storage -> storage/app/public
   Biar file bisa diakses dari browser

6. IMAGE PREVIEW:
   - FileReader API (JavaScript) -> baca file di client-side
   - readAsDataURL() -> convert ke base64
   - Set src img -> tampilkan preview
   - Tidak perlu upload dulu ke server

7. SELECT DROPDOWN:
   - Loop $categories dari controller
   - old('category_id') == $category->id -> restore pilihan kalau error
   - option disabled selected -> placeholder "-- Pilih Kategori --"

8. NUMERIC INPUT:
   - type="number" -> cuma bisa angka
   - min="0" -> tidak boleh negatif
   - step="1" -> integer saja (tanpa desimal)

9. VALIDATION ERRORS:
   @error('field_name') -> cek error per field
   Border dinamis: border-red-500 kalau error
   Pesan error muncul di bawah input

10. OLD INPUT VALUES:
    old('name') -> restore nilai kalau validation error
    Select: {{ old('category_id') == $category->id ? 'selected' : '' }}

11. REQUIRED FIELDS:
    - Visual: <span class="text-red-500">*</span>
    - HTML5: required attribute
    - Server-side: validation rules di controller (lebih penting)

12. FILE INPUT STYLING:
    file:mr-4 file:py-2 file:px-4 -> style button "Choose File"
    file:bg-red-600 -> red background (brand color)
    hover:file:bg-red-700 -> hover effect

13. IMAGE CONSTRAINTS:
    - accept="image/*" -> cuma terima gambar
    - Validation: image|mimes:jpeg,png,jpg,webp|max:2048 (2MB)
    - Rasio 16:9 recommended untuk konsistensi UI

14. SECURITY:
    - @csrf -> CSRF token wajib
    - Server-side validation wajib (jangan cuma client-side)
    - File validation: cek MIME type & size di controller
    - Sanitize filename: Laravel otomatis generate random name

15. ACCESSIBILITY:
    - Label dengan for attribute
    - Input dengan id attribute
    - Alt text di preview image
    - Helper text (text-xs text-gray-500)

16. @push('scripts'):
    Inject JavaScript ke stack 'scripts' di layout
    Layout punya @stack('scripts') di bagian bawah
    Jadi script image preview cuma load di halaman ini

END OF FILE
--}}
