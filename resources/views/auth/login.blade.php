<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CoreLogic</title>
    
    <!-- 
        Pake Tailwind CDN buat styling cepet. 
        Nanti kalau udah production, ganti pake Vite build biar lebih kenceng load-nya.
    -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<!-- 
    Body dikasih warna gelap (gray-900) biar kesan-nya elegan & misterius.
    Text warna abu terang (gray-200) biar enak dibaca di background gelap.
-->
<body class="bg-gray-900 text-gray-200 font-sans antialiased">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <!-- LOGO & BRANDING -->
        <div class="mb-6 text-center">
            <!-- Judul Besar: CoreLogic -->
            <h1 class="text-2xl font-bold tracking-wider text-white uppercase"> 
                CoreLogic <span class="text-red-600">Security Solutions</span>
            </h1>
            <!-- Subjudul: Keliatan profesional, padahal aslinya jualan jasa 'keamanan' -->
            <p class="text-xs text-red-500 tracking-[0.3em] mt-1 font-bold">INTEGRATED SECURITY SYSTEMS</p>
        </div>

        <!-- KOTAK FORM LOGIN -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-gray-800 shadow-2xl border-t-4 border-red-700 rounded-lg">
            
            <!-- 
                ALERT ERROR:
                Ini bakal muncul kalau login gagal (misal password salah).
                $errors->any() itu ngecek "Ada error gak dari controller?".
            -->
            @if ($errors->any())
                <div class="mb-4 bg-red-900/20 border border-red-600 text-red-400 px-4 py-3 rounded relative text-sm" role="alert">
                    <strong class="font-bold tracking-wide uppercase">Access Denied</strong>
                    <span class="block sm:inline mt-1">{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- FORM START -->
            <form method="POST" action="{{ route('login.post') }}">
                
                <!-- CSRF Token: Wajib ada di Laravel biar form gak ditolak server -->
                @csrf 

                <!-- INPUT 1: USERNAME / EMAIL -->
                <div class="mb-4">
                    <label for="login_identifier" class="block text-sm font-bold text-gray-400 mb-1 uppercase tracking-wider">
                        Client ID / Email
                    </label>
                    
                    <!-- 
                        name="login_identifier":
                        Sengaja gak dikasih nama 'email', biar bisa nerima Username ATAU Email.
                        Nanti di Controller dicek ini formatnya email atau bukan.
                    -->
                    <input type="text" name="login_identifier" id="login_identifier" required autofocus
                        class="w-full bg-gray-900 border border-gray-700 text-white rounded focus:ring-2 focus:ring-red-600 focus:border-red-600 p-2.5 placeholder-gray-600 transition-all duration-300"
                        placeholder="Enter your ID...">
                </div>

                <!-- INPUT 2: PASSWORD -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-bold text-gray-400 mb-1 uppercase tracking-wider">
                        Password
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full bg-gray-900 border border-gray-700 text-white rounded focus:ring-2 focus:ring-red-600 focus:border-red-600 p-2.5 placeholder-gray-600 transition-all duration-300"
                        placeholder="••••••••">
                </div>

                <!-- REMEMBER ME & FORGOT PASSWORD -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm text-gray-400 hover:text-gray-300 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-red-600 bg-gray-900 border-gray-600 rounded focus:ring-red-600 focus:ring-offset-gray-800">
                        <span class="ml-2">Keep me logged in</span>
                    </label>
                    
                    <a href="#" class="text-sm text-red-500 hover:text-red-400 transition-colors">Forgot password?</a>
                </div>

                <!-- TOMBOL LOGIN -->
                <button type="submit" 
                    onclick="this.innerHTML='LOGGING IN...'; this.disabled=true; this.form.submit();"
                    class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-900 font-bold rounded text-sm px-5 py-3 text-center transition-all tracking-widest uppercase shadow-lg shadow-red-900/50">
                    Secure Login
                </button>
            </form>

            <!-- LINK REGISTER -->
            <div class="mt-6 text-center border-t border-gray-700 pt-4">
                <p class="text-sm text-gray-500">
                    Not a partner yet? 
                    <a href="{{ url('/register') }}" class="text-red-500 hover:text-red-400 font-bold transition-colors">
                        Apply for Access
                    </a>
                </p>
            </div>
        </div>
        
        <!-- FOOTER COPYRIGHT -->
        <div class="mt-8 text-gray-600 text-xs text-center">
            &copy; 2025 CoreLogic Systems. <br>Confidential & Proprietary.
        </div>
    </div>

</body>
</html>

<?php
/*
========== CATATAN PRIBADI (JANGAN DIHAPUS BIAR GAK LUPA) ==========

Ini halaman LOGIN. Urg desain biar keliatan "Corporate" tapi agak gelap (Dark Mode).
Biar kerasa kalau ini perusahaan serius (isinya jualan jasa Keamanan Profesional).

1. KENAPA PAKE 'login_identifier'?
   Di form input, urg kasih name="login_identifier" BUKAN "email".
   Kenapa? Biar user bisa login pake USERNAME atau EMAIL.
   Jadi fleksibel, gak kaku harus email doang.
   Nanti di Controller (AuthController), urg bakal cek input ini cocoknya sama kolom mana.

2. DESAIN & TAILWIND:
   - Urg pake bg-gray-900 biar gelap pekat, hemat mata kalau coding malem.
   - Aksen warna merah (red-600/red-700) buat branding CoreLogic.
   - Font pake tracking-wider biar keliatan tegas & modern.

3. KEAMANAN (@csrf):
   Itu ada tag @csrf di dalem form. JANGAN DIHAPUS.
   Itu token keamanan dari Laravel biar form-nya gak dibajak orang (CSRF Protection).
   Kalau dihapus, form gak bakal bisa disubmit (Error 419 Page Expired).

4. ERROR HANDLING:
   Bagian @if ($errors->any()) itu buat nampilin pesan error kalau login gagal.
   Misal: "Password salah" atau "User gak ketemu".
   Kotaknya merah biar user langsung 'ngeh' kalau ada yang salah.
*/
?>