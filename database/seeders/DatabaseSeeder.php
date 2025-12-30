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

        // 1b. BIKIN AKUN CUSTOMER (Buat Test Login)
        User::factory()->create([
            'name' => 'Client VIP',
            'username' => 'client',
            'email' => 'customer@test.com', // Pake ini aja biar gampang
            'password' => Hash::make('password'),
            'role' => 'customer', // Role customer biasa
            'phone' => '08987654321',
            'address' => 'Sudirman CBD Tower',
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
            'price' => 25000.00, 
            'description' => 'Elite Private Security Unit equipped with Eastern European gear (AK-12, PKM). Specializes in Urban Security and Proactive Protection.',
            'status' => 'available',
        ]);

        // --- BLACKGOLD TEAM ---
        Service::create([
            'category_id' => $catCombat->id, 
            'name' => 'Blackgold Team - Gold Package',
            'slug' => 'blackgold-team-gold',
            'price' => 18500.00,
            'description' => 'NATO standard Private Security Unit (M4A1, Glock). Highly disciplined, suitable for corporate escorts.',
            'status' => 'available',
        ]);

        // --- K9 UNIT ---
        Service::create([
            'category_id' => $catTraining->id, 
            'name' => 'K-9 Handler & Trainer',
            'slug' => 'k9-handler-trainer',
            'price' => 2500.00,
            'description' => 'Professional handlers with K-9 units for hazard detection and site safety.', 
            'status' => 'available',
        ]);

        // --- ARMORED VIP ---
        Service::create([
            'category_id' => $catTransport->id, 
            'name' => 'Armored VIP Escort (City)',
            'slug' => 'armored-vip-escort',
            'price' => 5000.00,
            'description' => 'Luxury sedan with B6-level protection. Certified defensive drivers.', 
        ]);

        // --- APC ESCORT ---
        Service::create([
            'category_id' => $catTransport->id, 
            'name' => 'Heavy Cargo Escort (APC)',
            'slug' => 'heavy-cargo-escort-apc',
            'price' => 12000.00,
            'description' => 'Pengawalan barang bernilai tinggi menggunakan APC BTR/Bearcat.',
            'status' => 'deployed', // Ceritanya lagi tugas
        ]);

        // --- STATSEC ---
        Service::create([
            'category_id' => $catStatic->id, 
            'name' => 'CoreLogic StatSec Unit',
            'slug' => 'corelogic-statsec',
            'price' => 800.00,
            'description' => 'Unit penjagaan statis untuk Bank/Gedung. Armor Ringan & Shotgun.',
            'status' => 'available',
        ]);

        // --- CYBER SECURITY DIVISION (NEW) ---
        $catCyber = Category::create([
            'name' => 'Cyber Intelligence',
            'slug' => 'cyber-intelligence'
        ]);

        Service::create([
            'category_id' => $catCyber->id,
            'name' => 'Cyber Security Division',
            'slug' => 'cyber-security-division',
            'price' => 15000.00,
            'description' => 'Advanced digital asset protection unit. Specializes in counter-surveillance, encrypted communication channels, and neutralizing cyber threats before they manifest physically.',
            'status' => 'available',
        ]);

        // --- KATEGORI VIP ESCORT ---
        $catVIP = Category::create([
            'name' => 'VIP Escort',
            'slug' => 'vip-escort'
        ]);

        // --- MARITIME SECURITY DETAIL ---
        Service::create([
            'category_id' => $catTransport->id,
            'name' => 'Maritime Security Detail',
            'slug' => 'maritime-security-detail',
            'price' => 22000.00,
            'description' => 'Professional maritime security team for vessel protection, anti-piracy operations, and offshore facility guarding. Equipped with naval communication systems.',
            'status' => 'available',
        ]);

        // --- DRONE SURVEILLANCE UNIT ---
        Service::create([
            'category_id' => $catCyber->id,
            'name' => 'Drone Surveillance Unit',
            'slug' => 'drone-surveillance-unit',
            'price' => 8500.00,
            'description' => 'Advanced UAV surveillance with thermal imaging and real-time monitoring. Ideal for perimeter security and reconnaissance missions.',
            'status' => 'available',
        ]);

        // --- VVIP CLOSE PROTECTION ---
        Service::create([
            'category_id' => $catVIP->id,
            'name' => 'VVIP Close Protection',
            'slug' => 'vvip-close-protection',
            'price' => 35000.00,
            'description' => 'Elite executive protection team trained in international standards. Includes threat assessment, route planning, and 24/7 personal security detail.',
            'status' => 'available',
        ]);

        // --- RIOT CONTROL SQUAD ---
        Service::create([
            'category_id' => $catStatic->id,
            'name' => 'Riot Control Squad',
            'slug' => 'riot-control-squad',
            'price' => 9500.00,
            'description' => 'Specialized crowd management unit equipped with non-lethal deterrents. Trained in de-escalation tactics and mass event security.',
            'status' => 'available',
        ]);

        // --- EXECUTIVE HELICOPTER TRANSPORT ---
        Service::create([
            'category_id' => $catVIP->id,
            'name' => 'Executive Helicopter Transport',
            'slug' => 'executive-helicopter-transport',
            'price' => 45000.00,
            'description' => 'Luxury aerial transport with armed escort. Includes pilot, co-pilot, and two armed security personnel. Perfect for high-risk area evacuations.',
            'status' => 'available',
        ]);

        // --- EXPLOSIVE ORDNANCE DISPOSAL ---
        Service::create([
            'category_id' => $catTraining->id,
            'name' => 'Explosive Ordnance Disposal Team',
            'slug' => 'explosive-ordnance-disposal',
            'price' => 28000.00,
            'description' => 'Certified EOD specialists for threat detection and neutralization. Equipped with bomb suits, X-ray scanners, and remote disposal robots.',
            'status' => 'available',
        ]);

        // --- DIGNITARY MOTORCADE COORDINATION ---
        Service::create([
            'category_id' => $catVIP->id,
            'name' => 'Dignitary Motorcade Coordination',
            'slug' => 'dignitary-motorcade-coordination',
            'price' => 18500.00,
            'description' => 'Full motorcade service with route sweeping, advance security teams, and multiple armored vehicles. Suitable for political figures and foreign delegates.',
            'status' => 'available',
        ]);

        // --- HOSTILE ENVIRONMENT TRAINING ---
        Service::create([
            'category_id' => $catTraining->id,
            'name' => 'Hostile Environment Training Program',
            'slug' => 'hostile-environment-training',
            'price' => 12000.00,
            'description' => 'Comprehensive 5-day training for personnel operating in high-risk zones. Includes first aid, ambush response, and emergency extraction protocols.',
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