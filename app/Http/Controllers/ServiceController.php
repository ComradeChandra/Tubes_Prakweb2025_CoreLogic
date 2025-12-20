<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/*
========== SERVICE CONTROLLER - CRUD MANAGEMENT ==========

FUNGSI FILE INI:
Controller ini khusus buat Admin kelola Unit Keamanan (Services/Units).
Admin bisa:
- Lihat semua unit keamanan
- Tambah unit baru (upload foto, set harga, deskripsi, status)
- Edit unit yang udah ada
- Hapus unit
- Upload dan manage foto unit

KENAPA PERLU CONTROLLER INI?
Tanpa controller ini, Admin harus masuk database manual buat nambahin unit baru.
Dengan controller ini, Admin bisa kelola unit lewat UI web dengan form upload foto, dll.

FITUR KHUSUS:
- Upload foto unit (disimpan di storage/app/public/services)
- Generate slug otomatis dari nama unit
- Filter berdasarkan kategori
- Manage status unit (available, deployed, maintenance)

ROUTE YANG DIPAKE:
GET  /admin/services         -> index()    (List semua unit)
GET  /admin/services/create  -> create()   (Form tambah unit)
POST /admin/services         -> store()    (Proses tambah unit + upload foto)
GET  /admin/services/{id}/edit -> edit()   (Form edit unit)
PUT  /admin/services/{id}    -> update()   (Proses update unit + foto)
DELETE /admin/services/{id}  -> destroy()  (Hapus unit + foto)
*/

class ServiceController extends Controller
{
    /**
     * FUNGSI: Tampilkan daftar semua unit keamanan
     * ROUTE: GET /admin/services
     * VIEW: admin.services.index
     *
     * LOGIC:
     * 1. Ambil semua data services dari database dengan relasi category
     * 2. Eager load category (with) buat menghindari N+1 query problem
     * 3. Sort dari yang terbaru
     * 4. Kirim data ke view
     *
     * EAGER LOADING:
     * with('category') -> load relasi category sekaligus dalam 1 query
     * Tanpa eager loading, setiap service akan query category sendiri (N+1 problem)
     */
    public function index()
    {
        // Ambil semua services dengan relasi category (eager loading)
        // latest() -> urutkan dari yang terbaru dibuat
        $services = Service::with('category')
                          ->latest()
                          ->get();

        // Kirim data ke view admin.services.index
        return view('admin.services.index', compact('services'));
    }

    /**
     * FUNGSI: Tampilkan form untuk tambah unit baru
     * ROUTE: GET /admin/services/create
     * VIEW: admin.services.create
     *
     * LOGIC:
     * 1. Ambil semua kategori buat dropdown select
     * 2. Kirim data categories ke view
     * 3. View nampilin form dengan field: name, category, price, description, status, image
     */
    public function create()
    {
        // Ambil semua kategori buat dropdown
        // orderBy('name') -> sort A-Z biar user gampang cari
        $categories = Category::orderBy('name')->get();

        return view('admin.services.create', compact('categories'));
    }

    /**
     * FUNGSI: Proses simpan unit baru ke database + upload foto
     * ROUTE: POST /admin/services
     * REDIRECT: Kembali ke index dengan pesan sukses
     *
     * LOGIC:
     * 1. Validasi semua input (name, category_id, price, description, status, image)
     * 2. Upload foto kalau ada (simpan ke storage/app/public/services)
     * 3. Generate slug otomatis dari name
     * 4. Simpan data ke database
     * 5. Redirect dengan flash message
     *
     * VALIDASI RULES:
     * - name: wajib, unique, min 3 karakter
     * - category_id: wajib, harus exist di tabel categories
     * - price: wajib, numeric, minimal 0
     * - description: wajib, minimal 10 karakter
     * - status: wajib, harus salah satu dari: available, deployed, maintenance
     * - image: opsional, harus file gambar (jpg/jpeg/png), max 2MB
     *
     * FILE UPLOAD:
     * Foto disimpan di: storage/app/public/services/{filename}
     * Filename otomatis di-generate pakai timestamp + original name
     */
    public function store(Request $request)
    {
        // STEP 1: VALIDASI INPUT
        $validated = $request->validate([
            'name'        => 'required|unique:services,name|min:3|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0|max:9999999999.99',
            'description' => 'required|min:10',
            'status'      => 'nullable|in:available,deployed,maintenance',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048', // Max 2MB
        ], [
            // Custom error messages
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
            'image.image'          => 'File harus berupa gambar.',
            'image.mimes'          => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'image.max'            => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Set default status jika tidak diisi
        if (empty($validated['status'])) {
            $validated['status'] = 'available';
        }

        // STEP 2: UPLOAD FOTO (KALAU ADA)
        if ($request->hasFile('image')) {
            // Generate unique filename: timestamp_originalname.jpg
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();

            // Simpan ke storage/app/public/services/{filename}
            // storeAs() return path relatif: services/{filename}
            $validated['image'] = $request->file('image')->storeAs('services', $filename, 'public');
        }

        // STEP 3: GENERATE SLUG OTOMATIS
        // Slug ini akan dipake di URL detail unit
        // Contoh: "Eastern Wolves Platinum" -> "eastern-wolves-platinum"
        $validated['slug'] = Str::slug($validated['name']);

        // STEP 4: SIMPAN KE DATABASE
        Service::create($validated);

        // STEP 5: REDIRECT DENGAN FLASH MESSAGE
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
            'status'      => 'required|in:available,deployed,maintenance',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
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
            'image.image'          => 'File harus berupa gambar.',
            'image.mimes'          => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'image.max'            => 'Ukuran gambar maksimal 2MB.',
        ]);

        // STEP 3: HANDLE UPLOAD FOTO BARU (KALAU ADA)
        if ($request->hasFile('image')) {
            // HAPUS FOTO LAMA DULU (kalau ada)
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            // UPLOAD FOTO BARU
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $validated['image'] = $request->file('image')->storeAs('services', $filename, 'public');
        }

        // STEP 4: GENERATE SLUG BARU (kalau name berubah)
        $validated['slug'] = Str::slug($validated['name']);

        // STEP 5: UPDATE DATA
        $service->update($validated);

        // STEP 6: REDIRECT DENGAN FLASH MESSAGE
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Unit keamanan berhasil diperbarui!');
    }

