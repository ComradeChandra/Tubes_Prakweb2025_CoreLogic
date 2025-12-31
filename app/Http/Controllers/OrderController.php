<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * [USER] Export PDF Order
     * Route: GET /my-orders/{order}/pdf
     */
    public function exportPdf($id)
    {
        $order = Order::with(['service', 'user'])->where('user_id', Auth::id())->findOrFail($id);
        $data = [ 'order' => $order ];
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('users.order_pdf', $data);
        $filename = 'Order_' . $order->id . '_' . date('Ymd') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * [USER] DETAIL ORDER (OWNERSHIP CHECK)
     * Route: GET /my-orders/{order}
     *
     * Logika:
     * - Pastikan order milik user yang sedang login (Auth::id()).
     * - Jika tidak, kembalikan 404 (security by ownership).
     * - Tampilkan detail order beserta tombol download PDF (jika diizinkan).
     */
    public function showUser($id)
    {
        $order = Order::with(['service', 'user'])->where('user_id', Auth::id())->findOrFail($id);

        return view('users.show', compact('order'));
    }

    /**
     * [ADMIN] DETAIL ORDER
     * Route: GET /admin/orders/{order}
     */
    public function show($id)
    {
        $order = Order::with(['user', 'service'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
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
        // 1. CEK KTP USER
        $user = Auth::user();
        if (!$user->id_card_path || !$user->ktp_verified) {
            return back()->withErrors(['order' => 'KTP belum diupload atau belum terverifikasi. Silakan upload KTP dan tunggu verifikasi sebelum melakukan order.']);
        }

        // 2. VALIDASI INPUT
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'quantity'   => 'required|integer|min:1', 
            'start_date' => 'required|date|after_or_equal:today', // Gak boleh tanggal kemaren
            'end_date'   => 'required|date|after:start_date',     // Harus setelah start date
            'notes'      => 'nullable|string|max:1000',
            'address'    => 'required|string|max:1000', // Alamat pengiriman wajib
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        // 3. AMBIL DATA SERVICE ASLI DARI DB
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
        $subtotal = $service->price * $validated['quantity'] * $weeks;

        // [PRAK-20] MEMBERSHIP TIER LOGIC (GAMIFICATION)
        // Cek Tier User buat kasih diskon
        $user = Auth::user();
        $discount = 0;

        if ($user->tier === 'VIP') {
            $discount = $subtotal * 0.10; // Diskon 10% buat VIP
        } elseif ($user->tier === 'Elite') {
            $discount = $subtotal * 0.20; // Diskon 20% buat Elite
        }

        $finalPrice = $subtotal - $discount;

        // 4. SIMPAN KE DATABASE
        // Masukin semua data ke tabel 'orders'
        Order::create([
            'user_id'     => Auth::id(), 
            'service_id'  => $service->id,
            'quantity'    => $validated['quantity'],
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'total_price' => $finalPrice, // Harga setelah diskon
            'discount'    => $discount,   // Catat diskonnya
            'status'      => 'PENDING', 
            'notes'       => $validated['notes'] ?? null,
            'address'     => $validated['address'],
            'latitude'    => $validated['latitude'] ?? null,
            'longitude'   => $validated['longitude'] ?? null,
        ]);

        // [AUTO-TIER UPDATE]
        // Cek total belanja user setelah order ini (kalau nanti diapprove)
        // Tapi logic update tier sebaiknya pas order di-APPROVE admin, bukan pas create.
        // Jadi logic update tier kita taruh di method updateStatus aja.

        // 5. REDIRECT
        // Balikin ke katalog sambil bawa pesan sukses
        return redirect('/catalog')->with('success', 'Order berhasil dibuat! Kamu hemat $' . number_format($discount, 2));
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

        // [PRAK-20] AUTO-TIER UPDATE LOGIC
        // Kalau order di-APPROVE, kita cek total belanja user buat naik level.
        if ($validated['status'] === 'APPROVED') {
            $user = $order->user;
            
            // Hitung total belanja user yang statusnya APPROVED
            $totalSpent = $user->orders()->where('status', 'APPROVED')->sum('total_price');

            // Cek Threshold Tier
            if ($totalSpent >= 50000) {
                $user->update(['tier' => 'Elite']);
            } elseif ($totalSpent >= 10000) {
                $user->update(['tier' => 'VIP']);
            }
        }

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
