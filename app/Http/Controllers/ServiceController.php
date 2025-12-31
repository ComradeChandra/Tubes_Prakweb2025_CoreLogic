<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        // Query builder buat services
        $query = Service::with('category');

        // Filter berdasarkan search keyword (nama atau deskripsi)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Ambil hasil query
        $services = $query->latest()->get();

        // Ambil semua kategori buat dropdown filter
        $categories = Category::orderBy('name')->get();

        return view('admin.services.index', compact('services', 'categories'));
    }

    /**
     * Method khusus buat Halaman Katalog (Frontend Public).
     * Logic-nya mirip index admin, tapi view-nya beda.
     * Urg pisahin biar gak pusing ngurusin permission admin vs user biasa.
     */
    public function publicCatalog(Request $request)
    {
        // 1. Mulai Query Service (Eager load category biar enteng)
        $query = Service::with('category');

        // 2. Cek ada request pencarian gak? (Search Bar)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // 3. Cek ada filter kategori gak? (Dropdown)
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // 4. Eksekusi Query (Tampilin yang statusnya available aja kali ya? Tapi semua dulu deh)
        $services = $query->latest()->get();

        // 5. Ambil data kategori buat isi dropdown filter
        $categories = Category::orderBy('name')->get();

        // 6. Lempar ke view catalog punya Nauval
        return view('catalog', compact('services', 'categories'));
    }

    // Menampilkan form tambah unit baru
    public function create()
    {
        // Ambil semua kategori buat dropdown di form
        $categories = Category::orderBy('name')->get();

        return view('admin.services.create', compact('categories'));
    }

    // Proses simpan data unit baru ke database
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        // Pastikan nama unit unik, harga angka, dan gambar sesuai format
        $validated = $request->validate([
            'name'        => 'required|unique:services,name|min:3|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0|max:9999999999.99',
            'description' => 'required|min:10',
            'unit_size'   => 'nullable|integer|min:1',
            'unit_description' => 'nullable|string|max:255',
            'status'      => 'nullable|in:available,deployed,maintenance',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240', // Max 10MB
            'carousel_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
        ], [
            // Custom error messages biar user ngerti salahnya dimana
            'name.required'        => 'Nama unit wajib diisi.',
            'name.unique'          => 'Nama unit sudah ada. Gunakan nama lain.',
            'name.min'             => 'Nama unit minimal 3 karakter.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak valid.',
            'price.required'       => 'Harga wajib diisi.',
            'price.numeric'        => 'Harga harus berupa angka.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 10 karakter.',
            'status.in'            => 'Status tidak valid.',
            'image.image'          => 'File thumbnail harus berupa gambar.',
            'image.mimes'          => 'Format thumbnail harus JPG, JPEG, PNG, atau WebP.',
            'image.max'            => 'Ukuran thumbnail maksimal 10MB.',
            'carousel_images.*.image' => 'File carousel harus berupa gambar.',
            'carousel_images.*.mimes' => 'Format carousel harus JPG, JPEG, PNG, atau WebP.',
            'carousel_images.*.max'   => 'Ukuran carousel maksimal 10MB.',
        ]);

        // Default status kalau kosong = available
        if (empty($validated['status'])) {
            $validated['status'] = 'available';
        }

        // 2. Upload Thumbnail (Gambar Utama)
        if ($request->hasFile('image')) {
            // Sanitize filename
            $originalName = $request->file('image')->getClientOriginalName();
            $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $request->file('image')->getClientOriginalExtension();
            $filename = time() . '_' . $safeName;
            
            $validated['image'] = $request->file('image')->storeAs('services', $filename, 'public');
        }

        // 3. Bikin Slug otomatis dari nama (contoh: "Unit Alpha" -> "unit-alpha")
        $validated['slug'] = Str::slug($validated['name']);

        // 4. Simpan data utama ke database
        $service = Service::create($validated);

        // 5. Upload Gambar Carousel (Gallery) - Loop karena bisa banyak
        if ($request->hasFile('carousel_images')) {
            foreach ($request->file('carousel_images') as $image) {
                // Sanitize filename
                $originalName = $image->getClientOriginalName();
                $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '_' . $safeName;
                
                $path = $image->storeAs('services/carousel', $filename, 'public');
                
                // Simpan path gambar ke tabel service_images
                ServiceImage::create([
                    'service_id' => $service->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Unit keamanan berhasil ditambahkan!');
    }

    /**
     * FUNGSI: Tampilkan form edit unit
     * ROUTE: GET /admin/services/{id}/edit
     * VIEW: admin.services.edit
     * PARAMETER: $id (ID unit yang mau diedit)
     *
     * LOGIC:
     * 1. Cari service berdasarkan ID
     * 2. Ambil semua kategori buat dropdown
     * 3. Kirim data service & categories ke view
     */
    public function edit(string $id)
    {
        // Cari service by ID, kalau gak ketemu auto 404
        $service = Service::findOrFail($id);

        // Ambil semua kategori buat dropdown
        $categories = Category::orderBy('name')->get();

        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * FUNGSI: Proses update unit yang sudah ada
     * ROUTE: PUT /admin/services/{id}
     * REDIRECT: Kembali ke index dengan pesan sukses
     * PARAMETER: $id (ID unit yang mau diupdate)
     *
     * LOGIC:
     * 1. Validasi input (sama seperti store, tapi unique ignore ID sendiri)
     * 2. Kalau ada foto baru:
     *    - Hapus foto lama dari storage
     *    - Upload foto baru
     * 3. Generate slug baru kalau name berubah
     * 4. Update data di database
     * 5. Redirect dengan flash message
     *
     * FILE UPLOAD LOGIC:
     * - Kalau user upload foto baru, foto lama dihapus dari storage
     * - Kalau gak upload foto baru, foto lama tetap dipakai
     * - Ini mencegah file sampah numpuk di storage
     */
    public function update(Request $request, string $id)
    {
        // STEP 1: CARI SERVICE BERDASARKAN ID
        $service = Service::findOrFail($id);

        // STEP 2: VALIDASI INPUT
        // Perhatikan unique rule: unique:services,name,{$id}
        // Artinya: boleh pakai name yang sama dengan service ini sendiri
        $validated = $request->validate([
            'name'        => "required|unique:services,name,{$id}|min:3|max:255",
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0|max:9999999999.99',
            'description' => 'required|min:10',
            'unit_size'   => 'nullable|integer|min:1',
            'unit_description' => 'nullable|string|max:255',
            'status'      => 'required|in:available,deployed,maintenance',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
            'carousel_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
        ], [
            'name.required'        => 'Nama unit wajib diisi.',
            'name.unique'          => 'Nama unit sudah digunakan unit lain.',
            'name.min'             => 'Nama unit minimal 3 karakter.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak valid.',
            'price.required'       => 'Harga wajib diisi.',
            'price.numeric'        => 'Harga harus berupa angka.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 10 karakter.',
            'status.required'      => 'Status wajib dipilih.',
            'status.in'            => 'Status tidak valid.',
            'image.image'          => 'File thumbnail harus berupa gambar.',
            'image.mimes'          => 'Format thumbnail harus JPG, JPEG, PNG, atau WebP.',
            'image.max'            => 'Ukuran thumbnail maksimal 10MB.',
            'carousel_images.*.image' => 'File carousel harus berupa gambar.',
            'carousel_images.*.mimes' => 'Format carousel harus JPG, JPEG, PNG, atau WebP.',
            'carousel_images.*.max'   => 'Ukuran carousel maksimal 10MB.',
        ]);

        // STEP 3: HANDLE UPLOAD FOTO THUMBNAIL BARU (KALAU ADA)
        if ($request->hasFile('image')) {
            // HAPUS FOTO LAMA DULU (kalau ada)
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            // UPLOAD FOTO BARU
            // Sanitize filename: ganti spasi jadi dash, lowercase
            $originalName = $request->file('image')->getClientOriginalName();
            $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $request->file('image')->getClientOriginalExtension();
            $filename = time() . '_' . $safeName;
            
            $validated['image'] = $request->file('image')->storeAs('services', $filename, 'public');
        }

        // STEP 4: GENERATE SLUG BARU (kalau name berubah)
        $validated['slug'] = Str::slug($validated['name']);

        // STEP 5: UPDATE DATA
        $service->update($validated);

        // STEP 6: HANDLE UPLOAD CAROUSEL IMAGES BARU (APPEND)
        if ($request->hasFile('carousel_images')) {
            foreach ($request->file('carousel_images') as $image) {
                // Sanitize filename carousel juga
                $originalName = $image->getClientOriginalName();
                $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '_' . $safeName;
                
                $path = $image->storeAs('services/carousel', $filename, 'public');
                
                ServiceImage::create([
                    'service_id' => $service->id,
                    'image_path' => $path
                ]);
            }
        }

        // STEP 7: REDIRECT DENGAN FLASH MESSAGE
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Unit keamanan berhasil diperbarui!');
    }

    /**
     * FUNGSI: Hapus unit dari database + hapus foto
     * ROUTE: DELETE /admin/services/{id}
     * REDIRECT: Kembali ke index dengan pesan sukses
     * PARAMETER: $id (ID unitTHUMBNAIL DARI STORAGE (kalau ada)
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        // STEP 3: HAPUS FOTO CAROUSEL DARI STORAGE
        foreach ($service->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        // STEP 4: HAPUS DATA DARI DATABASE
        // Karena ada onDelete('cascade') di migration, record di service_images otomatis kehapus
        $service->delete();

        // STEP 5: REDIRECT DENGAN FLASH MESSAGE
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Unit keamanan berhasil dihapus!');
    }

    /**
     * FUNGSI: Hapus satu foto carousel
     * ROUTE: DELETE /admin/services/image/{id}
     */
    public function destroyImage(string $id)
    {
        $image = ServiceImage::findOrFail($id);
        
        // Hapus file fisik
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Hapus record DB
        $image->delete();

        return back()->with('success', 'Foto carousel berhasil dihapus.');
    }

    /**
     * FUNGSI: Hapus unit dari database + hapus foto
     * ROUTE: DELETE /admin/services/{id}
     */
    public function destroy(string $id)
    {
        // STEP 1: CARI SERVICE
        $service = Service::findOrFail($id);

        // STEP 2: HAPUS FOTO DARI STORAGE (kalau ada)
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        // STEP 3: HAPUS DATA DARI DATABASE
        $service->delete();

        // STEP 4: REDIRECT DENGAN FLASH MESSAGE
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Unit keamanan berhasil dihapus!');
    }
}

/*
    ============================================================================
    CATATAN PRIBADI (CHANDRA)
    ============================================================================
    
    1. LOGIKA CONTROLLER INI:
       - Ini otak dari segala operasi CRUD (Create, Read, Update, Delete) layanan.
       - Gw udah tambahin fitur upload multiple image di method `store` sama `update`.
       - Pake loop `foreach` buat nyimpen satu-satu ke tabel `service_images`.
    
    2. SOAL VALIDASI:
       - Validasi udah lumayan ketat. File harus gambar (jpg/png/webp) dan max 10MB.
       - Kalo user bandel upload file .exe atau .php, bakal ditolak mentah-mentah.
    
    3. FITUR HAPUS GAMBAR:
       - Method `destroyImage` itu khusus buat ngehapus satu foto dari galeri carousel.
       - Method `destroy` (yang paling bawah) itu buat ngehapus SATU UNIT FULL beserta semua fotonya.
       - Jadi kalo unit dihapus, sampah fotonya gak numpuk di server. Bersih!
*/
