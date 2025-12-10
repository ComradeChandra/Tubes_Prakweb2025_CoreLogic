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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            
            // DATA DIRI PELAMAR
            $table->string('name');     // Nama Pelamar
            $table->string('email');    // Email buat dihubungi
            $table->string('phone'); // No WA
            
            // POSISI YANG DILAMAR
            // Misal: Security Guard, Driver, Staff Admin
            $table->string('position'); 
            
            // FILE UPLOAD (PENTING)
            // Urg cuma simpen "Path" (lokasi file)-nya aja di database.
            // File aslinya nanti masuk folder storage.
            $table->string('cv_path');                // Lokasi file CV (PDF)
            $table->string('photo_path')->nullable(); // Lokasi Foto Diri/KTP
            
            // STATUS LAMARAN
            // pending: Baru masuk, belom dicek admin
            // interviewed: Udah dipanggil wawancara
            // accepted: Diterima (Nanti dibuatin akun User)
            // rejected: Ditolak (Sabar ya)
            $table->enum('status', ['pending', 'interviewed', 'accepted', 'rejected'])->default('pending');
            
            // CATATAN HRD (ADMIN)
            // Misal: "Orangnya oke, tapi minta gaji kegedean"
            $table->text('admin_note')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};

/*
========== CATATAN LOGIKA (CATATAN URG) ==========

Oke, ini tabel buat nampung orang-orang yang mau kerja di CoreLogic.

1. KENAPA DI PISAH DARI TABEL USERS?
   Karena mereka BELUM TENTU diterima.
   Kalau urg gabungin ke tabel Users, nanti database penuh sama akun sampah.
   Jadi biarin mereka ngantri di tabel 'applicants' dulu. 

2. LOGIKA UPLOAD FILE (cv_path):
   Database itu gak cocok buat nyimpen file fisik (PDF/Gambar) karena bakal berat banget.
   Jadi yang urg simpen di sini cuma ALAMATNYA doang (misal: "uploads/cv/budi.pdf").
   File aslinya nanti urg simpen di folder project.

3. LOGIKA STATUS:
   Urg pake Enum lagi ('pending', 'accepted', dll).
   Nanti di Dashboard Admin, urg bisa filter: "Tampilin dong lamaran yang statusnya masih Pending".
   Kalau statusnya urg ganti jadi 'accepted', baru deh urg bikinin dia akun User resmi.
*/