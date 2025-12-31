# Praktikum Web
# TUGAS BESAR
# "CoreLogic Security Solutions"

Dipersiapkan Oleh:  
G / Kelompok CoreLogic

- CHANDRA HARKAT RAHARJA - 233040089  
- FIRDA FARIDATUL FAJRIYANTI - 233040098  
- NAUVAL MUHAMMAD ABDUL AZIS - 233040109  
- ARIA YUDHISTIRA - 233040129  

Asisten Pembimbing:  
MUHAMAD MARSA NUR JAMAN - 223040083  

PROGRAM STUDI TEKNIK INFORMATIKA  
FAKULTAS TEKNIK  
UNIVERSITAS PASUNDAN  
BANDUNG  
2025  

## Daftar Isi
1. Daftar Isi  
2. Tujuan Pembuatan Project  
3. Masalah  
   3.1 Masalah Yang Berusaha di Selesaikan  
   3.2 Lingkup Masalah  
4. Pembagian Kerja Tim  
5. Detail Project  
   5.1 Latar Belakang  
   5.2 Nama Aplikasi  
   5.3 Tema  
   5.4 Rancangan fitur  
6. Referensi project  
7. Penutup  

## Tujuan Pembuatan Project

Proyek ini dibuat dengan tujuan utama untuk mengembangkan sebuah platform web yang memfasilitasi proses pemesanan layanan keamanan profesional (security services) secara online. Aplikasi ini dirancang untuk mendigitalisasi proses penyewaan unit pengamanan yang selama ini cenderung konvensional, sehingga memudahkan klien (perusahaan atau individu) dalam mencari, memverifikasi, dan memesan layanan keamanan yang terpercaya.

Selain itu, proyek ini bertujuan untuk menerapkan konsep Software Engineering modern menggunakan framework Laravel. Secara spesifik, tim pengembang bertujuan untuk:

- Mengimplementasikan arsitektur MVC (Model-View-Controller) yang rapi dan terstruktur.
- Menerapkan sistem Role-Based Access Control (RBAC) untuk membedakan hak akses antara User (Pelanggan) dan Admin.
- Mengintegrasikan logika bisnis yang kompleks seperti perhitungan harga berbasis durasi mingguan dan sistem diskon bertingkat (Tiering Membership).
- Mengelola integrasi dengan API Pihak Ketiga (OpenStreetMap & DiceBear) serta fitur pelaporan dokumen digital (PDF Invoice).

## Masalah

Proyek CoreLogic Security Solutions bertujuan untuk mengembangkan sebuah platform web yang memfasilitasi proses pemesanan layanan jasa secara online. Saat ini, masyarakat seringkali mengalami kesulitan dalam mencari, memilih, dan memesan layanan jasa yang terpercaya karena informasi yang tersebar dan tidak terpusat. Di sisi lain, penyedia jasa kesulitan menjangkau pelanggan yang lebih luas. Tanpa adanya sistem yang menjembatani keduanya, proses transaksi menjadi lambat, tidak efisien, dan informasi mengenai ketersediaan atau harga layanan seringkali tidak transparan bagi konsumen.

### Masalah Yang Berusaha di Selesaikan

Masalah utama yang berusaha diselesaikan oleh sistem ini adalah inefisiensi dalam pertemuan antara kebutuhan konsumen dan penawaran jasa. Saat ini, pelanggan sering mengalami kesulitan untuk mendapatkan informasi layanan yang transparan, lengkap, dan terpusat, serta harus menempuh proses pemesanan manual yang memakan waktu lama. Oleh karena itu, kehadiran platform ini bertujuan untuk mengubah mekanisme konvensional tersebut menjadi sistem digital yang terintegrasi, sehingga hambatan dalam akses informasi dapat dihilangkan dan proses transaksi antara penyedia jasa dengan konsumen dapat berjalan lebih cepat, praktis, dan menjangkau pasar yang lebih luas.

### Lingkup Masalah

