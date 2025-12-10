<?php

use Illuminate\Support\Facades\Route;
// Panggil Controller Auth buat ngatur Login/Logout/Register
use App\Http\Controllers\AuthController;
// UPDATE: Panggil Model Service biar bisa ambil data Unit buat Katalog
use App\Models\Service; 

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
========== CATATAN LOGIKA INTEGRASI (URG) ==========

1. UPDATE FRONTEND NAUVAL:
   - Rute '/' sekarang ngarah ke `view('layouts.welcome')`. Jadi pas buka web, langsung sangar.
   - Urg tambahin rute '/catalog'. Di sini urg pake `Service::all()` buat ngambil data REAL 
     dari database (Eastern Wolves, Blackgold, dll) yang kemaren urg seed.

2. SISTEM LOGIN (BACKEND):
   - Bagian Route::controller(AuthController::class) itu punya urg.
   - Fungsinya buat ngatur keamanan pintu masuk (Login/Logout).
   - Logika 'name' ('login.post') tetep dipake biar gampang dipanggil di form view.

3. PENTING:
   File ini nge-link Frontend (Nauval) sama Backend (Urg).
   Jadi sekarang Halaman Depan, Katalog, sama Login udah satu jalur.
*/