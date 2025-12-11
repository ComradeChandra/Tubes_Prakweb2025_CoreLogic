<?php

use Illuminate\Support\Facades\Route;
// Panggil Controller Auth buat ngatur Login/Logout/Register
use App\Http\Controllers\AuthController;
// UPDATE: Panggil Model Service biar bisa ambil data Unit buat Katalog
use App\Models\Service;
// CRUD Admin Controllers
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController; 

/*
|--------------------------------------------------------------------------
| Web Routes - CORELOGIC DEFENSE
|--------------------------------------------------------------------------
|
| Ini peta jalan aplikasi kita.
| Di sini urg ngatur: "Kalau user ketik URL ini, laravel harus ngapain?"
|
*/

// 1. HALAMAN DEPAN (LANDING PAGE)
// UPDATE: Urg ganti arahnya ke 'layouts.welcome' (Punya Nauval).
// Biar yang muncul desain Tentara & "Elite Protection", bukan logo Laravel biasa.
Route::get('/', function () {
    return view('layouts.welcome');
});

// 2. HALAMAN KATALOG (DAFTAR UNIT)
// UPDATE: Ini rute baru buat nampilin halaman Katalog buatan Nauval.
Route::get('/catalog', function () {
    // Logika: Ambil semua data dari tabel 'services' di database
    $services = Service::all();
    
    // Kirim datanya ke view 'catalog.blade.php' biar bisa di-looping di sana
    return view('catalog', compact('services'));
});

// 3. SISTEM AUTHENTICATION (LOGIN & REGISTER)
// Urg kelompokkin pake 'controller' biar rapi, gak perlu ngetik [AuthController::class] berkali-kali.
Route::controller(AuthController::class)->group(function () {
    
    // --- HALAMAN LOGIN ---
    // URL: /login (GET)
    // Tugas: Nampilin form login Redfor yang tadi urg desain.
    Route::get('/login', 'showLogin')->name('login');

    // --- PROSES LOGIN ---
    // URL: /login (POST)
    // Tugas: Nerima data dari form, terus cek email & password (Validasi).
    Route::post('/login', 'login')->name('login.post');

    // --- HALAMAN REGISTER ---
    // URL: /register (GET)
    Route::get('/register', 'showRegister')->name('register');

    // --- PROSES REGISTER ---
    // URL: /register (POST)
    Route::post('/register', 'register')->name('register.post');

    // --- LOGOUT ---
    Route::post('/logout', 'logout')->name('logout');
});

/*
========== ADMIN ROUTES - CRUD MANAGEMENT ==========

PROTECTED ROUTES (CUMA ADMIN YANG BISA AKSES)
Semua route di grup ini dilindungi dengan 2 middleware:
1. 'auth' -> User harus login dulu
2. 'role:admin' -> User harus punya role 'admin'

Kalau customer atau staff coba akses -> 403 Forbidden
Kalau belum login -> redirect ke halaman login

RESOURCEFUL ROUTES:
Route::resource() otomatis generate 7 routes:
- index()   -> GET    /admin/categories         (List semua kategori)
- create()  -> GET    /admin/categories/create  (Form tambah kategori)
- store()   -> POST   /admin/categories         (Proses tambah kategori)
- show()    -> GET    /admin/categories/{id}    (Detail kategori - not used)
- edit()    -> GET    /admin/categories/{id}/edit (Form edit kategori)
- update()  -> PUT    /admin/categories/{id}    (Proses update kategori)
- destroy() -> DELETE /admin/categories/{id}    (Hapus kategori)

NAMING CONVENTION:
->names('admin.categories') -> Semua route punya prefix nama 'admin.categories'
Contoh: route('admin.categories.index'), route('admin.categories.create'), dll
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // [BARANG BARU] DASHBOARD REDIRECT 
    // Biar kalo Admin buka '/admin' atau login, gak nyasar (404), tapi langsung masuk ke daftar Services.
    Route::get('/admin', function() {
        return redirect()->route('admin.services.index');
    })->name('admin.dashboard');

    // ===== CRUD KATEGORI =====
    // Manage kategori unit keamanan (Combat, Transport, Training, dll)
    Route::resource('admin/categories', CategoryController::class)
         ->names('admin.categories')
         ->except(['show']); // Gak pakai show() karena list udah cukup

    // ===== CRUD SERVICES (UNIT KEAMANAN) =====
    // Manage unit keamanan individual (Eastern Wolves, K9, dll)
    // Include upload foto, set harga, deskripsi, status
    Route::resource('admin/services', ServiceController::class)
         ->names('admin.services')
         ->except(['show']); // Gak pakai show() karena list udah cukup

});

/*
========== CATATAN LOGIKA INTEGRASI ==========

1. UPDATE FRONTEND NAUVAL:
   - Rute '/' sekarang ngarah ke `view('layouts.welcome')`. Jadi pas buka web, langsung sangar.
   - Urg tambahin rute '/catalog'. Di sini urg pake `Service::all()` buat ngambil data REAL
     dari database (Eastern Wolves, Blackgold, dll) yang kemaren urg seed.

2. SISTEM LOGIN (BACKEND):
   - Bagian Route::controller(AuthController::class) itu punya urg.
   - Fungsinya buat ngatur keamanan pintu masuk (Login/Logout).
   - Logika 'name' ('login.post') tetep dipake biar gampang dipanggil di form view.

3. ADMIN PANEL (NEW):
   - Rute admin dilindungi middleware auth + role:admin
   - Resource routes otomatis generate 7 endpoints (index, create, store, edit, update, destroy)
   - Admin bisa manage Categories dan Services lewat web UI (gak perlu database manual)

4. PENTING:
   File ini nge-link Frontend (Nauval) sama Backend (Urg).
   Jadi sekarang Halaman Depan, Katalog, Login, dan Admin Panel udah satu jalur.

5. TESTING ADMIN ACCESS:
   Login dengan:
   Email: admin@corelogic.com
   Password: password

   Setelah login, akses: http://localhost/admin/categories atau /admin/services
*/