Lingkup pengembangan sistem ini dibatasi pada pembuatan aplikasi berbasis website yang dirancang untuk memfasilitasi interaksi antara dua pengguna utama, yaitu Administrator dan Pelanggan. Fokus fungsionalitas sistem mencakup penyediaan fitur-fitur esensial seperti pengelolaan katalog layanan jasa yang informatif, mekanisme pemesanan layanan secara online, serta manajemen data pesanan untuk memantau status pengerjaan layanan. Batasan ini dibuat agar pengembangan sistem tetap terarah pada tujuan utama sebagai jembatan informasi dan transaksi jasa yang efektif bagi pengguna.

## Pembagian Kerja Tim

Tim terdiri dari 5 orang developer yaitu 1 orang sebagai Project Manager 2 orang sebagai Frontend Developer dan 2 orang sebagai Backend Developer.

| No. | Pembagian Kerja | Tenaga Ahli |
|----|----------------|-------------|
| 1. | Project Manager | Chandra Harkat Raharja |
| 2. | Team Frontend Developer | Aria Yudhistira |
| 3. | Team Frontend Developer | Nauval Muhammad Abdul Aziz |
| 4. | Team Backend Developer | Firda Faridatul Fajriyanti |
| 5. | Team Backend Developer | Chandra Harkat Raharja |

Chandra sebagai ketua tim dan project manager bertanggung jawab atas koordinasi keseluruhan proyek, perencanaan sprint, dan pengawasan kualitas kode. Firda fokus pada pengembangan backend seperti logika bisnis, validasi data, dan integrasi database. Nauval dan Aria menangani desain dan implementasi frontend, termasuk UI/UX, responsive design, dan interaksi pengguna.

## Detail Project

### Latar Belakang

Dalam era digital saat ini, kecepatan dan ketepatan informasi menjadi aset vital bagi keberlangsungan operasional perusahaan, terutama yang bergerak di sektor jasa keamanan (security services). CoreLogic Security Solutions sebagai penyedia layanan keamanan profesional menghadapi tantangan dalam mengelola operasional bisnis yang semakin kompleks.

Saat ini, proses penyewaan unit keamanan di CoreLogic masih didominasi oleh sistem manual dan semi-komputerisasi. Calon klien harus menghubungi perusahaan melalui telepon atau datang langsung untuk mengetahui ketersediaan unit dan harga. Di sisi internal, staf administrasi mengalami kesulitan dalam melakukan rekapitulasi pesanan dan pembuatan laporan pendapatan bulanan karena data tersebar dalam berbagai arsip fisik maupun spreadsheet yang tidak terintegrasi. Hal ini seringkali menyebabkan terjadinya human error, seperti duplikasi pemesanan, kesalahan perhitungan biaya, hingga keterlambatan dalam penyajian laporan kepada manajemen.

Selain itu, kebutuhan akan transparansi dan aksesibilitas layanan menuntut perusahaan untuk hadir secara digital. Klien membutuhkan platform yang dapat diakses 24 jam untuk melihat katalog layanan dan status pemesanan mereka tanpa birokrasi yang berbelit.

### Nama Aplikasi

CoreLogic Security Solutions

### Tema

Aplikasi web ini mengusung Tema "Tactical Corporate" (Korporat Taktis). Tema ini menggabungkan nuansa profesionalitas perusahaan dengan estetika militer modern.

### Rancangan fitur

Aplikasi CoreLogic Defense dirancang untuk memenuhi semua spesifikasi tugas besar praktikum web 2025/2026, dengan fitur minimal yang wajib serta bonus tambahan. Berikut adalah detail lengkap setiap fitur beserta implementasi kode dan logikanya:

#### 1. Halaman Depan (Front-end)

Halaman depan merupakan landing page yang menampilkan branding militer dengan desain yang mengesankan. Fitur ini menggunakan view `layouts.welcome` yang dirancang dengan Tailwind CSS (bukan Bootstrap seperti yang dilarang spesifikasi).

