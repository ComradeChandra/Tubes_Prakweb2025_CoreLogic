<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/*
========== CATEGORY CONTROLLER - CRUD MANAGEMENT ==========

FUNGSI FILE INI:
Controller ini khusus buat Admin kelola Kategori Unit Keamanan (Combat, Transport, Training, dll).
Admin bisa:
- Lihat semua kategori
- Tambah kategori baru
- Edit kategori yang udah ada
- Hapus kategori

KENAPA PERLU CONTROLLER INI?
Tanpa controller ini, Admin harus masuk database manual buat nambahin kategori baru.
Dengan controller ini, Admin bisa kelola kategori lewat UI web yang user-friendly.

ROUTE YANG DIPAKE:
GET  /admin/categories         -> index()    (List semua kategori)
GET  /admin/categories/create  -> create()   (Form tambah kategori)
POST /admin/categories         -> store()    (Proses tambah kategori)
GET  /admin/categories/{id}/edit -> edit()   (Form edit kategori)
PUT  /admin/categories/{id}    -> update()   (Proses update kategori)
DELETE /admin/categories/{id}  -> destroy()  (Hapus kategori)
*/

class CategoryController extends Controller
{
    /**
     * FUNGSI: Tampilkan daftar semua kategori
     * ROUTE: GET /admin/categories
     * VIEW: admin.categories.index
     *
     * LOGIC:
     * 1. Ambil semua data kategori dari database (sort by terbaru)
     * 2. Kirim data ke view dalam bentuk compact
     * 3. View akan nampilin tabel dengan tombol Edit & Delete
     */
    public function index()
    {
        // Ambil semua kategori, urutkan dari yang terbaru dibuat
        // withCount('services') -> hitung jumlah services di tiap kategori (untuk info tambahan)
        $categories = Category::withCount('services')
                              ->latest()
                              ->get();

        // Kirim data ke view admin.categories.index
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * FUNGSI: Tampilkan form untuk tambah kategori baru
     * ROUTE: GET /admin/categories/create
     * VIEW: admin.categories.create
     *
     * LOGIC:
     * - Cuma return view form kosong
     * - User isi form, terus klik Submit
     * - Submit akan trigger method store()
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * FUNGSI: Proses simpan kategori baru ke database
     * ROUTE: POST /admin/categories
     * REDIRECT: Kembali ke index dengan pesan sukses
     *
     * LOGIC:
     * 1. Validasi input (name wajib, unique, min 3 karakter)
     * 2. Generate slug otomatis dari name (untuk SEO-friendly URL)
     * 3. Simpan ke database
     * 4. Redirect ke halaman list dengan flash message sukses
     *
     * VALIDASI RULES:
     * - name: wajib diisi, unique di tabel categories, minimal 3 karakter
     *
     * AUTO-GENERATE SLUG:
     * Slug dibuat otomatis dari name pakai helper Str::slug()
     * Contoh: "Tactical Combat Unit" -> "tactical-combat-unit"
     */
    public function store(Request $request)
    {
        // STEP 1: VALIDASI INPUT
        // Pastikan name gak kosong, gak duplikat, dan minimal 3 huruf
        $validated = $request->validate([
            'name' => 'required|unique:categories,name|min:3|max:100',
        ], [
            // Custom error messages (bahasa Indonesia biar user-friendly)
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah ada. Gunakan nama lain.',
            'name.min'      => 'Nama kategori minimal 3 karakter.',
            'name.max'      => 'Nama kategori maksimal 100 karakter.',
        ]);

        // STEP 2: GENERATE SLUG OTOMATIS
        // Slug ini akan dipake di URL, contoh: /categories/tactical-combat-unit
        $validated['slug'] = Str::slug($validated['name']);

        // STEP 3: SIMPAN KE DATABASE
        Category::create($validated);

        // STEP 4: REDIRECT DENGAN FLASH MESSAGE
        // Flash message ini akan muncul sebentar di halaman list (pakai session)
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * FUNGSI: Tampilkan form edit kategori
     * ROUTE: GET /admin/categories/{id}/edit
     * VIEW: admin.categories.edit
     * PARAMETER: $id (ID kategori yang mau diedit)
     *
     * LOGIC:
     * 1. Cari kategori berdasarkan ID
     * 2. Kalau gak ketemu, auto return 404 (Laravel otomatis)
     * 3. Kirim data kategori ke view form edit
     */
    public function edit(string $id)
    {
        // findOrFail() = cari by ID, kalau gak ketemu auto throw 404
        $category = Category::findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * FUNGSI: Proses update kategori yang sudah ada
     * ROUTE: PUT /admin/categories/{id}
     * REDIRECT: Kembali ke index dengan pesan sukses
     * PARAMETER: $id (ID kategori yang mau diupdate)
     *
     * LOGIC:
     * 1. Validasi input (name wajib, unique kecuali ID sendiri, min 3 karakter)
     * 2. Generate slug baru dari name yang baru
     * 3. Update data di database
     * 4. Redirect dengan flash message
     *
     * KENAPA VALIDASI UNIQUE PAKAI IGNORE?
     * Rule: 'unique:categories,name,' . $id
     * Artinya: "Name harus unique, TAPI ignore ID sendiri"
     * Contoh: Edit kategori "Combat" jadi "Combat Unit" -> boleh
     *         Edit kategori "Combat" jadi "Transport" (yang udah ada) -> gak boleh
     */
    public function update(Request $request, string $id)
    {
        // STEP 1: CARI KATEGORI BERDASARKAN ID
        $category = Category::findOrFail($id);

        // STEP 2: VALIDASI INPUT
        // Perhatikan unique rule: unique:categories,name,{$id}
        // Artinya: boleh pakai name yang sama dengan kategori ini sendiri
        $validated = $request->validate([
            'name' => "required|unique:categories,name,{$id}|min:3|max:100",
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah digunakan kategori lain.',
            'name.min'      => 'Nama kategori minimal 3 karakter.',
            'name.max'      => 'Nama kategori maksimal 100 karakter.',
        ]);

        // STEP 3: GENERATE SLUG BARU
        $validated['slug'] = Str::slug($validated['name']);

        // STEP 4: UPDATE DATA
        $category->update($validated);

        // STEP 5: REDIRECT DENGAN FLASH MESSAGE
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * FUNGSI: Hapus kategori dari database
     * ROUTE: DELETE /admin/categories/{id}
     * REDIRECT: Kembali ke index dengan pesan sukses
     * PARAMETER: $id (ID kategori yang mau dihapus)
     *
     * LOGIC:
     * 1. Cari kategori berdasarkan ID
     * 2. Hapus dari database (hard delete)
     * 3. Redirect dengan flash message
     *
     * CATATAN PENTING - CASCADE DELETE:
     * Di migration Services, urg udah set onDelete('cascade')
     * Artinya: kalau kategori dihapus, SEMUA services di kategori itu ikut kehapus otomatis
     * Contoh: Hapus kategori "Combat" -> semua unit combat (Eastern Wolves, dll) ikut kehapus
     *
     * HATI-HATI:
     * Gak ada konfirmasi di controller, konfirmasi dilakukan di view pakai JavaScript
     * Pastikan di view ada alert "Yakin hapus? Semua unit di kategori ini ikut terhapus"
     */
    public function destroy(string $id)
    {
        // STEP 1: CARI KATEGORI
        $category = Category::findOrFail($id);

        // STEP 2: HAPUS DARI DATABASE
        // Karena ada cascade delete, semua services terkait ikut kehapus
        $category->delete();

        // STEP 3: REDIRECT DENGAN FLASH MESSAGE
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori dan semua unit di dalamnya berhasil dihapus!');
    }
}

/*
========== CATATAN TAMBAHAN UNTUK DEVELOPER ==========

1. KENAPA PAKAI SLUG?
   Slug itu URL-friendly version dari name.
   Contoh: "Tactical Combat Unit" -> "tactical-combat-unit"
   Fungsinya buat SEO dan bikin URL lebih cantik:
   Jelek: /category/1
   Bagus: /category/tactical-combat-unit

2. KENAPA PAKAI withCount('services')?
   Di method index(), urg pakai withCount('services')
   Ini ngasih info berapa jumlah services di tiap kategori TANPA perlu query tambahan
   Di view, urg bisa akses pakai $category->services_count
   Berguna buat nampilin info: "Combat (5 units)"

3. VALIDATION ERROR HANDLING:
   Laravel otomatis redirect balik ke form dengan error messages
   Gak perlu try-catch manual
   Error messages bisa diakses di view pakai @error atau $errors->first()

4. FLASH MESSAGES:
   with('success', 'Pesan') -> simpan pesan sementara di session
   Di view, akses pakai session('success')
   Pesan ini cuma muncul sekali, abis itu hilang (makanya namanya flash)

5. FIND vs FIND OR FAIL:
   find($id) -> return null kalau gak ketemu (urg harus handle manual)
   findOrFail($id) -> auto throw 404 kalau gak ketemu (lebih praktis)

6. RESOURCEFUL ROUTES:
   Controller ini dirancang buat pakai Route::resource()
   Jadi di web.php tinggal tulis:
   Route::resource('admin/categories', CategoryController::class);

   Laravel otomatis generate 7 routes:
   - index, create, store, show, edit, update, destroy

7. AUTHORIZATION (NEXT STEP):
   Controller ini belum ada authorization check
   Nanti harus ditambahin middleware RoleMiddleware buat pastikan cuma admin yang akses
   Di web.php: ->middleware('role:admin')

END OF FILE
*/
