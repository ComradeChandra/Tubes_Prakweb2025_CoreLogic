<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema; // dipakai untuk cek kolom di DB (mencegah error SQL saat migrasi belum dijalankan)


class UserController extends Controller
{
    /**
     * [ADMIN] List semua user yang terdaftar.
     */
    public function index()
    {
        // Ambil semua user, urutkan dari yang terbaru
        // Kecualikan admin sendiri biar gak salah hapus diri sendiri
        $users = User::where('role', '!=', 'admin')
                     ->latest()
                     ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * [ADMIN] Lihat detail user + history order mereka.
     */
    public function show($id)
    {
        $user = User::with(['orders.service'])->findOrFail($id);
        
        // Hitung total belanja user ini (buat verifikasi Tier)
        $totalSpent = $user->orders->where('status', 'APPROVED')->sum('total_price');

        return view('admin.users.show', compact('user', 'totalSpent'));
    }

    /**
     * [ADMIN] Hapus user (Banned).
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Hapus user beserta semua orderannya (Cascade Delete di Database)
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User has been removed from the system.');
    }

    /**
     * [ADMIN] Verify user's KTP (set ktp_verified = true)
     *
     * Catatan pengembang:
     * - Method ini dipanggil dari halaman admin (User details) ketika admin menekan tombol "Verify".
     * - Efek samping: membuat record notifikasi sederhana untuk memberi tahu user.
     * - Verifikasi ini bersifat manual dan memerlukan pemeriksaan visual terhadap file KTP.
     *
     * Cara tes singkat:
     * 1. Login sebagai admin, buka detail user yang punya KTP.
     * 2. Klik Verify.
     * 3. Cek bahwa kolom `ktp_verified` di DB berubah menjadi true dan user menerima notifikasi.
     */
    public function verifyKtp(User $user)
    {
        // Safety check: pastikan kolom ktp_verified ada di database.
        // Jika belum, beri tahu admin untuk menjalankan migration (php artisan migrate).
        if (!Schema::hasColumn('users', 'ktp_verified')) {
            return back()->with('error', 'Kolom `ktp_verified` belum tersedia di database. Jalankan `php artisan migrate` terlebih dahulu.');
        }

        try {
            $user->update(['ktp_verified' => true]);

            // Buat notifikasi sederhana agar user tahu KTP-nya sudah terverifikasi
            \App\Models\UserNotification::create([
                'user_id' => $user->id,
                'title' => 'KTP Terverifikasi',
                'message' => 'KTP Anda telah diverifikasi oleh administrator. Anda sekarang dapat melakukan order.',
            ]);

            return back()->with('success', 'User KTP marked as verified.');
        } catch (\Exception $e) {
            // Jika terjadi error DB, tangkap dan beri pesan yang jelas
            return back()->with('error', 'Gagal memverifikasi KTP: ' . $e->getMessage());
        }
    }

    /**
     * [ADMIN] Unverify user's KTP (set ktp_verified = false)
     *
     * Catatan pengembang:
     * - Gunakan ini jika verifikasi perlu dicabut (mis: data tidak valid).
     */
    public function unverifyKtp(User $user)
    {
        // Safety check: pastikan kolom ktp_verified ada di database.
        if (!Schema::hasColumn('users', 'ktp_verified')) {
            return back()->with('error', 'Kolom `ktp_verified` belum tersedia di database. Jalankan `php artisan migrate` terlebih dahulu.');
        }

        try {
            $user->update(['ktp_verified' => false]);

            \App\Models\UserNotification::create([
                'user_id' => $user->id,
                'title' => 'KTP Tidak Terverifikasi',
                'message' => 'KTP Anda ditandai sebagai tidak terverifikasi oleh administrator. Silakan upload ulang KTP.',
            ]);

            return back()->with('success', 'User KTP marked as unverified.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status verifikasi: ' . $e->getMessage());
        }
    }

}
