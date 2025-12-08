<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BIKIN AKUN (ADMIN)
        // Ini akun buat Admin (ChandraHarkatRaharja) login nanti. Passwordnya: password
        User::create([
            'name' => 'ChandraHarkatRaharja',
            'email' => 'admin@corelogic.com',
            'password' => Hash::make('password'),
        ]);

        // 2. BIKIN KATEGORI LAYANAN
        // Kita simpen ke variabel ($catCombat, dll) biar gampang dipanggil di bawah
        $catCombat = Category::create([
            'name' => 'Tactical Combat Unit',
            'slug' => 'tactical-combat-unit'
        ]);

        $catTransport = Category::create([
            'name' => 'Secure Transport',
            'slug' => 'secure-transport'
        ]);

        $catTraining = Category::create([
            'name' => 'Specialized Training',
            'slug' => 'specialized-training'
        ]);
        
        $catStatic = Category::create([
            'name' => 'Static Security',
            'slug' => 'static-security'
        ]);

        // 3. MASUKIN UNIT PMC (BARANG DAGANGAN)
        
        // --- EASTERN WOLVES ---
        Service::create([
            'category_id' => $catCombat->id, // Masuk kategori Combat
            'name' => 'Eastern Wolves - Platinum Package',
            'slug' => 'eastern-wolves-platinum',
            'price' => 1500.00, 
            'description' => 'Unit PMC elite bersenjata Timur (AK-12, PKM). Spesialisasi urban warfare dan heavy assault.',
            'status' => 'available',
        ]);

        // --- BLACKGOLD TEAM ---
        Service::create([
            'category_id' => $catCombat->id, // Masuk kategori Combat
            'name' => 'Blackgold Team - Gold Package',
            'slug' => 'blackgold-team-gold',
            'price' => 1200.00,
            'description' => 'Unit PMC standar NATO (M4A1, Glock). Disiplin tinggi, cocok untuk pengawalan korporat.',
            'status' => 'available',
        ]);

        // --- K9 UNIT ---
        Service::create([
            'category_id' => $catTraining->id, // Masuk kategori Training
            'name' => 'K-9 Handler & Trainer',
            'slug' => 'k9-handler-trainer',
            'price' => 450.00,
            'description' => 'Pelatih profesional dan anjing K-9 (Malinois) untuk deteksi bahan peledak.',
            'status' => 'available',
        ]);

        // --- ARMORED VIP ---
        Service::create([
            'category_id' => $catTransport->id, // Masuk kategori Transport
            'name' => 'Armored VIP Escort (City)',
            'slug' => 'armored-vip-escort',
            'price' => 800.00,
            'description' => 'Sedan Mewah (S-Class) dengan Armor Level B6. Driver bersertifikat taktis.',
            'status' => 'available',
        ]);

        // --- APC ESCORT ---
        Service::create([
            'category_id' => $catTransport->id, // Masuk kategori Transport
            'name' => 'Heavy Cargo Escort (APC)',
            'slug' => 'heavy-cargo-escort-apc',
            'price' => 2000.00,
            'description' => 'Pengawalan barang bernilai tinggi menggunakan APC BTR/Bearcat.',
            'status' => 'deployed', // Statusnya lagi "Sedang Tugas"
        ]);

        // --- STATSEC ---
        Service::create([
            'category_id' => $catStatic->id, // Masuk kategori Static
            'name' => 'CoreLogic StatSec Unit',
            'slug' => 'corelogic-statsec',
            'price' => 150.00,
            'description' => 'Unit penjagaan statis untuk Bank/Gedung. Armor Ringan & Shotgun.',
            'status' => 'available',
        ]);
    }
}

/*
========== PENJELASAN KODINGAN ==========

ini file buat NGISI DATA OTOMATIS (Seeder).

1. LOGIKA UTAMA:
   urg pake perintah `Create` manual buat masukin data satu per satu.
   Kenapa gak pake Factory? Karena data urg SPESIFIK (Eastern Wolves, Blackgold),
   bukan data acak kayak "Lorem Ipsum".

2. ALUR KERJANYA:
   - Pertama, urg bikin User Admin (buat urg login nanti).
   - Kedua, urg bikin Kategori (Combat, Transport, dll) dan urg simpen ID-nya di variabel ($catCombat).
   - Ketiga, urg bikin Service (Unit) dan urg tempelin ID Kategori tadi di kolom 'category_id'.

   Jadi pas urg jalanin seeder ini, si Eastern Wolves otomatis tau kalo dia itu anak buahnya "Tactical Combat Unit".

3. CARA PAKAI:
   Save file ini, terus buka terminal dan ketik:
   php artisan db:seed
*/