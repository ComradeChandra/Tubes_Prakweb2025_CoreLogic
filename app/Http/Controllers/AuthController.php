<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Facade buat ngurusin Login/Logout
use App\Models\User; // Panggil Model User buat register nanti
use Illuminate\Support\Facades\Hash; // PENTING: Buat enkripsi password

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
        // filter_var ngecek: "Ini format email bukan?"
        // Kalau iya -> set jadi 'email'. Kalau bukan -> set jadi 'username'.
        $fieldType = filter_var($request->login_identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 3. SUSUN DATA LOGIN
        $authData = [
            $fieldType => $request->login_identifier,
            'password' => $request->password
        ];

        // 4. COBA LOGIN (AUTH ATTEMPT)
        // Auth::attempt otomatis hash password inputan & bandingin sama database.
        if (Auth::attempt($authData, $request->filled('remember'))) {
            
            // a. Regenerate Session ID (Security: Cegah Session Fixation)
            $request->session()->regenerate();

            // b. LOGIKA REDIRECT (PENTING!)
            // Cek Role user yang barusan login.
            if (Auth::user()->role === 'admin') {
                // Kalau Admin -> Lempar ke Dashboard Admin
                // Pakai route name biar aman
                return redirect()->route('admin.dashboard');
            }

            // [FIX 403] Kalau Customer -> PAKSA ke Halaman Depan
            // Kita ganti 'intended' jadi redirect biasa biar dia gak nyasar ke halaman admin (bekas history).
            return redirect('/');
        }

        // 5. KALAU GAGAL
        // Balikin ke halaman login, bawa pesan error.
        return back()->withErrors([
            'login_identifier' => 'The provided credentials do not match our records.',
        ])->onlyInput('login_identifier');
    }

    /**
     * [UPDATE] TAMPILIN HALAMAN REGISTER
     * URL: /register (GET)
     */
    public function showRegister()
    {
        return view('auth.register'); 
    }

    /**
     * [UPDATE] PROSES REGISTER (DAFTAR BARU)
     * URL: /register (POST)
     */
    public function register(Request $request)
    {
        // 1. VALIDASI KETAT
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Email harus unik (gak boleh ada yang sama di tabel users)
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // Password min 8 char & harus match sama kolom 'password_confirmation'
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // [FIX ERROR] GENERATE USERNAME OTOMATIS
        // Karena database butuh kolom 'username' (Not Null), kita bikin otomatis aja.
        // Ambil nama depan dari email + angka random. Contoh: jhon@test.com -> jhon8821
        $generatedUsername = explode('@', $validated['email'])[0] . rand(1000, 9999);

        // 2. SIMPAN KE DATABASE
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            
            
            'username' => $generatedUsername, 

            // PENTING: Password WAJIB di-hash pake Hash::make(). Jangan simpan polos!
            'password' => Hash::make($validated['password']),
            // Set default role jadi 'customer'. Admin cuma bisa dibuat lewat Seeder/Database langsung.
            'role' => 'customer',
        ]);

        // 3. AUTO LOGIN (UX EXPERT)
        // Abis daftar, ngapain suruh login lagi? Langsung masukin aja.
        Auth::login($user);

        // 4. REDIRECT
        // Bawa ke home dengan pesan sukses.
        return redirect('/')->with('success', 'Welcome recruit! Registration successful.');
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

        // Tendang balik ke halaman utama
        return redirect('/');
    }
}

/*
========== CATATAN LOGIKA (URG) ==========

Ini otak dari sistem keamanan CoreLogic.

1. LOGIN DUAL MODE:
   Fitur `filter_var` tetep urg pertahanin. Ini fitur keren biar user bisa login pake username ATAU email.

2. REGISTER FLOW (UPDATE):
   - Validasi `confirmed` di password itu wajib.
   - [PENTING] Auto-Generate Username: Urg tambahin logika `explode` + `rand` biar kolom username terisi otomatis dari email.
     Ini solusi biar gak kena error SQL "username cannot be null".
   - `Hash::make()` itu harga mati buat keamanan password.
   - `Auth::login($user)` itu trik UX biar user langsung masuk abis daftar.

3. ROLE REDIRECT (FIX 403):
   - Admin: Ke `route('admin.dashboard')`.
   - Customer: Ke `redirect('/')` (Hapus 'intended' biar gak nyasar ke halaman admin bekas history).

4. SECURITY:
   Semua input divalidasi (`$request->validate`).
   Session di-regenerate pas login (Cegah Session Fixation).
   Token CSRF di-regenerate pas logout.
*/