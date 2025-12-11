# 🛡️ CoreLogic Admin CRUD Management

## 📋 Fitur yang Sudah Dibuat

Sistem CRUD (Create, Read, Update, Delete) lengkap untuk Admin mengelola **Categories** dan **Services** (Unit Keamanan).

---

## 🎯 Komponen yang Dibuat

### 1. **Controllers**
- ✅ `app/Http/Controllers/CategoryController.php` - CRUD Kategori
- ✅ `app/Http/Controllers/ServiceController.php` - CRUD Unit Keamanan (dengan upload foto)

### 2. **Middleware**
- ✅ `app/Http/Middleware/RoleMiddleware.php` - Authorization berdasarkan role

### 3. **Views**

#### Admin Layout:
- ✅ `resources/views/layouts/admin.blade.php` - Master layout admin panel

#### Category Management:
- ✅ `resources/views/admin/categories/index.blade.php` - List semua kategori
- ✅ `resources/views/admin/categories/create.blade.php` - Form tambah kategori
- ✅ `resources/views/admin/categories/edit.blade.php` - Form edit kategori

#### Service Management:
- ✅ `resources/views/admin/services/index.blade.php` - List semua unit
- ✅ `resources/views/admin/services/create.blade.php` - Form tambah unit (dengan upload foto)
- ✅ `resources/views/admin/services/edit.blade.php` - Form edit unit (dengan upload foto)

### 4. **Routes**
- ✅ Admin routes di `routes/web.php` dengan middleware protection
- ✅ Middleware alias registered di `bootstrap/app.php`

---

## 🚀 Cara Menggunakan

### 1. **Setup Database**

Pastikan database sudah di-migrate dan di-seed:

```bash
# Jika pakai SQLite, buat file database dulu
type nul > database\database.sqlite

# Atau ganti ke MySQL di .env:
DB_CONNECTION=mysql
DB_DATABASE=corelogic
DB_USERNAME=root
DB_PASSWORD=

# Jalankan migration
php artisan migrate

# Seed data (termasuk admin user)
php artisan db:seed
```

### 2. **Setup Storage Link** (Penting untuk Upload Foto)

```bash
php artisan storage:link
```

Command ini membuat symbolic link dari `public/storage` ke `storage/app/public`, sehingga foto yang diupload bisa diakses dari browser.

### 3. **Login sebagai Admin**

Akses: `http://localhost/login`

**Credentials:**
- Email: `admin@corelogic.com`
- Password: `password`

### 4. **Akses Admin Panel**

Setelah login sebagai admin, akses:

- **Categories:** `http://localhost/admin/categories`
- **Services:** `http://localhost/admin/services`

---

## 📸 Fitur-Fitur

### **CRUD Categories (Kategori Unit)**

| Fitur | URL | Method | Deskripsi |
|-------|-----|--------|-----------|
| List Kategori | `/admin/categories` | GET | Tampil semua kategori dengan jumlah unit |
| Tambah Kategori | `/admin/categories/create` | GET | Form tambah kategori baru |
| Simpan Kategori | `/admin/categories` | POST | Proses simpan kategori |
| Edit Kategori | `/admin/categories/{id}/edit` | GET | Form edit kategori |
| Update Kategori | `/admin/categories/{id}` | PUT | Proses update kategori |
| Hapus Kategori | `/admin/categories/{id}` | DELETE | Hapus kategori (cascade delete unit) |

**Validasi:**
- Name: wajib, unique, min 3 karakter
- Slug: auto-generate dari name

**Fitur Khusus:**
- Cascade delete (kalau kategori dihapus, semua unit di dalamnya ikut terhapus)
- Counter jumlah unit per kategori
- Flash messages (sukses/error)
- Konfirmasi sebelum hapus

---

### **CRUD Services (Unit Keamanan)**

| Fitur | URL | Method | Deskripsi |
|-------|-----|--------|-----------|
| List Unit | `/admin/services` | GET | Tampil semua unit dengan foto & info |
| Tambah Unit | `/admin/services/create` | GET | Form tambah unit baru |
| Simpan Unit | `/admin/services` | POST | Proses simpan unit + upload foto |
| Edit Unit | `/admin/services/{id}/edit` | GET | Form edit unit |
| Update Unit | `/admin/services/{id}` | PUT | Proses update unit + foto |
| Hapus Unit | `/admin/services/{id}` | DELETE | Hapus unit + foto |

**Validasi:**
- Name: wajib, unique, min 3 karakter
- Category: wajib, harus exist
- Price: wajib, numeric, min 0
- Description: wajib, min 10 karakter
- Status: wajib, harus salah satu: available/deployed/maintenance
- Image: 
  - Create: wajib, max 2MB, format JPG/JPEG/PNG/WebP
  - Edit: opsional (jika kosong, gunakan foto lama)

**Fitur Khusus:**
- Upload foto dengan live preview
- Auto-delete foto lama saat update/delete
- Status badge (Available/Deployed/Maintenance)
- Price formatting (Rupiah)
- Slug auto-generate

---

## 🔒 Security & Authorization

### **Middleware Protection**

Semua route admin dilindungi dengan 2 middleware:
1. `auth` - User harus login
2. `role:admin` - User harus punya role 'admin'

**Behavior:**
- ❌ Belum login → Redirect ke `/login`
- ❌ Login sebagai customer/staff → **403 Forbidden**
- ✅ Login sebagai admin → **Akses diberikan**

### **Role System**

User di database punya field `role` dengan 3 pilihan:
- `admin` - Akses penuh ke admin panel
- `staff` - (Future) Akses terbatas
- `customer` - Gak bisa akses admin panel

---

## 📂 File Upload System

### **Lokasi Penyimpanan:**
```
storage/app/public/services/{timestamp}_{filename}
```

