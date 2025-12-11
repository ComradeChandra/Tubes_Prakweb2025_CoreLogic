{{--
========== SERVICES EDIT - FORM EDIT UNIT KEAMANAN ==========

FUNGSI FILE INI:
Halaman admin untuk mengedit unit keamanan yang sudah ada.
Form lengkap dengan file upload untuk update gambar unit.

FITUR UTAMA:
1. Form input nama unit (pre-filled, required)
2. Form select kategori (pre-filled, required)
3. Form input harga (pre-filled, required)
4. Form textarea spesifikasi (pre-filled, required)
5. Form file upload gambar (optional - bisa ganti atau tetap pakai lama)
6. Preview gambar existing & preview gambar baru
7. Validation error display (per field)
8. Tombol Cancel & Update
9. CSRF protection & Method spoofing (PUT)

KOMPONEN:
- Header dengan judul
- Form card dengan 5 input fields (pre-filled)
- Current image display + new image preview
- Error messages di bawah setiap input
- Action buttons (Cancel/Update)

DESIGN:
- Dark theme: bg-gray-900, text-gray-100
- Red accent: red-600 (tombol update)
- Form validation: red-500 (error border & text)
- Image preview: rounded, border, comparison
- Responsive: Mobile & Desktop

ROUTE YANG DIPAKE:
- admin.services.edit (GET) -> halaman ini (form terisi data lama)
- admin.services.update (PUT) -> submit form (update DB + upload file)
- admin.services.index (GET) -> redirect setelah sukses/cancel
--}}

@extends('layouts.admin')

