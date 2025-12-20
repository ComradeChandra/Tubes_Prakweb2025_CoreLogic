<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * FUNGSI: Proses simpan order baru
     * ROUTE: POST /orders
     * 
     * [CATATAN LOGIKA - CHANDRA]:
     * Ini bagian paling krusial di transaksi.
     * Urg sengaja gak ngambil harga dari input form HTML, karena user bisa aja iseng inspect element
     * terus ganti harganya jadi Rp 0.
     * 
     * Jadi solusinya:
     * 1. Terima ID Service-nya aja.
     * 2. Cari harganya di database server.
     * 3. Kalikan sama quantity.
     * 4. Baru simpan. Aman anti-cheat.
     */
    public function store(Request $request)
    {
        // 1. VALIDASI INPUT
        // Pastiin service_id nya beneran ada di tabel services
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'quantity'   => 'required|integer|min:1', 
            'start_date' => 'required|date|after_or_equal:today', // Gak boleh tanggal kemaren
            'end_date'   => 'required|date|after:start_date',     // Harus setelah start date
            'notes'      => 'nullable|string|max:1000', 
        ]);

        // 2. AMBIL DATA SERVICE ASLI DARI DB
        // Cari unit yang mau dibeli berdasarkan ID
        $service = Service::findOrFail($validated['service_id']);

        // 3. HITUNG DURASI & TOTAL HARGA
        // [CATATAN CHANDRA - BACKEND LOGIC]:
        // Ini backup logic dari Frontend. Walaupun di JS udah diitung,
        // Backend WAJIB ngitung ulang biar gak dicurangi user (Inspect Element).
        
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end   = \Carbon\Carbon::parse($validated['end_date']);
        
        // Hitung selisih hari
        $days = $start->diffInDays($end);
        
        // [LOGIKA BISNIS]: Pembulatan Minggu (Ceiling)
        // max($days, 1) -> Biar kalo selisih 0 hari (hari yang sama), tetep dianggep 1 hari.
        // ceil(... / 7) -> Pembulatan ke atas. 3 hari = 1 minggu.
        $weeks = ceil(max($days, 1) / 7); 

        // Rumus Final: Harga Unit (Per Minggu) * Jumlah Pesanan * Jumlah Minggu
        $totalPrice = $service->price * $validated['quantity'] * $weeks;

        // 4. SIMPAN KE DATABASE
        // Masukin semua data ke tabel 'orders'
        Order::create([
            'user_id'     => Auth::id(), 
            'service_id'  => $service->id,
            'quantity'    => $validated['quantity'],
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'total_price' => $totalPrice, 
            'status'      => 'PENDING', 
            'notes'       => $validated['notes'] ?? null,
        ]);

        // 5. REDIRECT
        // Balikin ke katalog sambil bawa pesan sukses
        return redirect('/catalog')->with('success', 'Order berhasil dibuat! Kontrak berlaku selama ' . $weeks . ' minggu.');
    }

    /**
     * [ADMIN] LIST SEMUA ORDER
     * Route: GET /admin/orders
     */
    public function indexAdmin()
    {
        // Ambil semua order, urutkan dari yang terbaru
        // with(['user', 'service']) -> Eager Loading biar query gak berat (N+1 Problem)
        $orders = Order::with(['user', 'service'])->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * [ADMIN] UPDATE STATUS ORDER
     * Route: PATCH /admin/orders/{id}/status
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Validasi input status cuma boleh 'APPROVED' atau 'REJECTED'
        $validated = $request->validate([
            'status' => 'required|in:APPROVED,REJECTED'
        ]);

        // Update status di database
        $order->update([
            'status' => $validated['status']
        ]);

        // Balik lagi ke halaman list dengan pesan sukses
        return redirect()->route('admin.orders.index')
            ->with('success', 'Order status updated to ' . $validated['status']);
    }

    /**
     * [USER] ORDER HISTORY
     * Route: GET /my-orders
     */
    public function history()
    {
        // Ambil order punya user yang lagi login aja
        $orders = Order::where('user_id', Auth::id())
                       ->with('service') // Eager load service biar gak N+1
                       ->latest()
                       ->get();

        return view('users.history', compact('orders'));
    }
}
