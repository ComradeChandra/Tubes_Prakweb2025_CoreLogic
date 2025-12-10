<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ini fungsi buat BIKIN tabel pas urg jalanin 'php artisan migrate'
     */
    public function up(): void
    {
        // 1. TABEL USERS (Ini yang utama)
        Schema::create('users', function (Blueprint $table) {
            // ID Otomatis (1, 2, 3...)
            $table->id();

            // Data Standar Akun
            $table->string('name'); // Nama Lengkap
            
            // Urg tambahin username biar login bisa opsional (email/username)
            $table->string('username')->unique(); 
            
            $table->string('email')->unique(); // Email wajib unik
            $table->timestamp('email_verified_at')->nullable(); // Kapan verifikasi email
            $table->string('password'); // Password terenkripsi

            // --- UPGRADE FITUR CORELOGIC (YANG URG TAMBAHIN) ---

            // A. ROLE (SISTEM KASTA)
            // Ini buat misahin akses. 
            // 'admin' = Bisa masuk dashboard, kelola user.
            // 'staff' = Karyawan biasa.
            // 'customer' = Tamu yang cuma boleh sewa.
            // Defaultnya 'customer', jadi kalo ada yang daftar otomatis jadi customer.
            $table->enum('role', ['admin', 'staff', 'customer'])->default('customer');

            // B. DATA FORMAL (KYC - Know Your Customer)
            // Urg kasih 'nullable' (BOLEH KOSONG).
            // Kenapa? Karena pas Admin (urg) login pertama kali, urg gak perlu isi NIK.
            // Tapi nanti pas Customer Register, urg bakal paksa mereka isi ini lewat Validasi di Controller.
            $table->string('nik', 16)->nullable(); // NIK KTP wajib 16 digit
            $table->string('phone', 15)->nullable(); // No HP buat kontak darurat
            $table->text('address')->nullable(); // Alamat lengkap buat pengiriman unit
            $table->string('photo')->nullable(); // Foto Profil/KTP

            $table->rememberToken(); // Buat fitur 'Remember Me'
            $table->timestamps(); // Created_at & Updated_at
        });

        // 2. TABEL TOKEN RESET PASSWORD (Bawaan Laravel, biarin aja)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. TABEL SESSION (Bawaan Laravel, biarin aja)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     * Fungsi buat NGANCURIN tabel kalo urg mau reset (migrate:rollback)
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

/*
========== PENJELASAN LOGIKA & STEP BY STEP (CATATAN URG) ==========

Oke, jadi gini alur logika kenapa file ini urg ubah:

1. KENAPA DIUBAH DARI BAWAANNYA?
   Bawaan Laravel itu user-nya polos banget (cuma Nama, Email, Pass).
   Sedangkan aplikasi CoreLogic ini butuh sistem "Kasta" (Role) dan data "Resmi" (NIK/Alamat).
   Kalau urg gak tambah kolom ini sekarang, nanti susah misahin mana Boss mana Klien.

2. LOGIKA 'ENUM' PADA ROLE:
   Urg pake tipe data `enum` di baris `$table->enum('role', [...])`.
   Ini tuh kayak "Pilihan Ganda". Jadi di database, kolom `role` isinya CUMA BOLEH salah satu dari:
   'admin', 'staff', atau 'customer'.
   Kalau ada yang maksa masukin role 'hacker', database bakal nolak. Ini proteksi lapis pertama.

3. LOGIKA 'NULLABLE' PADA DATA FORMAL:
   Urg sengaja kasih `->nullable()` di kolom NIK, Phone, Address.
   Maksudnya: "Kolom ini BOLEH dikosongin".
   
   Kenapa? 
   Bayangin urg lagi bikin akun Admin lewat Seeder (kodingan otomatis). Urg males kan kalo harus input NIK palsu?
   Jadi di database urg bolehin kosong.
   TAPI, nanti di tampilan Register Customer (Frontend), urg bakal bikin aturan "WAJIB ISI".
   Jadi validasinya urg taruh di Controller, bukan di Database. Ini biar fleksibel.

4. END GAME:
   File ini cuma sketsa/denah. Tabel aslinya baru kebentuk pas urg ketik `php artisan migrate`.
   Kalau nanti urg butuh nambah kolom lagi (misal: gender), urg harus edit file ini terus migrate ulang.
*/