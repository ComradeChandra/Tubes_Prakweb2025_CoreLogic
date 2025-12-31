{{--
    Halaman Tambah Unit Baru
    ------------------------
    Form buat nambahin unit dagangan baru.
    Jangan lupa enctype="multipart/form-data" biar bisa upload gambar.
--}}

@extends('layouts.admin')

@section('title', 'Tambah Unit Keamanan Baru')

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white">Tambah Unit Keamanan Baru</h1>
        <p class="mt-1 text-sm text-gray-400">
            Tambahkan unit keamanan yang ditawarkan CoreLogic Security Systems
        </p>
    </div>

    {{-- Form Card --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow">
        <div class="p-6">

            {{-- 
                Form Action ke route store
                Pake enctype karena ada upload file
            --}}
            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Input Nama Unit --}}
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
                        placeholder="Contoh: Personal Bodyguard, VIP Escort, Home Security, dll"
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('name') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition"
                        required
                    >

                    @error('name')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Select Kategori --}}
                <div>
                    <label for="category_id" class="block mb-2 text-sm font-medium text-gray-300">
                        Kategori
                        <span class="text-red-500">*</span>
                    </label>
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
                        placeholder="Jelaskan deskripsi lengkap unit keamanan ini (fitur, kemampuan, teknologi, dll). Sertakan ukuran tim (berapa orang per unit) jika relevan."
                        class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border @error('description') border-red-500 @else border-gray-600 @enderror rounded-lg focus:ring-red-500 focus:border-red-500 transition resize-none"
                        required
                    >{{ old('description') }}</textarea>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="unit_size" class="block mb-2 text-sm font-medium text-gray-300">Unit Size (person per unit)</label>
                        <input type="number" id="unit_size" name="unit_size" min="1" value="{{ old('unit_size', 1) }}" class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border border-gray-600 rounded-lg focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-400 mt-1">Contoh: 1 (satu personel per unit) atau 3 (tiga personel per unit).</p>
                    </div>

                    <div>
                        <label for="unit_description" class="block mb-2 text-sm font-medium text-gray-300">Unit Keterangan Singkat</label>
                        <input type="text" id="unit_description" name="unit_description" value="{{ old('unit_description') }}" placeholder="Mis: 1 unit = 1 personel" class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border border-gray-600 rounded-lg focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-400 mt-1">Teks singkat yang menjelaskan apa yang dimaksud satu unit.</p>
                    </div>
                </div>

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

                {{-- ===== FIELD: THUMBNAIL (FILE UPLOAD WITH PREVIEW) ===== --}}
                <div>
                    <label for="image" class="block mb-2 text-sm font-medium text-gray-300">
                        Thumbnail Unit (Gambar Utama)
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

                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WebP. Maksimal 10MB. Rasio 16:9 recommended.</p>

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
                        <p class="mb-2 text-sm font-medium text-gray-400">Preview Thumbnail:</p>
                        <img
                            id="image-preview"
                            src="#"
                            alt="Preview"
                            class="w-full max-w-md h-64 object-cover rounded-lg border-2 border-gray-600"
                        >
                    </div>
                </div>

                {{-- ===== FIELD: CAROUSEL IMAGES (MULTIPLE UPLOAD) ===== --}}
                <div>
                    <label for="carousel_images" class="block mb-2 text-sm font-medium text-gray-300">
                        Gambar Carousel (Gallery)
                    </label>

                    <input
                        type="file"
                        id="carousel_images"
                        name="carousel_images[]"
                        accept="image/*"
                        multiple
                        onchange="previewCarouselImages(event)"
                        class="block w-full text-sm text-gray-300 border @error('carousel_images') border-red-500 @else border-gray-600 @enderror rounded-lg cursor-pointer bg-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-red-600 file:text-white hover:file:bg-red-700 transition"
                    >

                    <p class="mt-1 text-xs text-gray-500">Bisa pilih banyak gambar sekaligus. Format: JPG, PNG, WebP. Maksimal 10MB per gambar.</p>

                    @error('carousel_images')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror
                    @error('carousel_images.*')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror

                    <div id="carousel-preview-container" class="mt-4 hidden">
                        <p class="mb-2 text-sm font-medium text-gray-400">Preview Carousel:</p>
                        <div id="carousel-preview-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {{-- Preview images will be injected here --}}
                        </div>
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

function previewCarouselImages(event) {
    const input = event.target;
    const container = document.getElementById('carousel-preview-container');
    const grid = document.getElementById('carousel-preview-grid');
    
    grid.innerHTML = ''; // Clear previous previews

    if (input.files && input.files.length > 0) {
        container.classList.remove('hidden');
        
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-32 object-cover rounded-lg border border-gray-600';
                grid.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    } else {
        container.classList.add('hidden');
    }
}
</script>
@endpush
