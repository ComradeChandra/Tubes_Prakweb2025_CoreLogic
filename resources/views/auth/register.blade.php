<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Registration - CoreLogic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&display=swap');
        body { font-family: 'Chakra Petch', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen relative overflow-hidden">

    {{-- Background Accents --}}
    <div class="absolute top-0 left-0 w-full h-2 bg-red-700 z-50"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-red-900/10 rounded-full blur-3xl -mr-20 -mb-20"></div>
    <div class="absolute top-0 left-0 w-96 h-96 bg-gray-800/20 rounded-full blur-3xl -ml-20 -mt-20"></div>

    <div class="w-full max-w-md bg-gray-800/80 backdrop-blur-md p-8 rounded-lg shadow-2xl border border-gray-700 relative z-10">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-xl font-bold text-white tracking-wider uppercase">
                CoreLogic <span class="text-red-600">Security Solutions</span>
            </h1>
            <p class="text-red-500 text-xs font-semibold tracking-widest mt-2">CORPORATE PARTNER REGISTRATION</p>
        </div>

        {{-- Form Register --}}
        <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            {{-- Name Input --}}
            <div>
                <label for="name" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Full Name / Representative</label>
                <input type="text" name="name" id="name" required 
                    class="w-full bg-gray-900/50 border border-gray-600 text-white text-sm rounded focus:ring-red-500 focus:border-red-500 block p-2.5 placeholder-gray-600" 
                    placeholder="Enter your full name">
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Email Input --}}
            <div>
                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
                <input type="email" name="email" id="email" required 
                    class="w-full bg-gray-900/50 border border-gray-600 text-white text-sm rounded focus:ring-red-500 focus:border-red-500 block p-2.5 placeholder-gray-600" 
                    placeholder="name@company.com">
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- NIK Input --}}
            <div>
                <label for="nik" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">NIK (National ID)</label>
                <input type="text" name="nik" id="nik" required 
                    class="w-full bg-gray-900/50 border border-gray-600 text-white text-sm rounded focus:ring-red-500 focus:border-red-500 block p-2.5 placeholder-gray-600" 
                    placeholder="16 Digit NIK">
                @error('nik') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- ID Card Upload (KTP) --}}
            <div>
                <label for="id_card" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Upload ID Card (KTP)</label>
                <input type="file" name="id_card" id="id_card" required accept="image/*"
                    class="w-full text-sm text-gray-400 border border-gray-600 rounded cursor-pointer bg-gray-900/50 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500">JPG, PNG, or PDF (Max. 2MB). Required for verification.</p>
                @error('id_card') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Password Input --}}
            <div>
                <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Password</label>
                <input type="password" name="password" id="password" required 
                    class="w-full bg-gray-900/50 border border-gray-600 text-white text-sm rounded focus:ring-red-500 focus:border-red-500 block p-2.5 placeholder-gray-600" 
                    placeholder="Min. 8 characters">
                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Confirm Password Input (PENTING!) --}}
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required 
                    class="w-full bg-gray-900/50 border border-gray-600 text-white text-sm rounded focus:ring-red-500 focus:border-red-500 block p-2.5 placeholder-gray-600" 
                    placeholder="Repeat password">
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded-sm text-sm px-5 py-3 text-center tracking-widest shadow-lg shadow-red-900/50 transition-all mt-4">
                CREATE ACCOUNT
            </button>

            {{-- Link to Login --}}
            <div class="text-sm font-medium text-gray-400 text-center mt-6">
                Already have an account? <a href="{{ route('login') }}" class="text-red-500 hover:underline hover:text-red-400 transition">Login Here</a>
            </div>
        </form>
    </div>

    <div class="absolute bottom-4 text-gray-600 text-xs">
        © 2025 CoreLogic Solutions. Integrated Security.
    </div>

</body>
</html>

<?php
/*
========== CATATAN PRIBADI (JANGAN DIHAPUS BIAR GAK LUPA) ==========

Ini halaman REGISTER.
Urg udah ganti semua istilah "Recruitment/Protocol" jadi bahasa korporat normal ("Partner/Registration").
Biar gak dikira situs rekrutmen tentara bayaran beneran sama asdos.

1. FORM INPUT:
   - Name: "Full Name / Representative" (Biar kesannya B2B).
   - Email: "Email Address" (Normal).
   - Password: "Password" (Gak usah pake istilah 'Security Code' lagi).

2. DESAIN:
   - Masih pake tema gelap & merah (CoreLogic Branding).
   - Tapi teks-nya lebih sopan & profesional.

3. LOGIKA:
   - Form ini ngirim data ke route 'register.post'.
   - Controller bakal otomatis bikin user baru dengan role 'customer' (default).
*/
?>