<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;


class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update data profile user (Nama, Email, Password, Avatar).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Max 10MB
            'password' => 'nullable|string|min:8|confirmed', // Confirmed: harus sama dengan password_confirmation
            'id_card' => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
            'phone' => 'nullable|string|max:32',
            'nik' => 'nullable|numeric|digits_between:16,16',
            'address' => 'nullable|string|max:1000',
        ]);

        // 2. Update Data Dasar
        $user->name = $request->name;
        $user->email = $request->email;

        // 3. Update Password (Kalau diisi)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 4. Handle Upload Avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama kalau ada (dan bukan default DiceBear)
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Simpan avatar baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // 4b. Handle ID Card Upload (KTP)
        if ($request->hasFile('id_card')) {
            // Hapus file KTP lama kalau ada
            if ($user->id_card_path && Storage::exists('public/' . $user->id_card_path)) {
                Storage::delete('public/' . $user->id_card_path);
            }

            // Simpan KTP baru
            $idPath = $request->file('id_card')->store('id_cards', 'public');
            $user->id_card_path = $idPath;

            // Reset verification flag (but keep admin responsible to verify)
            $user->ktp_verified = false;
        }

        // 5. Other optional fields
        $user->phone = $request->phone ?? $user->phone;
        $user->nik = $request->nik ?? $user->nik;
        $user->address = $request->address ?? $user->address;

        // 6. Simpan ke Database
        $user->save();

        // 7. Inform user if they uploaded new KTP
        if ($request->hasFile('id_card')) {
            return redirect()->route('profile.edit')->with('success', 'KTP berhasil diupload. Menunggu verifikasi admin.');
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Mark all user notifications as read
     */
    public function markNotificationsRead()
    {
        $user = Auth::user();
        $user->userNotifications()->where('is_read', false)->update(['is_read' => true]);

        return redirect()->route('profile.edit')->with('success', 'All notifications marked as read.');
    }
}


/*
========== CATATAN PENGEMBANG (Profil) ==========
File ini menangani _edit_ dan _update_ data profil user.

Tujuan & Alur:
- Menampilkan halaman edit profil (nama, email, avatar, KTP, contact info).
- Saat user upload KTP (id_card), file disimpan ke `storage/app/public/id_cards`.
- Upload ulang KTP otomatis mereset flag `ktp_verified` ke `false`.
  Verifikasi diselesaikan oleh admin melalui panel (UserController::verifyKtp).
- Saat admin memverifikasi / membatalkan verifikasi, sistem membuat notifikasi sederhana
  di tabel `user_notifications` sehingga user mendapat informasi di halaman profil.

Catatan penting:
- Jangan otomatis menyetujui verifikasi di sisi user; keputusan verifikasi hanya boleh
  melalui admin (untuk kepatuhan proses manual/verifikasi visual).
- Pesan flash digunakan untuk memberi umpan balik ke user (mis: "KTP berhasil diupload. Menunggu verifikasi admin.").
*/
