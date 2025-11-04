# ✅ CHECKLIST PEMBUATAN APLIKASI ARTILIA

## 📦 Persiapan (30 menit)
- [ ] Install PHP 8.2+
- [ ] Install Composer
- [ ] Install Node.js 18+ & NPM
- [ ] Install MySQL/MariaDB
- [ ] Install Laragon (Windows) atau XAMPP/WAMP
- [ ] Verifikasi semua tools terinstal dengan benar

## 🚀 Setup Project (15 menit)
- [ ] Buat project Laravel baru: `composer create-project laravel/laravel artilia`
- [ ] Masuk ke direktori: `cd artilia`
- [ ] Copy `.env.example` ke `.env`
- [ ] Generate app key: `php artisan key:generate`
- [ ] Buat database `artilia` di MySQL
- [ ] Konfigurasi database di `.env`

## 📚 Install Dependencies (20 menit)
- [ ] Install PHP packages: `composer require laravel/framework:^11.31 livewire/livewire:^3.6 inertiajs/inertia-laravel:^2.0 barryvdh/laravel-dompdf:^2.2`
- [ ] Install NPM packages: `npm install`
- [ ] Install Tailwind CSS: `npm install -D tailwindcss @tailwindcss/forms @tailwindcss/typography`
- [ ] Install Vue 3 & Inertia: `npm install vue@^3.5.17 @inertiajs/inertia @inertiajs/vue3`
- [ ] Setup `vite.config.js`
- [ ] Setup `tailwind.config.js`
- [ ] Setup `postcss.config.js`

## 🗄️ Database Setup (45 menit)
- [ ] Buat migration untuk modify users table (tambah: bio, profil, role, is_active)
- [ ] Buat migration create_categories_table
- [ ] Buat migration create_suppliers_table
- [ ] Buat migration create_items_table
- [ ] Buat migration create_inventories_table
- [ ] Buat migration create_borrowing_requests_table
- [ ] Buat migration create_borrowings_table
- [ ] Buat migration create_companies_table
- [ ] Buat migration create_logins_table
- [ ] Jalankan migrations: `php artisan migrate`

## 📝 Models & Enums (30 menit)
- [ ] Buat enum UsersRole
- [ ] Buat enum ItemType
- [ ] Modifikasi model User (tambah relasi & method hasRole)
- [ ] Buat model Category
- [ ] Buat model Supplier
- [ ] Buat model Item
- [ ] Buat model Inventory
- [ ] Buat model BorrowingRequest
- [ ] Buat model Borrowing
- [ ] Buat model Company
- [ ] Buat model LoginLog

## 🎮 Controllers (2 jam)
- [ ] Buat AccessController (login, logout, dashboard)
- [ ] Buat InventoryController (CRUD inventory)
- [ ] Buat UsersAccountController (CRUD users)
- [ ] Buat CategoryController (CRUD categories)
- [ ] Buat SupplierController (CRUD suppliers)
- [ ] Buat BorrowingController (CRUD borrowings untuk admin)
- [ ] Buat UserBorrowingController (untuk user request borrowing)
- [ ] Buat AdminBorrowingRequestController (approve/reject requests)
- [ ] Buat IncomingItemsController (barang masuk)
- [ ] Buat OutgoingItemsController (barang keluar)
- [ ] Buat ReportsController (generate laporan)
- [ ] Buat ActivityController (log aktivitas)
- [ ] Buat ProfileController (profil admin)
- [ ] Buat UserProfileController (profil user)
- [ ] Buat CompanyController (settings perusahaan)

## 🔒 Middleware & Services (30 menit)
- [ ] Buat RoleMiddleware (cek role user)
- [ ] Daftarkan middleware di bootstrap/app.php
- [ ] Buat AuthService
- [ ] Buat InventoryService
- [ ] Buat ItemService
- [ ] Buat InvoiceService

## 🛣️ Routes (30 menit)
- [ ] Setup route login/logout
- [ ] Setup route group admin dengan middleware
- [ ] Setup route group user dengan middleware
- [ ] Setup semua route CRUD untuk setiap fitur
- [ ] Test semua routes dengan `php artisan route:list`

## 🎨 Views & Frontend (3-4 jam)
- [ ] Buat layout base (app.blade.php)
- [ ] Buat view login
- [ ] Buat admin layout (sidebar, header)
- [ ] Buat admin dashboard
- [ ] Buat user layout (sidebar, header)
- [ ] Buat user dashboard
- [ ] Buat views untuk CRUD inventory
- [ ] Buat views untuk CRUD users
- [ ] Buat views untuk CRUD categories
- [ ] Buat views untuk CRUD suppliers
- [ ] Buat views untuk borrowing management
- [ ] Buat views untuk reports
- [ ] Buat components (sidebar, header, forms, dll)
- [ ] Setup Tailwind CSS di app.css
- [ ] Setup JavaScript di app.js
- [ ] Build assets: `npm run build`

## 🌱 Seeders (15 menit)
- [ ] Buat AdminUserSeeder (admin default)
- [ ] Buat CategorySeeder (kategori contoh)
- [ ] Buat SupplierSeeder (supplier contoh)
- [ ] Update DatabaseSeeder
- [ ] Jalankan seeder: `php artisan db:seed`

## 🔧 Providers & Helpers (20 menit)
- [ ] Buat file helpers.php
- [ ] Daftarkan helpers.php di composer.json
- [ ] Buat ComponentsServiceProvider
- [ ] Daftarkan provider di bootstrap/providers.php
- [ ] Jalankan: `composer dump-autoload`

## 📦 Storage & File Upload (10 menit)
- [ ] Setup storage link: `php artisan storage:link`
- [ ] Pastikan folder storage writable
- [ ] Test upload gambar

## ✅ Testing (30 menit)
- [ ] Test login dengan akun admin
- [ ] Test semua fitur CRUD
- [ ] Test role-based access (admin vs user)
- [ ] Test file upload
- [ ] Test generate laporan PDF
- [ ] Test semua fungsi utama aplikasi

## 🚀 Deployment Prep (15 menit)
- [ ] Set APP_ENV=production di .env
- [ ] Set APP_DEBUG=false
- [ ] Build production assets: `npm run build`
- [ ] Cache config: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`

## 📋 Total Estimasi Waktu: 10-12 jam

---

## 🎯 Prioritas Pengembangan

### Fase 1: Core (Wajib)
1. ✅ Setup project & database
2. ✅ Authentication & Authorization
3. ✅ Basic CRUD (Items, Categories, Suppliers)
4. ✅ Inventory Management (Masuk/Keluar)

### Fase 2: Features (Penting)
5. ✅ Borrowing System
6. ✅ Reports
7. ✅ User Management
8. ✅ Dashboard & Statistics

### Fase 3: Enhancement (Opsional)
9. ✅ Activity Logs
10. ✅ Advanced Search
11. ✅ Export/Import
12. ✅ Notifications

---

## 📝 Tips

- **Mulai dari yang kecil**: Buat satu fitur lengkap sebelum lanjut ke fitur lain
- **Test terus**: Setiap kali selesai satu fitur, test dulu
- **Gunakan Git**: Commit setiap perubahan penting
- **Dokumentasi**: Catat setiap perubahan yang penting
- **Backup**: Backup database secara berkala

---

**Selamat Membangun! 🚀**

