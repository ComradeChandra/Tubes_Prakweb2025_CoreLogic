{{--
========== CATEGORIES EDIT - FORM EDIT KATEGORI ==========

FUNGSI FILE INI:
Halaman admin untuk mengedit kategori unit keamanan yang sudah ada.
Form sama seperti create, tapi field udah pre-filled dengan data existing.

FITUR UTAMA:
1. Form input nama kategori (pre-filled dengan data lama)
2. Form input deskripsi kategori (pre-filled dengan data lama)
3. Validation error display (per field)
4. Tombol Cancel & Update
5. CSRF protection & Method spoofing (PUT)

KOMPONEN:
- Header dengan judul
- Form card dengan 2 input fields (pre-filled)
- Error messages di bawah setiap input
- Action buttons (Cancel/Update)

DESIGN:
- Dark theme: bg-gray-900, text-gray-100
- Red accent: red-600 (tombol update)
- Form validation: red-500 (error border & text)
- Responsive: Mobile & Desktop

ROUTE YANG DIPAKE:
- admin.categories.edit (GET) -> halaman ini (form terisi data lama)
- admin.categories.update (PUT) -> submit form (update DB)
- admin.categories.index (GET) -> redirect setelah sukses/cancel
--}}

@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- ===== HEADER SECTION ===== --}}
    {{--
    Header berisi:
    - Judul halaman dengan nama kategori yang sedang diedit
    - Info singkat
    --}}
    <div>
        <h1 class="text-2xl font-bold text-white">Edit Kategori: {{ $category->name }}</h1>
        <p class="mt-1 text-sm text-gray-400">
            Ubah informasi kategori unit keamanan
        </p>
    </div>

    {{-- ===== FORM CARD ===== --}}
    {{--
    Card berisi form dengan:
    - Input nama kategori (pre-filled)
    - Textarea deskripsi (pre-filled)
    - Validation errors (tampil kalau ada error dari controller)
    --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow">
        <div class="p-6">

            {{-- FORM START --}}
            {{--
            Form PUT ke route admin.categories.update
            - Method spoofing: @method('PUT') karena browser cuma support GET/POST
            - CSRF protection: @csrf wajib
            - Route parameter: $category->id
            --}}
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- ===== FIELD: NAMA KATEGORI ===== --}}
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-300">
                        Nama Kategori
                        <span class="text-red-500">*</span>
                    </label>

                    {{--
                    Input nama kategori
                    - value: old('name') kalau ada error, fallback ke $category->name
                    - old() -> restore nilai kalau validation gagal
                    - $category->name -> data asli dari database
                    --}}
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        placeholder="Contoh: Personel, Kendaraan, Senjata, dll"
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('name') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition"
                        required
                    >

                    {{-- VALIDATION ERROR MESSAGE --}}
                    @error('name')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ===== ACTION BUTTONS ===== --}}
                {{--
                Tombol Cancel & Update
                Cancel -> kembali ke index tanpa save
                Update -> submit form (update database)
                --}}
                <div class="flex items-center space-x-3 pt-4 border-t border-gray-700">

                    {{-- Tombol Cancel --}}
                    {{--
                    Klik cancel -> kembali ke halaman index
                    Data tidak disimpan
                    --}}
                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-300 bg-gray-700 rounded-lg hover:bg-gray-600 focus:ring-4 focus:ring-gray-600 transition"
                    >
                        Batal
                    </a>

                    {{-- Tombol Update --}}
                    {{--
                    Type submit -> trigger form submit
                    Method PUT (via @method('PUT'))
                    Background red -> konsisten dengan brand CoreLogic
                    --}}
                    <button
                        type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition"
                    >
                        Update Kategori
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

1. DATA DARI CONTROLLER:
   File ini expect variable $category dari CategoryController@edit
   Contoh di controller:

   public function edit(Category $category) {
       return view('admin.categories.edit', compact('category'));
   }

2. FORM SUBMISSION:
   Form PUT ke CategoryController@update
   Controller harus validate & update data:

   public function update(Request $request, Category $category) {
       $validated = $request->validate([
           'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
           'description' => 'required|string',
       ]);

       $category->update($validated);

       return redirect()->route('admin.categories.index')
           ->with('success', 'Kategori berhasil diupdate!');
   }

3. METHOD SPOOFING:
   @method('PUT') -> Laravel method spoofing
   Browser HTML form cuma support GET/POST
   Laravel butuh PUT/PATCH untuk update, DELETE untuk destroy
   Solusi: hidden input _method dengan value "PUT"

4. OLD INPUT WITH DEFAULT:
   old('name', $category->name)
   - Kalau ada validation error -> pakai old('name') (user input terakhir)
   - Kalau tidak ada error -> pakai $category->name (data dari DB)
   - Ini prevent data loss kalau validation gagal

5. VALIDATION UNIQUE WITH EXCEPTION:
   Di controller, validation rule untuk name:
   'unique:categories,name,' . $category->id
   Artinya: name harus unique, tapi ignore kategori yang sedang diedit
   Jadi boleh pakai nama yang sama dengan dirinya sendiri

6. PRE-FILLED VALUES:
   Semua input field harus pre-filled dengan data existing
   - Input: value="{{ old('name', $category->name) }}"
   - Textarea: {{ old('description', $category->description) }} di dalam tag

7. CSRF & METHOD:
   @csrf -> CSRF token (wajib)
   @method('PUT') -> HTTP method spoofing (wajib untuk update)
   Tanpa kedua directive ini -> error 419 atau method not allowed

8. ROUTE MODEL BINDING:
   Route: admin.categories.update
   Parameter: $category->id
   Di controller bisa pakai route model binding:
   public function update(Request $request, Category $category)
   Laravel otomatis find kategori by ID

9. ERROR HANDLING:
   @error('name') -> cek error per field
   Border dinamis: border-red-500 kalau error, border-gray-600 kalau normal
   Pesan error muncul di bawah input field

10. CONSISTENCY WITH CREATE:
    Edit form harus konsisten dengan create form
    Perbedaan cuma:
    - Route & method (update vs store, PUT vs POST)
    - Pre-filled values (ada default dari DB)
    - Button text (Update vs Simpan)

11. RESPONSIVE DESIGN:
    max-w-3xl -> limit width form
    Space-y-6 -> spacing vertikal antar section
    Konsisten dengan create.blade.php

12. ACCESSIBILITY:
    - Label dengan for attribute
    - Input dengan id attribute
    - Required indicator (*)
    - Error messages dengan contrast color yang jelas

END OF FILE
--}}