**Implementasi Kode:**
- Route: `Route::get('/', function () { return view('layouts.welcome'); });` di `routes/web.php`
- View: `resources/views/layouts/welcome.blade.php` menggunakan Tailwind CSS untuk styling responsif
- Fitur: Hero section dengan background image dari Unsplash API, call-to-action buttons, dan section "Why CoreLogic?" dengan cards

**Logika:** Halaman statis yang langsung mengarahkan ke katalog, tanpa logika backend kompleks. Menggunakan gradient dan animasi hover untuk kesan profesional.

#### 2. Halaman Dashboard Admin (Back-end)

Dashboard admin menampilkan ringkasan data seperti jumlah order, total pendapatan, dan statistik lainnya. Menggunakan middleware `auth` dan `role:admin` untuk proteksi.

**Implementasi Kode:**
- Controller: `DashboardController::index()` di `app/Http/Controllers/DashboardController.php`
- Route: `Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');`
- View: `resources/views/admin/dashboard.blade.php` dengan cards menampilkan metrics

**Logika:** Query database untuk menghitung total orders, revenue, dan users. Menampilkan data real-time dari tabel orders dan users.

#### 3. Model / Database dengan Relasi

Aplikasi memiliki struktur database yang kompleks dengan multiple relasi untuk mendukung fitur marketplace.

**Models dan Relasi:**
- `User` model: Relasi `hasMany` ke `Order`, `belongsTo` ke `UserNotification`
- `Service` model: Relasi `belongsTo` ke `Category`, `hasMany` ke `ServiceImage`, `hasMany` ke `Order`
- `Order` model: Relasi `belongsTo` ke `User` dan `Service`
- `Category` model: Relasi `hasMany` ke `Service`
- `ServiceImage` model: Relasi `belongsTo` ke `Service`

**Implementasi Kode:**
```php
// app/Models/Service.php
public function category() {
    return $this->belongsTo(Category::class);
}
public function images() {
    return $this->hasMany(ServiceImage::class);
}
```

**Logika:** Menggunakan Eloquent ORM Laravel untuk query relasi, memungkinkan eager loading seperti `Order::with(['service', 'user'])` untuk performa optimal.

#### 4. View dengan Front-end Framework

Semua view menggunakan Blade templating dengan Tailwind CSS, bukan Bootstrap atau framework terlarang. Desain responsif dengan dark theme militer.

**Implementasi Kode:**
- Layout utama: `resources/views/layouts/app.blade.php` dengan Tailwind imports
- Komponen: Menggunakan Blade components untuk reusability

**Logika:** Responsive design dengan breakpoint Tailwind (sm:, md:, lg:), animasi CSS untuk interaksi, dan dark color scheme.

#### 5. CRUDS (Create, Read, Update, Delete, Search)

Fitur lengkap CRUD diimplementasikan di admin panel untuk categories, services, users, dan orders.

**Implementasi Kode:**
- Resource Routes: `Route::resource('admin/categories', CategoryController::class);`
- Controllers: `CategoryController`, `ServiceController`, `UserController`, `OrderController`
- Search & Filter: Di `ServiceController::publicCatalog()` menggunakan query builder

```php
// app/Http/Controllers/ServiceController.php
public function publicCatalog(Request $request) {
    $query = Service::with(['category', 'images']);
    
    if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    
    if ($request->category) {
        $query->where('category_id', $request->category);
    }
    
    $services = $query->paginate(12);
    return view('catalog', compact('services'));
}
```

**Logika:** Live search dengan AJAX (jika diimplementasikan), filter berdasarkan kategori, pagination untuk performa.

#### 6. Mengelola Gambar (Upload, Delete, Validasi)

Sistem upload gambar untuk avatar user, KTP, dan foto service dengan validasi ketat.

**Implementasi Kode:**
- Upload: Menggunakan `Storage::put()` Laravel
- Validasi: `'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'`
- Delete: `Storage::delete()` dengan route khusus

```php
// app/Http/Controllers/ProfileController.php
$request->validate([
    'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
    'id_card' => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
]);

if ($request->hasFile('avatar')) {
    $path = $request->file('avatar')->store('avatars', 'public');
    $user->avatar = $path;
}
```

