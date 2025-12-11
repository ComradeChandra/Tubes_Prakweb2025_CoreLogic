<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/*
========== ROLE MIDDLEWARE - AUTHORIZATION ==========

FUNGSI FILE INI:
Middleware ini buat ngecek apakah user punya role/permission tertentu.
Contoh: Cuma user dengan role 'admin' yang boleh akses halaman admin.

CARA KERJA:
1. User login -> Auth::user() ada datanya
2. Middleware cek role user cocok gak dengan role yang diminta
3. Kalau cocok -> lanjut ke controller
4. Kalau gak cocok -> redirect dengan error message

KENAPA PERLU MIDDLEWARE INI?
Tanpa middleware ini, SEMUA user yang udah login bisa akses halaman admin.
Customer bisa hapus kategori, staff bisa edit harga, dll -> BAHAYA!
Dengan middleware ini, cuma admin yang bisa akses admin panel.

CARA PAKAI:
Di routes/web.php, tambahkan middleware ke route:

Contoh 1: Cuma admin yang boleh akses
Route::get('/admin/categories', [CategoryController::class, 'index'])
     ->middleware('auth', 'role:admin');

Contoh 2: Admin atau staff yang boleh akses
Route::get('/admin/reports', [ReportController::class, 'index'])
     ->middleware('auth', 'role:admin,staff');

PARAMETER:
- $roles: String atau array role yang diizinkan
  Contoh: 'admin' atau 'admin,staff' atau ['admin', 'staff']

SECURITY NOTE:
Middleware ini harus dipake SETELAH middleware 'auth'
Urutan penting: auth -> role
Kenapa? Karena Auth::user() baru ada SETELAH middleware auth dijalankan
*/

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * LOGIC:
     * 1. Cek user udah login atau belum (via Auth::check())
     * 2. Cek role user cocok gak dengan $roles yang diminta
     * 3. Kalau gak cocok -> abort 403 (Forbidden)
     * 4. Kalau cocok -> next($request) lanjut ke controller
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Satu atau lebih role yang diizinkan (contoh: 'admin', 'staff')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // STEP 1: CEK USER SUDAH LOGIN ATAU BELUM
        // Kalau belum login, redirect ke halaman login
        // Sebenarnya ini redundan karena harusnya udah dipake middleware 'auth' sebelumnya
        // Tapi urg tambahin buat double safety
        if (!auth()->check()) {
            return redirect()->route('login')
                           ->with('error', 'Silakan login terlebih dahulu.');
        }

        // STEP 2: AMBIL DATA USER YANG LOGIN
        $user = auth()->user();

        // STEP 3: CEK ROLE USER COCOK GAK DENGAN ROLE YANG DIMINTA
        // in_array() -> cek apakah role user ada di dalam array $roles
        //
        // Contoh:
        // User role: 'admin'
        // $roles: ['admin', 'staff']
        // Hasil: TRUE (admin ada di array) -> BOLEH AKSES
        //
        // User role: 'customer'
        // $roles: ['admin', 'staff']
        // Hasil: FALSE (customer gak ada di array) -> GAK BOLEH AKSES
        if (!in_array($user->role, $roles)) {
            // Kalau role gak cocok, abort dengan HTTP 403 Forbidden
            // 403 = Forbidden (server paham requestnya, tapi gak dikasih izin)
            //
            // Urg bisa custom pesan error atau redirect ke halaman custom 403
            // Sekarang pake abort() default Laravel (akan muncul halaman 403 bawaan)
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');

            // ALTERNATIVE: Redirect ke halaman tertentu dengan pesan error
            // return redirect()->route('home')
            //              ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // STEP 4: KALAU SEMUA CEK LOLOS, LANJUT KE CONTROLLER
        return $next($request);
    }
}

/*
========== CATATAN TAMBAHAN UNTUK DEVELOPER ==========

1. REGISTRASI MIDDLEWARE:
   Middleware ini harus didaftarkan dulu di bootstrap/app.php atau App\Http\Kernel.php (tergantung versi Laravel).

   Di Laravel 11/12 (yang kau pakai), tambahkan di bootstrap/app.php:
   ->withMiddleware(function (Middleware $middleware) {
       $middleware->alias([
           'role' => \App\Http\Middleware\RoleMiddleware::class,
       ]);
   })

   Setelah itu, baru bisa dipake dengan nama 'role'

2. CARA PAKAI DI ROUTES:
   Single role:
   ->middleware('role:admin')

   Multiple roles (salah satu aja cocok udah boleh):
   ->middleware('role:admin,staff')

   Dengan middleware auth:
   ->middleware(['auth', 'role:admin'])

3. ROUTE GROUP (RECOMMENDED):
   Kalau banyak route yang butuh role sama, pakai route group:

   Route::middleware(['auth', 'role:admin'])->group(function () {
       Route::resource('admin/categories', CategoryController::class);
       Route::resource('admin/services', ServiceController::class);
   });

4. VARIADIC PARAMETERS (...$roles):
   Parameter ...$roles itu variadic (terima unlimited arguments)
   Laravel otomatis split string 'admin,staff' jadi array ['admin', 'staff']
   Jadi urg bisa terima multiple roles dari route definition

5. HTTP STATUS CODES:
   - 401 Unauthorized = User belum login
   - 403 Forbidden = User udah login, tapi gak punya akses
   - 404 Not Found = Resource gak ada

   Di middleware ini urg pakai 403 karena user udah login tapi gak punya role

6. CUSTOM ERROR PAGE (OPTIONAL):
   Buat file resources/views/errors/403.blade.php
   Laravel otomatis pakai file itu kalau ada abort(403)

7. TESTING:
   Test dengan 3 skenario:
   a. User belum login -> redirect ke login
   b. User login tapi role gak cocok -> 403 error
   c. User login dan role cocok -> success akses

8. ALTERNATIVE IMPLEMENTATION:
   Kalau mau lebih fleksibel, bisa pakai package Laravel Permission (Spatie)
   Tapi buat project kecil ini, custom middleware udah cukup

9. SECURITY BEST PRACTICE:
   - SELALU pakai middleware 'auth' SEBELUM middleware 'role'
   - Jangan cuma andalkan frontend (hide button), backend harus tetap cek role
   - Log unauthorized access attempts (buat detect attack)

END OF FILE
*/