@section('title', 'Edit Unit Keamanan')

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- ===== HEADER SECTION ===== --}}
    <div>
        <h1 class="text-2xl font-bold text-white">Edit Unit: {{ $service->name }}</h1>
        <p class="mt-1 text-sm text-gray-400">
            Ubah informasi unit keamanan yang ditawarkan CoreLogic Defense Systems
        </p>
    </div>

    {{-- ===== FORM CARD ===== --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow">
        <div class="p-6">

            {{-- FORM START --}}
            {{--
            Form PUT ke route admin.services.update
            - Method spoofing: @method('PUT')
            - Enctype: multipart/form-data (untuk file upload)
            - Route parameter: $service->id
            --}}
            <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- ===== FIELD: NAMA UNIT ===== --}}
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-300">
                        Nama Unit
                        <span class="text-red-500">*</span>
                    </label>

                    {{--
                    Input nama unit
                    Pre-filled dengan old('name') atau $service->name
                    --}}
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $service->name) }}"
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
                    Pre-selected dengan kategori yang sekarang dipake
                    --}}
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('category_id') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition"
                        required
                    >
                        <option value="" disabled>-- Pilih Kategori --</option>

                        {{--
                        Loop semua kategori
                        Selected kalau:
                        1. Ada validation error -> pakai old('category_id')
                        2. Tidak ada error -> pakai $service->category_id
                        --}}
                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id', $service->category_id) == $category->id ? 'selected' : '' }}
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
                        Harga (Rp)
                        <span class="text-red-500">*</span>
                    </label>

                    {{--
                    Input harga
                    Pre-filled dengan old('price') atau $service->price
                    --}}
                    <input
                        type="number"
                        id="price"
                        name="price"
                        value="{{ old('price', $service->price) }}"
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

                    {{--
                    Textarea deskripsi
                    Pre-filled dengan old('description') atau $service->description
                    --}}
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Jelaskan deskripsi lengkap unit keamanan ini (fitur, kemampuan, teknologi, dll)"
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('description') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition resize-none"
                        required
                    >{{ old('description', $service->description) }}</textarea>

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
                        <option value="" disabled>-- Pilih Status --</option>
                        <option value="available" {{ old('status', $service->status) == 'available' ? 'selected' : '' }}>Available (Tersedia)</option>
                        <option value="deployed" {{ old('status', $service->status) == 'deployed' ? 'selected' : '' }}>Deployed (Sedang Tugas)</option>
                        <option value="maintenance" {{ old('status', $service->status) == 'maintenance' ? 'selected' : '' }}>Maintenance (Perawatan)</option>
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
                        <span class="text-gray-400 text-xs font-normal">(Kosongkan jika tidak ingin mengganti)</span>
                    </label>

                    {{-- CURRENT IMAGE DISPLAY --}}
                    {{--
                    Tampilkan gambar yang sekarang dipake
                    Biar admin bisa lihat gambar lama sebelum diganti
                    --}}
                    @if($service->image)
                        <div class="mb-4">
                            <p class="mb-2 text-sm font-medium text-gray-400">Gambar Saat Ini:</p>
                            <img
                                src="{{ asset('storage/' . $service->image) }}"
                                alt="{{ $service->name }}"
                                class="w-full max-w-md h-64 object-cover rounded-lg border-2 border-gray-600"
                            >
                        </div>
                    @endif

                    {{--
                    File input untuk upload gambar baru
                    - TIDAK required (optional) -> bisa kosong kalau gak mau ganti
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
                    >

                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WebP. Maksimal 2MB. Rasio 16:9 recommended.</p>

                    @error('image')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror

                    {{--
                    NEW IMAGE PREVIEW
                    Tampilkan preview gambar baru yang dipilih user
                    - hidden -> awalnya disembunyikan
                    - Muncul setelah user pilih file baru
                    --}}
                    <div id="image-preview-container" class="mt-4 hidden">
                        <p class="mb-2 text-sm font-medium text-gray-400">Preview Gambar Baru:</p>
                        <img
                            id="image-preview"
                            src="#"
                            alt="Preview"
                            class="w-full max-w-md h-64 object-cover rounded-lg border-2 border-green-600"
                        >
                        <p class="mt-2 text-xs text-green-400">Gambar baru akan menggantikan gambar lama setelah disimpan</p>
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

                    {{-- Tombol Update --}}
                    <button
                        type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition"
                    >
                        Update Unit
                    </button>

                </div>

            </form>
            {{-- FORM END --}}

        </div>
    </div>

</div>
@endsection

{{-- ===== CUSTOM JAVASCRIPT FOR IMAGE PREVIEW ===== --}}
@push('scripts')
<script>
/**
 * FUNCTION: previewImage()
 *
 * Fungsi untuk menampilkan preview gambar baru sebelum upload.
 * Sama seperti di create.blade.php, tapi ada current image juga.
 *
 * Flow:
 * 1. User pilih file baru -> trigger onchange
 * 2. Baca file pakai FileReader
 * 3. Convert ke base64 (data URL)
 * 4. Set src dari img#image-preview (border hijau)
 * 5. Tampilkan preview container
 *
 * Note:
 * - Current image tetap ditampilkan (border abu)
 * - New preview punya border hijau (biar jelas bedanya)
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

1. DATA DARI CONTROLLER:
   File ini expect 2 variables dari ServiceController@edit:
   - $service -> data unit yang sedang diedit
   - $categories -> semua kategori (untuk dropdown)

   Contoh di controller:
   public function edit(Service $service) {
       $categories = Category::all();
       return view('admin.services.edit', compact('service', 'categories'));
   }

2. FORM SUBMISSION:
   Form PUT ke ServiceController@update
   Controller harus handle validation & update (termasuk file):

   public function update(Request $request, Service $service) {
       $validated = $request->validate([
           'name' => 'required|string|max:255',
           'category_id' => 'required|exists:categories,id',
           'price' => 'required|numeric|min:0',
           'specifications' => 'required|string',
           'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // NULLABLE!
       ]);

       // Kalau ada upload gambar baru
       if ($request->hasFile('image')) {
           // Hapus gambar lama
           if ($service->image) {
               Storage::disk('public')->delete($service->image);
           }

           // Upload gambar baru
           $imagePath = $request->file('image')->store('services', 'public');
           $validated['image'] = $imagePath;
       }

       $service->update($validated);

       return redirect()->route('admin.services.index')
           ->with('success', 'Unit berhasil diupdate!');
   }

3. METHOD SPOOFING:
   @method('PUT') -> Laravel method spoofing
   Browser cuma support GET/POST, tapi Laravel butuh PUT untuk update

4. FILE UPLOAD - OPTIONAL:
   Validation: 'image' => 'nullable|image|...'
   - nullable -> boleh kosong (tidak wajib upload)
   - Kalau kosong -> pakai gambar lama (tidak update field image)
   - Kalau ada file baru -> replace gambar lama

5. DELETE OLD IMAGE:
   Sebelum upload gambar baru, hapus dulu gambar lama:
   Storage::disk('public')->delete($service->image);
   Cegah storage penuh karena file lama tidak terhapus

6. PRE-FILLED VALUES:
   old('field', $service->field)
   - Kalau ada validation error -> pakai old('field')
   - Kalau tidak ada error -> pakai $service->field
   - Berlaku untuk semua field (name, category_id, price, specifications)

7. CURRENT IMAGE DISPLAY:
   Tampilkan gambar lama (border abu) di atas file input
   Biar admin bisa lihat dan decide mau ganti atau tidak
   asset('storage/' . $service->image)

8. NEW IMAGE PREVIEW:
   - Border hijau (border-green-600) -> beda dengan current image (gray)
   - Hidden by default -> muncul setelah user pilih file
   - Text hijau: "Gambar baru akan menggantikan..."

9. SELECT PRE-SELECTED:
   {{ old('category_id', $service->category_id) == $category->id ? 'selected' : '' }}
   Kategori yang sekarang dipake otomatis ter-select

10. VALIDATION UNIQUE (DI CONTROLLER):
    Kalau ada field unique (misal: slug, SKU), jangan lupa exception:
    'slug' => 'required|unique:services,slug,' . $service->id

11. HASFILE CHECK:
    $request->hasFile('image') -> cek apakah ada file yang di-upload
    Kalau false -> skip upload, pakai gambar lama

12. STORAGE DISK:
    Storage::disk('public') -> akses storage/app/public
    delete(), store() -> method Laravel Storage facade

13. ERROR HANDLING:
    @error('field') -> validation error per field
    Border dinamis: red kalau error, gray kalau normal

14. CONSISTENCY WITH CREATE:
    Form edit harus konsisten dengan create:
    - Field sama
    - Styling sama
    - Perbedaan: pre-filled values + optional image upload

15. RESPONSIVE:
    max-w-4xl -> limit width form
    max-w-md -> limit width image preview
    object-cover -> crop gambar biar proporsional

16. ACCESSIBILITY:
    - Label dengan for attribute
    - Alt text di current & preview image
    - Helper text (text-xs) untuk instruksi
    - Required indicator (*)

17. SECURITY:
    - @csrf token wajib
    - Server-side validation wajib
    - File MIME type validation
    - Delete old file cegah orphan files
    - Sanitize input (Laravel otomatis)

18. UX IMPROVEMENTS:
    - Tampilkan gambar lama -> user bisa lihat sebelum ganti
    - Preview gambar baru -> user yakin file yang dipilih benar
    - Border color berbeda -> jelas mana lama mana baru
    - Helper text -> instruksi jelas

END OF FILE
--}}
