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
        // 1. BIKIN AKUN SUPER ADMIN (Ini akun Urg)
        User::factory()->create([
            'name' => 'Chandra Harkat Raharja',      // Nama asli urg biar nggak gila
            'username' => 'admin',                   // Username buat login
            'email' => 'admin@corelogic.com',
            'password' => Hash::make('password'),    // Password default
            
            // --- UPDATE PENTING DI SINI ---
            // Karena tadi di Migration urg udah nambahin kolom 'role',
            // Di sini urg WAJIB set ini jadi 'admin'.
            // Kalo gak diset, nanti urg cuma jadi 'customer' (default-nya).
            'role' => 'admin', 
            
            // Data pelengkap (Boleh isi, boleh enggak karena nullable)
            'phone' => '081234567890',
            'address' => 'Markas Besar CoreLogic',
        ]);

        // 2. BIKIN KATEGORI LAYANAN
        // Urg simpen ke variabel ($catCombat, dll) biar ID-nya bisa dipake di bawah
        $catCombat = Category::create([
            'name' => 'Strategic Security Unit', // Ganti 'Tactical Combat' jadi lebih halus
            'slug' => 'strategic-security-unit'
        ]);

        $catTransport = Category::create([
            'name' => 'Logistics Security', // Ganti 'Secure Transport'
            'slug' => 'logistics-security'
        ]);

        $catTraining = Category::create([
            'name' => 'Specialized Training',
            'slug' => 'specialized-training'
        ]);
        
        $catStatic = Category::create([
            'name' => 'Static Security',
            'slug' => 'static-security'
        ]);

        // 3. MASUKIN BARANG DAGANGAN (UNIT PMC)
        
        // --- EASTERN WOLVES ---
        Service::create([
            'category_id' => $catCombat->id, // Nyambung ke kategori Combat
            'name' => 'Eastern Wolves - Platinum Package',
            'slug' => 'eastern-wolves-platinum',
            'price' => 1500.00, 
            'description' => 'Elite Private Security Unit equipped with Eastern European gear (AK-12, PKM). Specializes in Urban Security and Proactive Protection.',
            'status' => 'available',
        ]);

        // --- BLACKGOLD TEAM ---
        Service::create([
            'category_id' => $catCombat->id, 
            'name' => 'Blackgold Team - Gold Package',
            'slug' => 'blackgold-team-gold',
            'price' => 1200.00,
            'description' => 'NATO standard Private Security Unit (M4A1, Glock). Highly disciplined, suitable for corporate escorts.',
            'status' => 'available',
        ]);

        // --- K9 UNIT ---
        Service::create([
            'category_id' => $catTraining->id, 
            'name' => 'K-9 Handler & Trainer',
            'slug' => 'k9-handler-trainer',
            'price' => 450.00,
            'description' => 'Professional handlers with K-9 units for hazard detection and site safety.', 
            'status' => 'available',
        ]);

        // --- ARMORED VIP ---
        Service::create([
            'category_id' => $catTransport->id, 
            'name' => 'Armored VIP Escort (City)',
            'slug' => 'armored-vip-escort',
            'price' => 800.00,
            'description' => 'Luxury sedan with B6-level protection. Certified defensive drivers.', 
        ]);

        // --- APC ESCORT ---
        Service::create([
            'category_id' => $catTransport->id, 
            'name' => 'Heavy Cargo Escort (APC)',
            'slug' => 'heavy-cargo-escort-apc',
            'price' => 2000.00,
            'description' => 'Pengawalan barang bernilai tinggi menggunakan APC BTR/Bearcat.',
            'status' => 'deployed', // Ceritanya lagi tugas
        ]);

        // --- STATSEC ---
        Service::create([
            'category_id' => $catStatic->id, 
            'name' => 'CoreLogic StatSec Unit',
            'slug' => 'corelogic-statsec',
            'price' => 150.00,
            'description' => 'Unit penjagaan statis untuk Bank/Gedung. Armor Ringan & Shotgun.',
            'status' => 'available',
        ]);
    }
}

/*
========== CATATAN LOGIKA (CATATAN URG) ==========

Logika file Seeder ini setelah update Migration User:

1. MASALAH SEBELUMNYA:
   Tadi urg update tabel `users` nambahin kolom `role` ('admin'/'customer').
   Default-nya database bakal ngisi 'customer' kalau gak dibilangin.
   
2. SOLUSI DI SINI:
   Pas bikin user `Chandra Harkat Raharja`, urg tambahin baris:
   'role' => 'admin',
   
   Kenapa? 
   Biar pas urg login nanti, sistem tau: "Oh, ini Boss Besar".
   Kalau baris itu lupa urg tulis, nanti urg malah dianggap tamu biasa (customer) 
   dan gak bisa masuk Dashboard Admin yang mau urg bikin.

3. SISA KODINGAN (Category & Service):
   Ini sama aja kayak kemaren. Cuma buat ngisi data dummy biar website gak kosong melompong.
*/