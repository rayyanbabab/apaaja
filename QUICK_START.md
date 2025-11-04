# 🚀 QUICK START - ARTILIA

Panduan cepat untuk memulai development aplikasi Artilia.

## ⚡ 5 Langkah Cepat

### 1. Setup Environment (5 menit)
```bash
# Install Laravel project
composer create-project laravel/laravel artilia
cd artilia

# Install dependencies
composer require laravel/framework:^11.31 livewire/livewire:^3.6 inertiajs/inertia-laravel:^2.0 barryvdh/laravel-dompdf:^2.2
npm install
npm install -D tailwindcss @tailwindcss/forms @tailwindcss/typography
npm install vue@^3.5.17 @inertiajs/inertia @inertiajs/vue3
```

### 2. Setup Database (5 menit)
```bash
# Buat database di MySQL
CREATE DATABASE artilia;

# Konfigurasi .env
cp .env.example .env
# Edit .env: set DB_DATABASE=artilia

# Generate key
php artisan key:generate
```

### 3. Jalankan Migrations (5 menit)
```bash
# Copy semua migration dari project ini ke database/migrations/
# Atau buat sesuai struktur yang ada

php artisan migrate
```

### 4. Install Dependencies & Build (5 menit)
```bash
# Setup frontend
npm run build

# Link storage
php artisan storage:link
```

### 5. Jalankan Seeder & Server (5 menit)
```bash
# Seed database
php artisan db:seed

# Jalankan server
php artisan serve
# Di terminal lain:
npm run dev
```

## 📖 Login Default

- **Email**: `admin@artilia.com`
- **Password**: `password`

## 🎯 Struktur Project

```
artilia/
├── app/
│   ├── Enums/          # Enums (UsersRole, ItemType)
│   ├── Http/
│   │   ├── Controllers/ # Semua controllers
│   │   └── Middleware/  # Middleware (RoleMiddleware)
│   ├── Models/          # Semua models
│   └── Services/        # Business logic services
├── database/
│   ├── migrations/      # Database migrations
│   └── seeders/         # Database seeders
├── resources/
│   ├── css/            # Tailwind CSS
│   ├── js/             # Vue.js & Inertia
│   └── views/          # Blade templates
│       ├── admin/      # Admin views
│       └── user/       # User views
└── routes/
    └── web.php         # All routes
```

## 🔧 Perintah Penting

```bash
# Development
php artisan serve          # Laravel server
npm run dev                # Vite dev server

# Database
php artisan migrate        # Run migrations
php artisan migrate:fresh  # Reset & migrate
php artisan db:seed       # Run seeders

# Cache
php artisan config:cache   # Cache config
php artisan route:cache   # Cache routes
php artisan view:cache    # Cache views

# Build
npm run build             # Build production assets
```

## 📚 Dokumentasi Lengkap

Lihat file:
- `PANDUAN_PEMBUATAN_APLIKASI.md` - Panduan lengkap step-by-step
- `CHECKLIST_PEMBUATAN.md` - Checklist untuk tracking progress

## 🐛 Troubleshooting

### Error: Class not found
```bash
composer dump-autoload
```

### Error: Storage link
```bash
php artisan storage:link
```

### Error: Permission denied
```bash
chmod -R 775 storage bootstrap/cache
```

### Error: Vite not found
```bash
npm install
npm run dev
```

---

**Happy Coding! 🎉**

