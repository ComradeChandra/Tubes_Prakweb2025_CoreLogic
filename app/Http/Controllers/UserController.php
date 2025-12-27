<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
