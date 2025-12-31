{{--
    Halaman Edit Unit
    -----------------
    Form buat update data unit.
    Pake method PUT (spoofing) karena HTML form cuma support GET/POST.
--}}

@extends('layouts.admin')

@section('title', 'Edit Unit Keamanan')

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white">Edit Unit: {{ $service->name }}</h1>
        <p class="mt-1 text-sm text-gray-400">
            Ubah informasi unit keamanan yang ditawarkan CoreLogic Security Systems
        </p>
    </div>

    {{-- Form Card --}}
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow">
        <div class="p-6">

            {{-- 
                Form Action ke route update
                Jangan lupa @method('PUT')
            --}}
            <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Input Nama Unit --}}
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-300">
                        Nama Unit
                        <span class="text-red-500">*</span>
                    </label>

                    {{-- Value diambil dari old input (kalo error) atau data database --}}
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $service->name) }}"
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
                        Price (USD)
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
> {{ old('description', $service->description) }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm text-red-400">
                            <span class="font-medium">Error:</span> {{ $message }}
                        </p>
                    @enderror

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="unit_size" class="block mb-2 text-sm font-medium text-gray-300">Unit Size (person per unit)</label>
                        <input type="number" id="unit_size" name="unit_size" min="1" value="{{ old('unit_size', $service->unit_size ?? 1) }}" class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border border-gray-600 rounded-lg focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-400 mt-1">Contoh: 1 (satu personel per unit) atau 3 (tiga personel per unit).</p>
                    </div>

                    <div>
                        <label for="unit_description" class="block mb-2 text-sm font-medium text-gray-300">Unit Keterangan Singkat</label>
                        <input type="text" id="unit_description" name="unit_description" value="{{ old('unit_description', $service->unit_description) }}" placeholder="Mis: 1 unit = 1 personel" class="w-full px-4 py-2.5 text-sm text-gray-100 bg-gray-900 border border-gray-600 rounded-lg focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-400 mt-1">Teks singkat yang menjelaskan apa yang dimaksud satu unit.</p>
                    </div>
                </div>
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

                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WebP. Maksimal 10MB. Rasio 16:9 recommended.</p>

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

                {{-- 
                    ================================================
                    CAROUSEL IMAGES (GALLERY) - ADDED BY CHANDRA
                    ================================================
                    Ini buat upload banyak foto sekaligus buat carousel di halaman detail.
                    Biar gak cuma satu foto doang yang muncul.
                --}}
                <div class="border-t border-gray-700 pt-6">
                    <label class="block mb-2 text-sm font-medium text-gray-300">
                        Gallery / Carousel Images
                        <span class="text-gray-500 text-xs font-normal ml-1">(Optional, bisa banyak)</span>
                    </label>

                    {{-- LIST FOTO CAROUSEL YANG UDAH ADA --}}
                    @if($service->images->count() > 0)
                        <div class="mb-4">
                            <p class="mb-2 text-xs text-gray-400">Foto Gallery Saat Ini:</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($service->images as $img)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="h-24 w-full object-cover rounded border border-gray-600">
                                        
                                        {{-- Tombol Hapus Per Foto --}}
                                        <button 
                                            type="button"
                                            onclick="deleteImage('{{ route('admin.services.image.destroy', $img->id) }}')"
                                            class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded-full hover:bg-red-700 transition opacity-0 group-hover:opacity-100"
                                            title="Hapus Foto Ini"
                                        >
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- INPUT UPLOAD BANYAK FOTO --}}
                    <input
                        type="file"
                        name="carousel_images[]"
                        multiple
                        accept="image/*"
                        onchange="previewCarouselImages(event)"
                        class="block w-full text-sm text-gray-300 border border-gray-600 rounded-lg cursor-pointer bg-gray-900 focus:outline-none focus:ring-red-500 focus:border-red-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-gray-700 file:text-white hover:file:bg-gray-600 transition"
                    >
                    <p class="mt-1 text-xs text-gray-500">Bisa pilih banyak file sekaligus (Ctrl + Click). Max 10MB per file.</p>

                    {{-- PREVIEW CAROUSEL BARU --}}
                    <div id="carousel-preview-container" class="mt-4 hidden">
                        <p class="mb-2 text-xs text-gray-400">Preview Upload Baru:</p>
                        <div id="carousel-preview-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            {{-- Preview images will be injected here by JS --}}
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

    {{-- HIDDEN FORM FOR IMAGE DELETION --}}
    <form id="delete-image-form" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

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

function deleteImage(url) {
    if(confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        const form = document.getElementById('delete-image-form');
        form.action = url;
        form.submit();
    }
}
</script>
@endpush

{{-- 
    ============================================================================
    CATATAN PRIBADI (CHANDRA)
    ============================================================================
    
    1. FITUR BARU: MULTIPLE UPLOAD
       - Gw tambahin input `carousel_images[]` biar bisa upload banyak file sekaligus.
       - Pake `multiple` attribute di input file.
       - Di controller udah di-handle pake loop `foreach`.

    2. PREVIEW GAMBAR:
       - Ada JS dikit di bawah (`previewCarouselImages`) biar admin bisa liat gambar apa aja yang mau diupload.
       - Biar gak salah upload foto kucing lagi.

    3. HAPUS GAMBAR:
       - Tiap foto di galeri ada tombol silang (X) merah.
       - Itu bakal nembak ke route `admin.services.image.destroy`.
       - Pake `confirm()` dulu biar gak kepencet gak sengaja.

    4. NOTE BUAT DIRI SENDIRI:
       - Jangan lupa jalanin `php artisan storage:link` kalo gambar gak muncul.
       - Kalo mau nambah validasi ukuran file, cek di ServiceController.php.
--}}