    /**
     * FUNGSI: Hapus unit dari database + hapus foto
     * ROUTE: DELETE /admin/services/{id}
     * REDIRECT: Kembali ke index dengan pesan sukses
     * PARAMETER: $id (ID unit yang mau dihapus)
     *
     * LOGIC:
     * 1. Cari service berdasarkan ID
     * 2. Hapus foto dari storage (kalau ada)
     * 3. Hapus data dari database
     * 4. Redirect dengan flash message
     *
     * CLEANUP FILE:
     * Saat service dihapus, foto di storage juga ikut dihapus
     * Ini mencegah file sampah numpuk di server
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
========== CATATAN TAMBAHAN UNTUK DEVELOPER ==========

1. FILE UPLOAD & STORAGE:
   Laravel punya 2 disk storage:
   - 'public' -> storage/app/public (bisa diakses publik via symlink)
   - 'local'  -> storage/app (private, gak bisa diakses publik)

   Foto unit disimpan di 'public' karena harus bisa ditampilkan di web.
   Path lengkap: storage/app/public/services/{filename}

   Buat akses foto dari web, harus jalanin command:
   php artisan storage:link

   Setelah itu, foto bisa diakses via URL:
   http://localhost/storage/services/{filename}

2. EAGER LOADING (N+1 PROBLEM):
   Di method index(), urg pakai with('category')
   Ini buat ngindarin N+1 query problem.

   Tanpa eager loading:
   - 1 query buat ambil semua services
   - N query buat ambil category setiap service (kalau ada 100 service = 100 query!)

   Dengan eager loading:
   - 1 query buat ambil semua services
   - 1 query buat ambil semua categories terkait
   Total: Cuma 2 query! Lebih efisien.

3. VALIDATION - UNIQUE WITH IGNORE:
   Di method update(), urg pakai: unique:services,name,{$id}
   Ini biar saat edit, service bisa tetap pakai name-nya sendiri.

   Contoh:
   Edit "Eastern Wolves" jadi "Eastern Wolves Platinum" -> BOLEH
   Edit "Eastern Wolves" jadi "Blackgold Team" (yang udah ada) -> GAK BOLEH

4. FILE DELETION:
   Saat update/delete, foto lama harus dihapus dari storage.
   Kenapa? Biar gak boros storage dengan file sampah.

   Check dulu file exist dengan: Storage::disk('public')->exists($path)
   Baru hapus dengan: Storage::disk('public')->delete($path)

5. PRICE VALIDATION:
   Rule: numeric|min:0|max:9999999999.99
   - numeric -> harus angka (bisa desimal)
   - min:0 -> gak boleh negatif
   - max:9999999999.99 -> max 10 digit + 2 desimal (sesuai migration decimal(15,2))

6. STATUS ENUM:
   Rule: in:available,deployed,maintenance
   Ini hardcode sesuai dengan enum di migration.
   Kalau nanti tambah status baru, harus update di:
   - Migration (enum values)
   - Controller validation rule (in:...)
   - View form (dropdown options)

7. SLUG AUTO-GENERATION:
   Slug dibuat otomatis dari name pakai Str::slug()
   - Auto lowercase
   - Auto replace spasi dengan dash (-)
   - Auto remove special characters

   Contoh: "K-9 Handler & Trainer" -> "k-9-handler-trainer"

8. MASS ASSIGNMENT PROTECTION:
   Model Service harus punya $guarded atau $fillable
   Di model urg udah set $guarded = ['id']
   Artinya: semua field bisa di-mass assign kecuali 'id'

9. FORM ENCODING:
   Form upload file harus pakai enctype="multipart/form-data"
   Tanpa ini, file gak akan ke-upload.
   Di view form, pastikan ada:
   <form enctype="multipart/form-data">

10. AUTHORIZATION (NEXT STEP):
    Controller ini belum ada authorization check.
    Nanti harus ditambahin middleware RoleMiddleware.
    Di web.php: ->middleware('role:admin')

*/
