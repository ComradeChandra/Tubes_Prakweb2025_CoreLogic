<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Ini Facade buat ngurusin Login/Logout
use App\Models\User; // Panggil Model User buat register nanti

class AuthController extends Controller
{
    /**
     * TAMPILIN HALAMAN LOGIN
     * URL: /login (GET)
     */
    public function showLogin()
    {
        // Urg arahin ke file view yang tadi kita desain
        // Lokasi: resources/views/auth/login.blade.php
        return view('auth.login');
    }

    /**
     * PROSES LOGIN (LOGIKA UTAMA)
     * URL: /login (POST)
     */
    public function login(Request $request)
    {
        // 1. VALIDASI INPUT
        // Urg pastiin user ngisi kolomnya. Gak boleh kosong.
        $credentials = $request->validate([
            'login_identifier' => ['required', 'string'], // Bisa Email atau Username
            'password' => ['required'],
        ]);

        // 2. CEK TIPE INPUT (Email atau Username?)
        // Ini logika pinter yang urg janjiin tadi.
        // filter_var ngecek: "Ini format email bukan?"
        // Kalau iya -> set jadi 'email'. Kalau bukan -> set jadi 'username'.
        $fieldType = filter_var($request->login_identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 3. COBA LOGIN (AUTH ATTEMPT)
        // Kita gabungin data loginnya
        $authData = [
            $fieldType => $request->login_identifier,
            'password' => $request->password
        ];

        // Auth::attempt bakal otomatis ngecek ke database + hash passwordnya cocok gak.
        // $request->filled('remember') itu ngecek checkbox "Remember Me" dicentang gak.
        if (Auth::attempt($authData, $request->filled('remember'))) {
            
            // Kalau Sukses:
            
            // a. Regenerate Session ID (Biar aman dari serangan Session Fixation)
            $request->session()->regenerate();

            // b. Cek Role (Sistem Kasta)
            // Kalau Admin/Staff -> Masuk Dashboard (Nanti kita bikin)
            // Kalau Customer -> Masuk Halaman Utama
            // Sementara urg arahin semua ke '/' dulu sampe Dashboard jadi.
            return redirect()->intended('/');
        }

        // 4. KALAU GAGAL
        // Balikin ke halaman login, bawa pesan error.
        // withInput() biar email yang dia ketik gak ilang.
        return back()->withErrors([
            'login_identifier' => 'The provided credentials do not match our records.',
        ])->onlyInput('login_identifier');
    }

    /**
     * PROSES LOGOUT
     * URL: /logout (POST)
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Hapus sesi login

        $request->session()->invalidate(); // Matikan session
        $request->session()->regenerateToken(); // Generate ulang token CSRF

        // Tendang balik ke halaman login
        return redirect('/login');
    }

    // --- FITUR REGISTER (AKAN DATANG) ---
    // Urg siapin func-nya kosong dulu biar Route web.php gak error.
    
    public function showRegister()
    {
        // Nanti kita bikin view: resources/views/auth/register.blade.php
        return view('auth.register'); 
    }

    public function register(Request $request)
    {
        // Nanti diisi logika simpen user baru
        dd('Fitur Register belum dikoding, sabar ya.');
    }
}

/*
========== CATATAN LOGIKA (URG) ==========

Ini otak dari sistem keamanan CoreLogic.

1. LOGIKA 'LOGIN IDENTIFIER':
   Di function login(), urg pake trik `filter_var`.
   Ini ngebuat user bebas mau login pake Username ("admin") atau Email ("admin@corelogic.com").
   Sistem bakal otomatis tau itu apa, terus dicocokin ke database.

2. AUTH::ATTEMPT:
   Ini fungsi ajaib Laravel. Urg gak perlu ribet nge-hash password manual buat nyocokin.
   Dia otomatis ngambil password inputan -> di-hash -> dibandingin sama password di database.
   Kalau cocok return TRUE, kalau salah return FALSE.

3. SECURITY (SESSION REGENERATE):
   Pas user berhasil login, urg panggil `$request->session()->regenerate()`.
   Ini WAJIB buat keamanan. Biar session ID yang lama diganti baru, 
   jadi hacker gak bisa nyuri sesi user (Session Hijacking).

4. REDIRECT:
   Sekarang urg set `return redirect()->intended('/')`.
   Artinya kalau sukses, bawa ke Halaman Depan.
   Nanti kalau Dashboard Admin udah jadi, urg bakal ubah ini pake logika:
   "Kalau Role == Admin -> ke Dashboard".
*/