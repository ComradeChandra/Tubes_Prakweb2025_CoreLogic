<?php

use Illuminate\Support\Facades\Route;
// Panggil Controller Auth buat ngatur Login/Logout/Register
use App\Http\Controllers\AuthController;
// UPDATE: Panggil Model Service biar bisa ambil data Unit buat Katalog
use App\Models\Service;
use App\Models\Category; // Tambahin ini biar filter kategori di katalog jalan dinamis
// CRUD Admin Controllers
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController; // Controller baru buat handle order
use App\Http\Controllers\DashboardController; // Controller khusus Dashboard Admin
use App\Http\Controllers\ProfileController; // Controller Profile User
use App\Http\Controllers\UserController; // Controller Admin Manage User

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
// Urg arahin ke Controller biar logic search & filternya jalan (jangan pake closure function lagi).
Route::get('/catalog', [ServiceController::class, 'publicCatalog']);

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

// 4. ROUTES UNTUK USER YANG SUDAH LOGIN
// Protected routes - harus login dulu
Route::middleware(['auth'])->group(function () {

    // Detail Service & Form Order
    Route::get('/services/{id}', function($id) {
        $service = Service::findOrFail($id);
        return view('services.show', compact('service'));
    })->name('services.show');

    Route::get('/services/{id}/order', function($id) {
        $service = Service::findOrFail($id);
        return view('services.order', compact('service'));
    })->name('services.order');

    Route::post('/services/{id}/order', [OrderController::class, 'store'])->name('orders.store');

    // Halaman History Order User
    Route::get('/my-orders', [OrderController::class, 'history'])->name('orders.history');

    // Route: Show order detail (USER)
    // Catatan: Pastikan route ini didefinisikan sebelum route PDF yang lebih spesifik
    Route::get('/my-orders/{order}', [OrderController::class, 'showUser'])->name('orders.show');

    // Export PDF Order User
    Route::get('/my-orders/{order}/pdf', [OrderController::class, 'exportPdf'])->name('orders.exportPdf');

    // --- USER PROFILE (NEW SPRINT 3) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Notifications (mark read)
    Route::post('/notifications/mark-read', [ProfileController::class, 'markNotificationsRead'])->name('notifications.markRead');

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

    // Halaman Utama Admin (Dashboard)
    // Nampilin ringkasan data / overview dengan stats keuangan
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    // --- MANAGE KATEGORI UNIT ---
    // Pake resource biar otomatis dapet index, create, store, edit, update, destroy
    // Ini buat ngatur kategori kayak: Combat, Transport, Guard, dll
    Route::resource('admin/categories', CategoryController::class)
         ->names('admin.categories')
         ->except(['show']); // Gak pakai show() karena list udah cukup

    // ===== CRUD SERVICES (UNIT KEAMANAN) =====
    // Manage unit keamanan individual (Eastern Wolves, K9, dll)
    // Include upload foto, set harga, deskripsi, status

    // Route khusus buat hapus foto carousel
    Route::delete('admin/services/image/{id}', [ServiceController::class, 'destroyImage'])->name('admin.services.image.destroy');

    Route::resource('admin/services', ServiceController::class)
         ->names('admin.services')
         ->except(['show']); // Gak pakai show() karena list udah cukup

    // ===== MANAGE INCOMING ORDERS =====
    // Admin bisa liat list order & update status (Approve/Reject)
    Route::get('admin/orders', [OrderController::class, 'indexAdmin'])->name('admin.orders.index');
    Route::patch('admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    // --- MANAGE USERS (NEW) ---
    // Admin bisa liat list user, detail, dan hapus user
    Route::resource('admin/users', UserController::class)
         ->names('admin.users')
         ->only(['index', 'show', 'destroy']);

    // Admin actions for KTP verification
    // Catatan: Route ini dipakai oleh halaman admin (User details) untuk menandai KTP
    // sebagai terverifikasi atau tidak. Setelah tindakan, user akan menerima notifikasi
    // singkat di halaman profil mereka.
    Route::patch('admin/users/{user}/verify-ktp', [UserController::class, 'verifyKtp'])->name('admin.users.verifyKtp');
    Route::patch('admin/users/{user}/unverify-ktp', [UserController::class, 'unverifyKtp'])->name('admin.users.unverifyKtp');

    // ===== EXPORT LAPORAN PDF =====
    // Download laporan penjualan bulanan dalam format PDF
    Route::get('admin/reports/monthly-sales', [DashboardController::class, 'downloadMonthlySalesReport'])->name('admin.reports.monthly');
});

// DEV-SAFE: Smoke test route to create a test order and set KTP verified (only local environment)
if (app()->environment('local')) {
    Route::get('/dev/smoke-order', function() {
        $user = \App\Models\User::where('email', 'customer@test.com')->first();
        if (!$user) return response('Test user not found', 404);

        $user->update(['id_card_path' => 'id_cards/sample.jpg', 'ktp_verified' => true]);

        $service = \App\Models\Service::first();
        if (!$service) return response('No service found', 404);

        $start = now()->addDay()->toDateString();
        $end = now()->addDays(3)->toDateString();
        $quantity = 2;

        $startDT = \Carbon\Carbon::parse($start);
        $endDT = \Carbon\Carbon::parse($end);
        $days = $startDT->diffInDays($endDT);
        $weeks = ceil(max($days, 1) / 7);

        $subtotal = $service->price * $quantity * $weeks;

        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'quantity' => $quantity,
            'start_date' => $start,
            'end_date' => $end,
            'total_price' => $subtotal,
            'discount' => 0,
            'status' => 'PENDING',
            'address' => 'Smoke Test Address',
        ]);

        return response()->json(['order_id' => $order->id, 'total_price' => $order->total_price]);
    });

    Route::get('/dev/debug', function() {
        $users = \App\Models\User::count();
        $services = \App\Models\Service::count();
        $orders = \App\Models\Order::count();
        $customer = \App\Models\User::where('email','customer@test.com')->first();
        $notifications = $customer ? $customer->userNotifications()->count() : 0;
        return response()->json(compact('users','services','orders','notifications'));
    });

    Route::post('/dev/upload', function(\Illuminate\Http\Request $request) {
        if (! $request->hasFile('file')) return response('No file uploaded', 400);
        $f = $request->file('file');
        return response()->json(['size' => $f->getSize(), 'name' => $f->getClientOriginalName()]);
    });
}

/*
========== CATATAN LOGIKA INTEGRASI (URG) ==========

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