<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Admin dengan statistik lengkap.
     * 
     * [CATATAN CHANDRA]:
     * Controller ini dibuat khusus buat Dashboard Admin.
     * Logic perhitungan semua dipindahin ke sini biar View blade-nya clean (sesuai MVC).
     */
    public function index()
    {
        // ========== STATS DASAR ==========
        // Hitung total data untuk ditampilkan di dashboard
        $totalServices = Service::count();
        $totalCategories = Category::count();
        $pendingOrders = Order::where('status', 'PENDING')->count();
        
        // ========== STATS BARU (UPDATE BY CHANDRA) ==========
        
        // 1. Total Orders (Semua order tanpa filter)
        $totalOrders = Order::count();
        
        // 2. Total Users (Semua user yang terdaftar)
        $totalUsers = User::count();
        
        // 3. Total Revenue (Pemasukan dari order APPROVED)
        // PENTING: Kita SUM kolom 'total_price' dari tabel orders yang statusnya APPROVED.
        // Kenapa APPROVED? Karena itu order yang udah jadi deal, bukan pending/rejected.
        $totalRevenue = Order::where('status', 'APPROVED')
                             ->sum('total_price');
        
        // ========== RECENT ORDERS WIDGET ==========
        // Ambil 5 orderan terbaru buat ditampilin di dashboard.
        // Gunain 'with()' biar load relasi user dan service sekalian (Eager Loading).
        // Ini biar gak kena masalah N+1 Query pas looping di blade.
        $recentOrders = Order::with(['user', 'service'])
                             ->latest()
                             ->take(5)
                             ->get();
        
        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalServices',
            'totalCategories',
            'pendingOrders',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'recentOrders'
        ));
    }

    /**
     * Method untuk ambil data laporan penjualan bulanan
     * Nanti dipake buat generate PDF
     */
    public function getMonthlySalesData(Request $request)
    {
        // Ambil parameter bulan dan tahun dari request
        // Kalau gak ada, pake bulan sekarang
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // Query orders berdasarkan bulan dan tahun
        $orders = Order::with(['user', 'service'])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->where('status', 'APPROVED') // Cuma yang approved
                    ->get();

        // Hitung total revenue bulan ini
        $monthlyRevenue = $orders->sum('total_price');

        return [
            'orders' => $orders,
            'monthlyRevenue' => $monthlyRevenue,
            'month' => $month,
            'year' => $year,
            'totalOrders' => $orders->count()
        ];
    }

    /**
     * Generate dan download PDF laporan penjualan bulanan
     */
    public function downloadMonthlySalesReport(Request $request)
    {
        // Ambil data pake method yang udah dibuat
        $data = $this->getMonthlySalesData($request);

        // Generate PDF dari view template
        $pdf = Pdf::loadView('pdf.monthly_sales', $data);

        // Download PDF dengan nama file yang jelas
        $filename = 'Laporan_Penjualan_' . $data['month'] . '_' . $data['year'] . '.pdf';

        return $pdf->download($filename);
    }
}