**Logika:** File disimpan di `storage/app/public`, diakses via symlink. Validasi tipe dan ukuran untuk keamanan.

#### 7. Authentication & Authorization

Sistem login/register/logout dengan role-based access control.

**Implementasi Kode:**
- Controller: `AuthController` dengan methods `showLogin`, `login`, `showRegister`, `register`, `logout`
- Middleware: `auth` untuk protected routes, custom `role:admin` middleware
- Model: `User` extends `Authenticatable`, dengan field `role`

**Logika:** Password hashing dengan bcrypt, session management Laravel, redirect berdasarkan role.

#### 8. PDF Reporting

Fitur export PDF untuk invoice order dan laporan bulanan admin.

**Implementasi Kode:**
- Library: `barryvdh/laravel-dompdf`
- Controller: `OrderController::exportPdf()`, `DashboardController::downloadMonthlySalesReport()`
- Template: `resources/views/users/order_pdf.blade.php`

```php
// app/Http/Controllers/OrderController.php
public function exportPdf($id) {
    $order = Order::with(['service', 'user'])->where('user_id', Auth::id())->findOrFail($id);
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('users.order_pdf', $data);
    return $pdf->download('Order_' . $order->id . '.pdf');
}
```

**Logika:** Generate PDF dari HTML template dengan data dinamis, download otomatis.

#### 9. Terhubung API Publik

Aplikasi terhubung dengan dua API publik:
- **DiceBear API**: Untuk generate avatar default berdasarkan nama user
- **OpenStreetMap Nominatim API**: Untuk reverse geocoding pada form order

**Implementasi Kode:**
```javascript
// resources/views/services/order.blade.php
fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
    .then(response => response.json())
    .then(data => {
        addressInput.value = data.display_name;
    });
```

**Logika:** Saat user klik peta, koordinat dikirim ke Nominatim API untuk mendapatkan alamat otomatis.

#### 10. Web Hosting

Proyek telah dipersiapkan untuk hosting dengan konfigurasi environment, build assets, dan symlink storage.

**Fitur Bonus Tambahan:**
- **Sistem Tier Membership**: Diskon otomatis berdasarkan loyalitas (VIP 10%, Elite 20%)
- **Verifikasi KTP**: Upload dan verifikasi dokumen identitas
- **Notifikasi Real-time**: Sistem pesan untuk update status
- **Gamification**: Tier upgrade berdasarkan total pembelian
- **Multi-image Upload**: Carousel gambar untuk services
- **Geolocation Integration**: Peta interaktif dengan Leaflet.js

Semua fitur diimplementasikan dengan kode yang bersih, komentar detail, dan mengikuti best practices Laravel.

## Referensi project

Proyek ini terinspirasi dari platform marketplace seperti Upwork dan Fiverr, namun dengan tema keamanan militer yang unik. Dokumentasi Laravel resmi digunakan sebagai referensi utama untuk implementasi framework. Package tambahan seperti DomPDF untuk PDF generation dan Carbon untuk date manipulation diadopsi dari ekosistem Laravel. Desain UI mengambil inspirasi dari situs militer dan aplikasi SaaS modern.

## Penutup

Berdasarkan hasil praktikum Pemrograman Web yang telah dilaksanakan menggunakan framework Laravel dengan bantuan Laravel Herd sebagai local development environment, dapat disimpulkan bahwa penggunaan framework mempermudah proses pengembangan aplikasi web. Laravel menyediakan struktur proyek yang rapi, sistem routing yang jelas, serta kemudahan dalam pengelolaan database dan tampilan melalui Blade. Praktikum ini membantu mahasiswa memahami alur kerja pengembangan web modern serta meningkatkan keterampilan dalam membangun aplikasi web yang terstruktur dan efisien.</content>
<parameter name="filePath">d:\laragon\www\prakweb2025\TubesCoreLogic\corelogic\LAPORAN_TUBES_WEB_CoreLogic.md