### **Akses dari Browser:**
```
http://localhost/storage/services/{timestamp}_{filename}
```

### **Auto-Cleanup:**
- Saat update foto → Foto lama otomatis dihapus
- Saat delete unit → Foto ikut dihapus dari storage
- Mencegah file sampah numpuk di server

---

## 🎨 Design & UI

### **Tema:**
- Dark theme (Gray-900 background)
- Red-600 accent (brand color CoreLogic)
- Font: Chakra Petch (military/futuristic)

### **Responsive:**
- Mobile: Sidebar tersembunyi, muncul saat klik burger menu
- Desktop: Sidebar selalu tampil

### **Components:**
- Navbar dengan user info & logout
- Sidebar menu dengan active state
- Flash messages auto-hide (5 detik)
- Delete confirmation dialog
- Validation error display per field

---

## 📝 Dokumentasi Kode

Semua file sudah dilengkapi dengan **komentar lengkap dalam Bahasa Indonesia** menjelaskan:
- Fungsi file
- Logic setiap method
- Validasi rules
- Security notes
- Best practices
- Cara pakai

**Contoh dokumentasi:**
- CategoryController: ~260 baris (termasuk komentar)
- ServiceController: ~380 baris (termasuk komentar)
- RoleMiddleware: ~170 baris (termasuk komentar)

---

## 🧪 Testing

### **Manual Testing:**

1. **Test sebagai Admin:**
   - Login: `admin@corelogic.com` / `password`
   - Akses `/admin/categories` → ✅ Berhasil
   - Akses `/admin/services` → ✅ Berhasil

2. **Test sebagai Customer:**
   - Register akun baru (role default: customer)
   - Coba akses `/admin/categories` → ❌ 403 Forbidden

3. **Test Tanpa Login:**
   - Akses `/admin/categories` → ❌ Redirect ke `/login`

### **Feature Testing:**

#### Categories:
- ✅ Tambah kategori baru
- ✅ Edit kategori existing
- ✅ Hapus kategori (dengan konfirmasi)
- ✅ Validasi error handling
- ✅ Flash message muncul

#### Services:
- ✅ Tambah unit baru dengan upload foto
- ✅ Edit unit, ganti foto lama
- ✅ Hapus unit (foto ikut terhapus)
- ✅ Live preview foto sebelum upload
- ✅ Validasi file (max 2MB, format)

---

## 📊 Database Schema

### **Categories Table:**
```sql
id, name, slug, created_at, updated_at
```

### **Services Table:**
```sql
id, category_id, name, slug, price, description, image, status, created_at, updated_at
```

**Relationship:**
- Categories `hasMany` Services
- Services `belongsTo` Category
- `onDelete('cascade')` → Hapus kategori = hapus semua unit-nya

---

## 🛠️ Troubleshooting

### **1. Foto tidak muncul**
```bash
# Pastikan sudah jalanin:
php artisan storage:link

# Check folder exist:
ls storage/app/public/services
```

### **2. 403 Forbidden saat akses admin**
- Check role user di database (harus `admin`)
- Check middleware sudah registered di `bootstrap/app.php`

### **3. Validation error tidak muncul**
- Check `@error` directive di view
- Check validation rules di controller

### **4. Flash message tidak hilang**
- Check JavaScript di layout sudah load
- Check ID element `flash-message` exist

---

## 📦 Routes List

Jalankan command untuk lihat semua routes:

```bash
php artisan route:list --name=admin
```

**Output:**
```
POST   admin/categories ..................... admin.categories.store
GET    admin/categories ..................... admin.categories.index
GET    admin/categories/create .............. admin.categories.create
PUT    admin/categories/{category} .......... admin.categories.update
DELETE admin/categories/{category} .......... admin.categories.destroy
GET    admin/categories/{category}/edit ..... admin.categories.edit
POST   admin/services ....................... admin.services.store
GET    admin/services ....................... admin.services.index
GET    admin/services/create ................ admin.services.create
PUT    admin/services/{service} ............. admin.services.update
DELETE admin/services/{service} ............. admin.services.destroy
GET    admin/services/{service}/edit ........ admin.services.edit
```

---

## 🎓 Learning Resources

### **Konsep yang Dipakai:**

1. **Resourceful Routes** - Route::resource()
2. **Middleware Aliases** - Custom role middleware
3. **Eloquent ORM** - Model relationships
4. **Blade Templates** - Master layout, @extends, @section
5. **File Upload** - Storage facade, disk 'public'
6. **Form Validation** - Request validation dengan custom messages
7. **Flash Messages** - Session()->with()
8. **CSRF Protection** - @csrf directive
9. **Method Spoofing** - @method('PUT'), @method('DELETE')
10. **Eager Loading** - with(), withCount()

---

## 📈 Next Steps (Future Enhancement)

- [ ] Dashboard admin dengan statistik
- [ ] User management CRUD
- [ ] Applicants management
- [ ] Service booking system
- [ ] Export data (PDF/Excel)
- [ ] Activity logs
- [ ] Image optimization/resize
- [ ] Soft deletes
- [ ] Search & filter
- [ ] Pagination

---

## 👥 Credits

**Developer:** Chandra, Nauval, Firda
**Project:** Tubes Praktikum Web 2025
**Framework:** Laravel 12
**Theme:** CoreLogic Defense Solutions

---

## 📞 Support

Kalau ada error atau pertanyaan:
1. Check dokumentasi komentar di setiap file
2. Jalankan `php artisan route:list` untuk debug routes
3. Check Laravel log: `storage/logs/laravel.log`
4. Check browser console untuk JavaScript errors

---

**Status:** ✅ Production Ready
**Last Updated:** 2025-12-11
**Version:** 1.0.0
