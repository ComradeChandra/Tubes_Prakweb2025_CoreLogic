<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // TAMPILIN HALAMAN LOGIN
    public function showLogin()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        // 1. Validasi dulu biar gak error
        // Cek inputan user, gak boleh kosong
        $request->validate([
            'login_identifier' => 'required',
            'password' => 'required'
        ], [
            'login_identifier.required' => 'Please provide your email or username.',
            'password.required' => 'Password is required to proceed.'
        ]);

        // 2. Cek ini Email atau Username?
        // Pake str_contains aja biar gampang dipahami.
        // Logikanya: Kalo ada keong (@) berarti dia masukin email.
        // Kalo gak ada @ nya, ya berarti itu username. 
        
        $input = $request->login_identifier;
        
        if (str_contains($input, '@')) {
            // Ada @ nya, berarti email
            $fieldType = 'email';
        } else {
            // Gak ada @, berarti username
            $fieldType = 'username';
        }

        // 3. Coba login (Auth Attempt)
        // Kita gabungin data loginnya buat dicek sama Laravel
        $credentials = [
            $fieldType => $input,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            // Kalo sukses login:
            
            // Regenerate session biar aman dari hacker
            $request->session()->regenerate();

            // Cek role user, kalo admin lempar ke dashboard admin
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Kalo user biasa (customer), lempar ke home aja
            return redirect('/');
        }

        // Kalo gagal login (password salah atau user gak ada)
        // Balikin lagi ke halaman login
        return back()->withErrors([
            'login_identifier' => 'Invalid credentials. Please check your email/username and password.',
        ]);
    }

    // TAMPILIN HALAMAN REGISTER
    public function showRegister()
    {
        return view('auth.register');
    }

    // PROSES REGISTER
    public function register(Request $request)
    {
        // Validasi manual satu-satu
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed' // confirmed biar ngecek password sama confirm_password sama
        ], [
            'name.required' => 'Please enter your full name.',
            'name.max' => 'Name must not exceed 255 characters.',
            'email.required' => 'Please provide a valid email address.',
            'email.email' => 'The email format is invalid.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password requires at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.'
        ]);

        // Bikin username otomatis (Soalnya di database wajib ada kolom username)
        // Kita ambil dari nama depan email terus tambahin angka random.
        // Pake titik (.) buat gabungin string (belajar dari PHP dasar wkwk)
        
        $emailParts = explode('@', $request->email);
        $namaDepan = $emailParts[0]; 
        $angkaRandom = rand(100, 999);
        
        // Gabungin jadi username: contoh "chandra.123"
        $usernameJadi = $namaDepan . "." . $angkaRandom;

        // Simpan ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $usernameJadi,
            'password' => Hash::make($request->password), // Jangan lupa di-hash!
            'role' => 'customer' // Default user biasa
        ]);

        // Langsung login aja biar cepet, gak usah suruh login ulang
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        
        Auth::attempt($credentials);

        return redirect('/')->with('success', 'Registration successful. Welcome aboard!');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Bersihin session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
