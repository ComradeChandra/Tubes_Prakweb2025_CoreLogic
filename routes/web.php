<?php

use Illuminate\Support\Facades\Route;
Route::get('/tes', function () {
    return 'ROUTE JALAN';
});

// Panggil Controller yang dibutuhin
// Harus dipanggil satu-satu biar jelas asalnya darimana
use App\Http\Controllers\AuthController;
use App\Models\Service; // Ini buat ngambil data service di halaman depan
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController; 
use App\Http\Controllers\OrderController; // Controller baru buat handle order
use App\Http\Controllers\AdminController; // Controller Dashboard Admin

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Ini file buat ngatur semua alamat URL yang ada di website ini.
| Jadi kalau user ngetik alamat apa, laravel bakal cek ke sini dulu.
|
*/

// ====================================================
// HALAMAN PUBLIK (Bisa diakses siapa aja)
// ====================================================

// 1. Halaman Depan (Landing Page)
// Arahin ke view layouts.welcome punya Nauval yang desainnya sangar
Route::get('/', function () {
    return view('layouts.welcome');
});

// 2. Halaman Katalog Unit
// Ini buat nampilin daftar unit keamanan yang kita punya
Route::get('/catalog', function () {
    // Ambil semua data service dari database
    $services = Service::all();
    
    // Kirim datanya ke view catalog biar bisa dilooping
    return view('catalog', compact('services'));
});
// 3. Halaman Detail Service (Single Service)
Route::get('/services/{id}', function ($id) {
    $service = Service::findOrFail($id);
    return view('services.show', compact('service'));
});

// ====================================================
// AUTHENTICATION (LOGIN, REGISTER, LOGOUT)
// ====================================================
// Ini sengaja ditulis satu-satu routenya biar gak bingung bacanya.

// --- LOGIN ---
// Ini route buat nampilin form loginnya
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Ini route buat proses loginnya (terima data dari form)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// --- REGISTER ---
// Ini route buat nampilin form daftar akun baru
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Ini route buat proses simpan data pendaftar baru
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// --- LOGOUT ---
// Pake 'any' biar bisa diakses lewat url langsung (darurat/testing)
// Jadi kalau tombol logout error, user bisa ketik /logout di browser
Route::any('/logout', [AuthController::class, 'logout'])->name('logout');


// ====================================================
// ORDER ROUTES (Perlu Login)
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    // Proses Submit Order (POST)
    // Form action di blade harus mengarah ke sini: action="{{ route('orders.store') }}"
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // 4. Halaman Order Service (DIPINDAH KE SINI BIAR GAK BISA DIAKSES GUEST)
    // [CATATAN CHANDRA]:
    // Dulu ini di luar middleware 'auth', jadi Guest bisa akses.
    // Sekarang dimasukin sini biar cuma user login yang bisa buka form order.
    Route::get('/services/{id}/order', function ($id) {
        $service = Service::findOrFail($id);
        return view('services.order', compact('service'));
    });

    // 5. Halaman History Order User (PRAK-14)
    Route::get('/my-orders', [OrderController::class, 'history'])->name('orders.history');

});


// ====================================================
// HALAMAN ADMIN (Cuma bisa diakses User Admin)
// ====================================================
// Kita kelompokin pake middleware 'auth' sama 'role:admin'.
// Jadi kalau user belum login atau bukan admin, bakal ditendang keluar.

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Halaman Utama Admin (Dashboard)
    // Nampilin ringkasan data / overview
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // --- MANAGE KATEGORI UNIT ---
    // Pake resource biar otomatis dapet index, create, store, edit, update, destroy
    // Ini buat ngatur kategori kayak: Combat, Transport, Guard, dll
    Route::resource('admin/categories', CategoryController::class)
         ->names('admin.categories')
         ->except(['show']); // show gak dipake karena kita gak butuh detail per kategori

    // --- MANAGE UNIT KEAMANAN (SERVICES) ---
    // Route khusus buat hapus foto carousel
    Route::delete('admin/services/image/{id}', [ServiceController::class, 'destroyImage'])->name('admin.services.image.destroy');

    // Ini buat CRUD data unitnya (Eastern Wolves, K9 Unit, dll)
    Route::resource('admin/services', ServiceController::class)
         ->names('admin.services')
         ->except(['show']);

    // --- MANAGE INCOMING ORDERS (PRAK-15) ---
    // Admin bisa liat list order & update status (Approve/Reject)
    Route::get('admin/orders', [OrderController::class, 'indexAdmin'])->name('admin.orders.index');
    Route::patch('admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

});