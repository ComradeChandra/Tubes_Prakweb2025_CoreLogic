<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // TABEL TRANSAKSI (ORDERS)
        // Ini tempat nyimpen semua data pembelian unit.
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User (Siapa yang pesen?)
            // constrained() otomatis nyari tabel 'users' dan kolom 'id'
            // onDelete('cascade') artinya kalau user dihapus, orderannya juga ilang.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relasi ke Service (Unit apa yang dipesen?)
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            
            // Jumlah unit yang dipesen (Default 1)
            $table->integer('quantity')->default(1);

            // Tanggal Mulai & Selesai (Ganti duration_weeks jadi Date)
            // [CATATAN CHANDRA]:
            // Awalnya pake integer 'duration_weeks', tapi diganti jadi Date
            // biar lebih jelas kontraknya dari tanggal berapa sampe tanggal berapa.
            $table->date('start_date');
            $table->date('end_date');
            
            // [SNAPSHOT PRICE]
            // PENTING: Total harga disimpan di sini, BUKAN ngambil dari tabel services terus.
            // Kenapa? Biar kalau bulan depan harga unit naik, history pesanan yang lama harganya tetep sama.
            // Pake decimal(15, 2) biar presisi buat mata uang.
            $table->decimal('total_price', 15, 2);
            
            // Status Order: 
            // PENDING (Baru pesen) -> APPROVED (Disetujui Admin) -> REJECTED (Ditolak)
            $table->string('status')->default('PENDING');
            
            // Catatan tambahan dari user (opsional, boleh kosong)
            $table->text('notes')->nullable();
            
            $table->timestamps(); // Kapan dibuat & diupdate
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
