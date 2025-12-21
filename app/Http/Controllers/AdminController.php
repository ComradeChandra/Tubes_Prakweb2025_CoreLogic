<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Admin dengan ringkasan data.
     */
    public function dashboard()
    {
        // Hitung total data untuk ditampilkan di dashboard
        $totalServices = Service::count();
        $totalCategories = Category::count();
        $pendingOrders = Order::where('status', 'PENDING')->count();

        // Kirim data ke view
        return view('admin.dashboard', compact('totalServices', 'totalCategories', 'pendingOrders'));
    }
}
