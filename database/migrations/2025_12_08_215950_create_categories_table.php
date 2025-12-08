<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ini fungsi buat BIKIN tabelnya pas kita ketik 'php artisan migrate'
     */
    public function up(): void
    {
        // Perintah: Tolong bikinin tabel namanya 'categories'
        Schema::create('categories', function (Blueprint $table) {
            // 1. Kolom ID: Ini nomor urut otomatis (1, 2, 3...)
            // Jadi setiap kategori pasti punya nomor unik biar gak ketuker
            $table->id();

            // 2. Kolom Name: Ini buat nyimpen nama kategorinya
            // Tipe datanya 'string' alias tulisan pendek.
            // Contoh isinya nanti: "Tactical Unit", "Transport", "Training"
            $table->string('name');

            // 3. Kolom Slug: Ini buat bikin link/URL yang rapi
            // Misal nama kategorinya "Tactical Unit", slug-nya jadi "tactical-unit"
            // 'unique()' artinya GAK BOLEH ada slug yang kembar, harus beda semua
            $table->string('slug')->unique();

            // 4. Kolom Timestamps: Ini otomatis bikin 2 kolom (created_at & updated_at)
            // Biar ketahuan kapan kategori ini dibuat atau diedit terakhir kali
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Ini fungsi buat HAPUS tabelnya pas kita ketik 'php artisan migrate:rollback'
     * Jadi semacam tombol "Undo" kalau salah bikin
     */
    public function down(): void
    {
        // Perintah: Cek dulu, kalau tabel 'categories' ada, tolong dihapus (drop)
        Schema::dropIfExists('categories');
    }
};

/*
========== CATATAN PRIBADI (JANGAN DIHAPUS BIAR GAK LUPA) ==========

ini logika file Migrasi Kategori biar urg gak bingung lagi:

1. FUNGSI FILE INI:
   Ini tuh kayak "Denah Bangunan" buat database. urg lagi nyuruh Laravel:
   "Woy, tolong bangunin tabel baru namanya 'categories' di database."

2. BAGIAN function up():
   Ini instruksi buat NGEBANGUN. urg minta laci-laci (kolom) ini:
   - $table->id()       => Nomor urut otomatis (1, 2, 3...). Wajib ada biar datanya punya KTP.
   - $table->string()   => Laci buat tulisan pendek. urg pake buat 'name' (Nama Kategori) sama 'surgg' (URL cantik).
   - unique()           => Stempel khusus di 'slug' biar gak ada dua kategori yang URL-nya kembar.
   - $table->timestamps() => Ini otomatis bikin kolom 'dibuat_kapan' sama 'diedit_kapan'.

3. BAGIAN function down():
   Ini tombol "UNDO" atau "HANCURKAN".
   Kalau urg ngerasa salah bikin dan pengen ulang (pake perintah migrate:rollback),
   fungsi ini bakal ngehapus tabel 'categories' sampe bersih.

Intinya: File ini cuma RENCANA. Tabel aslinya baru muncul kalau urg ketik 'php artisan migrate' di terminal.
*/