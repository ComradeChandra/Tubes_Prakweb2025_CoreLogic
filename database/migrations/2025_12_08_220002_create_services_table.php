<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ini buat bikin tabel 'services' (Unit Jualan Kita)
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            
            // RELASI PENTING: Kolom category_id
            // Ini nyambungin Service ke Kategori.
            // constrained() -> otomatis nyari tabel 'categories'
            // onDelete('cascade') -> Kalau Kategori dihapus, Service-nya ikut kehapus otomatis.
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            // Nama Unit, misal: Eastern Wolves
            $table->string('name');
            // Slug buat URL
            $table->string('slug')->unique();
            
            // Harga Sewa. Pake decimal biar presisi duitnya (15 digit, 2 angka belakang koma)
            $table->decimal('price', 15, 2);
            
            // Deskripsi lengkap (Spek senjata, armor, dll). Pake 'text' karena bisa panjang banget.
            $table->text('description');
            
            // Foto unit. Pake 'nullable' artinya BOLEH KOSONG (biar gak error kalau belum ada gambar)
            $table->string('image')->nullable();
            
            // Status ketersediaan. Pake 'enum' artinya pilihannya cuma boleh yg ada di list ini.
            // Default-nya 'available' (Tersedia)
            $table->enum('status', ['available', 'deployed', 'maintenance'])->default('available');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

/*
========== CATATAN PRIBADI (JANGAN DIHAPUS BIAR GAK LUPA) ==========
 ini tabel buat nyimpen BARANG DAGANGAN (Unit PMC) urg/kita:

1. LOGIKA UTAMA:
   Tabel ini namanya 'services'. Isinya data-data kayak 'Eastern Wolves', 'Blackgold Team', dll.

2. POIN PENTING DI SINI:
   - foreignId('category_id') => Ini KUNCI RELASI. Ini cara kita bilang: "Setiap Unit pasti punya Kategori".
     Kalau urg bikin unit tanpa kategori, dia bakal nolak (error).
   - decimal('price')         => Khusus buat duit. Jangan pake integer biasa, nanti komanya ilang.
   - nullable()               => Artinya "Boleh Kosong". urg pake di 'image' soalnya mungkin pas awal input urg belum punya fotonya.
   - enum('status')           => Ini kayak Pilihan Ganda. Statusnya cuma bisa: Tersedia, Lagi Tugas (Deployed), atau Rusak (Maintenance).

3. HUBUNGAN SAMA KATEGORI:
   Berkat 'onDelete(cascade)', kalau urg hapus kategori "Combat Unit", semua pasukan Eastern Wolves & Blackgold bakal ILANG juga otomatis. Hati-hati.
*/