@extends('layouts.app')

@section('content')
<section class="bg-gray-900 py-20 min-h-screen">
    <div class="max-w-3xl mx-auto px-6 text-white text-center">

        <h1 class="text-3xl font-extrabold mb-6">
            Confirm Your Order
        </h1>

        <p class="text-lg mb-4">
            You are about to hire:
        </p>

        <p class="text-2xl font-bold text-red-500 mb-6">
            {{ $service->name }}
        </p>

        <p class="mb-8 text-gray-300">
            Price:
            <span class="font-bold">
                $ {{ number_format($service->price, 2, '.', ',') }}
            </span>
        </p>

        <!-- FORM ORDER (UPDATED BY CHANDRA) -->
        <!-- Action ngarah ke route 'orders.store' buat diproses Backend -->
        <form action="{{ route('orders.store') }}" method="POST" class="max-w-md mx-auto bg-gray-800 p-6 rounded-lg border border-gray-700">
            @csrf <!-- Token Keamanan Wajib -->
            
            <!-- ID Service (Hidden) biar backend tau unit apa yg dibeli -->
            <input type="hidden" name="service_id" value="{{ $service->id }}">

            <!-- Input Quantity -->
            <div class="mb-4 text-left">
                <label class="block text-sm font-bold mb-2">Quantity (Personel/Unit)</label>
                <input type="number" name="quantity" value="1" min="1" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white focus:border-red-500 focus:outline-none">
            </div>

            <!-- Input Date Range (Added by Chandra) -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-left">
                    <label class="block text-sm font-bold mb-2">Start Date</label>
                    <!-- type="date" ini yang bikin muncul kalender otomatis dari browser -->
                    <!-- min="{{ date('Y-m-d') }}" biar gak bisa pilih tanggal masa lalu -->
                    <input type="date" name="start_date" id="start_date" min="{{ date('Y-m-d') }}" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white focus:border-red-500 focus:outline-none">
                </div>
                <div class="text-left">
                    <label class="block text-sm font-bold mb-2">End Date</label>
                    <input type="date" name="end_date" id="end_date" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white focus:border-red-500 focus:outline-none">
                </div>
            </div>
            <p class="text-xs text-gray-400 mb-4 text-left">*Price is calculated per week. Partial weeks are rounded up.</p>

            <!-- ESTIMASI HARGA (Added by Chandra) -->
            <div class="mb-6 p-4 bg-gray-900 rounded border border-gray-600">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-400 text-sm">Duration:</span>
                    <span id="duration_display" class="font-bold text-white">- Weeks</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Total Estimated:</span>
                    <span id="total_display" class="text-xl font-bold text-green-400">$ 0.00</span>
                </div>
            </div>

            <!-- Input Notes -->
            <div class="mb-6 text-left">
                <label class="block text-sm font-bold mb-2">Mission Notes (Optional)</label>
                <textarea name="notes" rows="3" class="w-full p-2 rounded bg-gray-700 border border-gray-600 text-white focus:border-red-500 focus:outline-none" placeholder="Specific requirements..."></textarea>
            </div>

            <!-- SCRIPT VALIDASI & KALKULATOR HARGA -->
            <!-- 
                [CATATAN CHANDRA - FRONTEND LOGIC]
                Script ini tugasnya ada 2:
                1. UX Protection: Mencegah user milih tanggal yang gak masuk akal (Masa lalu / End date sebelum Start date).
                2. Live Calculator: Biar user tau estimasi harga sebelum kaget pas checkout.
            -->
            <script>
                const startInput = document.getElementById('start_date');
                const endInput = document.getElementById('end_date');
                const qtyInput = document.querySelector('input[name="quantity"]');
                const durationDisplay = document.getElementById('duration_display');
                const totalDisplay = document.getElementById('total_display');
                
                // Ambil harga dari PHP (Server-side variable di-inject ke JS)
                const basePrice = {{ $service->price }};

                function calculateTotal() {
                    const qty = parseInt(qtyInput.value) || 1;
                    const start = new Date(startInput.value);
                    const end = new Date(endInput.value);

                    // Kalo tanggal belum lengkap, jangan hitung dulu
                    if (isNaN(start.getTime()) || isNaN(end.getTime())) {
                        durationDisplay.innerText = "- Weeks";
                        totalDisplay.innerText = "$ 0.00";
                        return;
                    }

                    // Hitung selisih waktu (ms)
                    const diffTime = end - start;
                    
                    // Kalau tanggal end sebelum start, jangan hitung (Invalid)
                    if (diffTime < 0) return;

                    // Konversi ke hari 
                    // Ditambah 1 karena hitungannya inklusif (Tgl 1 s/d Tgl 1 = 1 Hari)
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    const days = Math.max(diffDays, 0) + 1; 

                    // [LOGIKA BISNIS]: Minimum Charge 1 Minggu
                    // Walaupun sewa cuma 2 hari, tetep bayar 1 minggu.
                    // Math.ceil() dipake buat pembulatan ke atas.
                    const weeks = Math.ceil(days / 7);

                    // Hitung Total: Harga x Quantity x Minggu
                    const total = basePrice * qty * weeks;

                    // Update Tampilan (Format Currency USD)
                    durationDisplay.innerText = weeks + " Week(s)";
                    totalDisplay.innerText = "$ " + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }

                // Event Listeners
                startInput.addEventListener('change', function() {
                    // Pas start date dipilih, set min date buat end date
                    // Biar user gak bisa pilih tanggal mundur (End < Start)
                    endInput.min = this.value;
                    
                    // Kalo tanggal end yang udah dipilih ternyata lebih kecil dari start baru, reset aja
                    if (endInput.value && endInput.value < this.value) {
                        endInput.value = '';
                    }
                    calculateTotal();
                });

                endInput.addEventListener('change', calculateTotal);
                qtyInput.addEventListener('input', calculateTotal);
            </script>

            <div class="flex justify-between gap-4">
                <a href="/catalog"
                   class="w-1/2 bg-gray-600 hover:bg-gray-500 py-2 rounded-lg text-center transition">
                    Cancel
                </a>

                <button type="submit" class="w-1/2 bg-red-600 hover:bg-red-700 py-2 rounded-lg font-bold transition">
                    Confirm Hire
                </button>
            </div>
        </form>

    </div>
</section>
@endsection
