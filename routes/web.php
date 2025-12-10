<?php

use Illuminate\Support\Facades\Route;
// Panggil Controller Auth yang tadi kita bikin
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Ini peta jalan aplikasi kita.
| Di sini urg ngatur: "Kalau user ketik URL ini, laravel harus ngapain?"
|
*/

// 1. HALAMAN DEPAN (LANDING PAGE)
// Sementara urg arahin ke welcome default dulu. 
// Nanti si Nauval yang bakal ubah ini jadi Landing Page CoreLogic.
Route::get('/', function () {
    return view('welcome');
});

// 2. SISTEM AUTHENTICATION (LOGIN & REGISTER)
// Urg kelompokkin pake 'controller' biar rapi, gak perlu ngetik [AuthController::class] berkali-kali.

Route::controller(AuthController::class)->group(function () {
    
    // --- HALAMAN LOGIN ---
    // URL: /login (GET)
    // Tugas: Nampilin form login yang tadi urg desain.
    // Nama Route: 'login' (Penting buat redirect middleware nanti)
    Route::get('/login', 'showLogin')->name('login');

    // --- PROSES LOGIN ---
    // URL: /login (POST)
    // Tugas: Nerima data dari form, terus cek email & password.
    // Nama Route: 'login.post' (Ini yang dipake di <form action="..."> tadi)
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
========== CATATAN LOGIKA ROUTE (URG) ==========

1. KONSEP 'NAME':
   Liat deh urg kasih ->name('login.post').
   Ini semacam "Nickname" buat jalur ini.
   Jadi di view tadi, urg cukup panggil route('login.post').
   Kalau suatu saat urg ganti URL-nya jadi '/masuk-gan', view-nya gak perlu diedit, karena nickname-nya tetep sama.

2. CONTROLLER GROUP:
   Urg pake Route::controller(...) biar kodingannya ringkes.
   Semua jalur di dalem group itu otomatis nyambung ke AuthController.
   Jadi tinggal tulis nama fungsinya aja ('showLogin', 'login', dll).

3. ERROR SAAT INI:
   Kalau file ini di-save sekarang, pasti bakal ERROR pas dibuka.
   Kenapa? Karena di AuthController, fungsi 'showLogin', 'login', dll BELUM ADA isinya.
   Langkah selanjutnya: Kita harus isi otak si AuthController.
*/