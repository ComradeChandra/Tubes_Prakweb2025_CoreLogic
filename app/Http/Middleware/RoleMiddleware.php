<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/*
========== CATATAN BUAT NAUVAL & TIM (JANGAN DIHAPUS) ==========

Ini middleware buatan urg buat ngejagain halaman Admin.
Logikanya simpel: Cuma ngecek kolom 'role' di tabel users.

Kalau role-nya 'admin', boleh masuk ($next).
Kalau bukan (misal 'customer'), langsung urg tendang pake error 403.

Sengaja gak pake package permission kayak Spatie biar ringan & gak ribet config-nya.
Jadi kalau mau nambah role baru, tinggal tambahin logic di sini aja.
*/

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. CEK LOGIN DULU
        // Auth::check() -> mastiin user udah login atau belum.
        if (! Auth::check()) {
            return redirect('login');
        }

        // 2. AMBIL DATA USER
        $user = Auth::user();

        // 3. LOGIKA PENGECEKAN ROLE (INTI DARI FILE INI)
        // $roles itu parameter yang dikirim dari route (misal: 'admin').
        // Fungsi in_array ngecek: "Apakah role si user ada di daftar role yang dibolehin?"
        if (in_array($user->role, $roles)) {
            // Kalau COCOK -> Silakan lewat (Lanjut ke Controller)
            return $next($request);
        }

        // 4. KALAU GAK COCOK -> TENDANG
        // Abort 403 artinya "Forbidden" (Dilarang Masuk).
        abort(403, 'Maaf, Anda tidak punya akses ke halaman ini (Bukan Admin).');
    }
}

/*
========== CATATAN TAMBAHAN  ==========

1. REGISTRASI MIDDLEWARE:
   Middleware ini harus didaftarkan dulu di bootstrap/app.php atau App\Http\Kernel.php (tergantung versi Laravel).

   Di Laravel 11/12, tambahkan di bootstrap/app.php:
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
