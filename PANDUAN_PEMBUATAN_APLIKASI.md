# 📘 PANDUAN LENGKAP: MEMBUAT APLIKASI ARTILIA DARI NOL

## 🎯 Deskripsi Aplikasi
Artilia adalah **Sistem Manajemen Inventori** yang memungkinkan pengelolaan stok barang, peminjaman, dan laporan. Aplikasi ini memiliki dua role utama: **Admin** dan **User**.

### Fitur Utama:
- ✅ Autentikasi dan Manajemen User (Admin & User)
- ✅ Manajemen Inventori (Barang, Kategori, Supplier)
- ✅ Tracking Barang Masuk & Keluar
- ✅ Sistem Peminjaman Barang
- ✅ Laporan (Inventory, Outgoing, Incoming, Suppliers)
- ✅ Dashboard dengan Statistik
- ✅ Log Aktivitas
- ✅ Manajemen Profil

---

## 📋 DAFTAR ISI
1. [Persiapan Lingkungan Pengembangan](#1-persiapan-lingkungan-pengembangan)
2. [Instalasi Laravel](#2-instalasi-laravel)
3. [Konfigurasi Database](#3-konfigurasi-database)
4. [Instalasi Dependencies](#4-instalasi-dependencies)
5. [Setup Authentication](#5-setup-authentication)
6. [Membuat Models & Migrations](#6-membuat-models--migrations)
7. [Membuat Enums](#7-membuat-enums)
8. [Membuat Controllers](#8-membuat-controllers)
9. [Setup Routes](#9-setup-routes)
10. [Membuat Middleware](#10-membuat-middleware)
11. [Membuat Services](#11-membuat-services)
12. [Membuat Views (Blade Templates)](#12-membuat-views-blade-templates)
13. [Setup Frontend (Tailwind CSS & Vue)](#13-setup-frontend-tailwind-css--vue)
14. [Membuat Seeders](#14-membuat-seeders)
15. [Testing & Deploy](#15-testing--deploy)

---

## 1. PERSIAPAN LINGKUNGAN PENGEMBANGAN

### 1.1 Persyaratan Sistem
- **PHP**: 8.2 atau lebih tinggi
- **Composer**: Versi terbaru
- **Node.js**: 18.x atau lebih tinggi
- **NPM**: Versi terbaru
- **Database**: MySQL 8.0+ atau MariaDB 10.3+
- **Web Server**: Apache/Nginx (atau Laragon untuk Windows)

### 1.2 Instalasi Tools
#### Windows (Laragon):
```bash
# Download dan instal Laragon dari https://laragon.org/
# Laragon sudah termasuk PHP, MySQL, Apache, dan Composer
```

#### Mac/Linux:
```bash
# Install PHP
brew install php@8.2  # Mac
# atau
sudo apt install php8.2 php8.2-cli php8.2-mysql  # Ubuntu

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js & NPM
# Download dari https://nodejs.org/
```

### 1.3 Verifikasi Instalasi
```bash
php -v          # Harus PHP 8.2+
composer -v     # Harus terinstal
node -v         # Harus Node 18+
npm -v          # Harus terinstal
```

---

## 2. INSTALASI LARAVEL

### 2.1 Membuat Project Laravel Baru
```bash
# Buat direktori project
cd C:\laragon\www  # Windows dengan Laragon
# atau
cd ~/projects      # Mac/Linux

# Install Laravel project baru
composer create-project laravel/laravel artilia

# Masuk ke direktori project
cd artilia
```

### 2.2 Verifikasi Instalasi
```bash
# Jalankan server development
php artisan serve

# Buka browser: http://localhost:8000
# Harus menampilkan halaman welcome Laravel
```

---

## 3. KONFIGURASI DATABASE

### 3.1 Buat Database
#### Via MySQL Command Line:
```sql
CREATE DATABASE artilia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Via phpMyAdmin (Laragon):
1. Buka http://localhost/phpmyadmin
2. Klik "New" → Nama database: `artilia` → Create

### 3.2 Konfigurasi .env
```bash
# Copy file .env.example ke .env
cp .env.example .env  # Mac/Linux
# atau copy .env.example .env  # Windows CMD

# Edit file .env
```

Edit file `.env` dengan konfigurasi database:
```env
APP_NAME="Artilia"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artilia
DB_USERNAME=root
DB_PASSWORD=

# Generate application key
php artisan key:generate
```

---

## 4. INSTALASI DEPENDENCIES

### 4.1 Install PHP Dependencies (Composer)
```bash
composer require laravel/framework:^11.31
composer require livewire/livewire:^3.6
composer require inertiajs/inertia-laravel:^2.0
composer require barryvdh/laravel-dompdf:^2.2
composer require laravel/tinker:^2.9

# Development dependencies
composer require --dev laravel/pint:^1.13
composer require --dev fakerphp/faker:^1.23
```

### 4.2 Install Frontend Dependencies (NPM)
```bash
# Install dependencies
npm install

# Install packages tambahan
npm install @inertiajs/inertia@^0.11.1
npm install @inertiajs/vue3@^2.0.14
npm install vue@^3.5.17
npm install axios@^1.7.4

# Install Tailwind CSS dan plugins
npm install -D tailwindcss@^3.4.17
npm install -D @tailwindcss/forms@^0.5.10
npm install -D @tailwindcss/typography@^0.5.16
npm install -D @tailwindcss/aspect-ratio@^0.4.2
npm install -D @tailwindcss/line-clamp@^0.4.4
npm install -D @tailwindcss/vite@^4.1.5
npm install -D autoprefixer@^10.4.21
npm install -D postcss@^8.5.3
npm install -D vite@^6.0.11
npm install -D @vitejs/plugin-vue@^6.0.0
npm install -D laravel-vite-plugin@^1.2.0
npm install -D prettier@^3.6.2
```

### 4.3 Konfigurasi Vite
Buat/Edit `vite.config.js`:
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
```

### 4.4 Konfigurasi Tailwind CSS
Buat file `tailwind.config.js`:
```javascript
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';
import lineClamp from '@tailwindcss/line-clamp';

export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {},
    },
    plugins: [
        forms,
        typography,
        aspectRatio,
        lineClamp,
    ],
};
```

Buat file `postcss.config.js`:
```javascript
export default {
    plugins: {
        tailwindcss: {},
        autoprefixer: {},
    },
};
```

---

## 5. SETUP AUTHENTICATION

### 5.1 Install Laravel Breeze (Opsional - atau buat manual)
```bash
# Opsi 1: Install Breeze (lebih mudah)
composer require laravel/breeze --dev
php artisan breeze:install blade

# Opsi 2: Buat manual (sesuai aplikasi ini)
# Kita akan membuat manual karena aplikasi ini sudah punya sistem auth sendiri
```

### 5.2 Setup Authentication Manual
Buat file `app/helpers.php`:
```php
<?php

if (!function_exists('currentUser')) {
    function currentUser() {
        return auth()->user();
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return auth()->check() && auth()->user()->hasRole('admin');
    }
}

if (!function_exists('isUser')) {
    function isUser() {
        return auth()->check() && auth()->user()->hasRole('user');
    }
}
```

Daftarkan di `composer.json`:
```json
"autoload": {
    "files": [
        "app/helpers.php"
    ],
    "psr-4": {
        "App\\": "app/"
    }
}
```

Jalankan:
```bash
composer dump-autoload
```

---

## 6. MEMBUAT MODELS & MIGRATIONS

### 6.1 Migration Users (Default sudah ada, tapi perlu dimodifikasi)
```bash
php artisan make:migration modify_users_table
```

Edit migration:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bio')->nullable();
            $table->string('profil')->nullable();
            $table->string('role')->default('user');
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'profil', 'role', 'is_active']);
        });
    }
};
```

### 6.2 Migration Categories
```bash
php artisan make:migration create_categories_table
```

Isi migration:
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->text('deskripsi')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

### 6.3 Migration Suppliers
```bash
php artisan make:migration create_suppliers_table
```

Isi migration:
```php
Schema::create('suppliers', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('email')->nullable();
    $table->string('telepon')->nullable();
    $table->text('alamat')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

### 6.4 Migration Items
```bash
php artisan make:migration create_items_table
```

Isi migration:
```php
Schema::create('items', function (Blueprint $table) {
    $table->id();
    $table->string('kode')->unique();
    $table->string('nama');
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
    $table->integer('stok_total')->default(0);
    $table->integer('stok_reguler')->default(0);
    $table->integer('stok_peminjaman')->default(0);
    $table->bigInteger('harga')->default(0);
    $table->string('gambar')->nullable();
    $table->text('keterangan')->nullable();
    $table->string('type')->default('consumable');
    $table->timestamps();
    $table->softDeletes();
});
```

### 6.5 Migration Inventories
```bash
php artisan make:migration create_inventories_table
```

Isi migration:
```php
Schema::create('inventories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('item_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
    $table->enum('tipe', ['masuk', 'keluar']);
    $table->integer('jumlah');
    $table->enum('status', ['received', 'processed', 'cancelled'])->default('received');
    $table->text('keterangan')->nullable();
    $table->morphs('reference'); // untuk relasi dengan borrowing/outgoing
    $table->timestamps();
});
```

### 6.6 Migration Borrowing Requests
```bash
php artisan make:migration create_borrowing_requests_table
```

Isi migration:
```php
Schema::create('borrowing_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('item_id')->constrained()->onDelete('cascade');
    $table->integer('jumlah');
    $table->date('tanggal_pinjam');
    $table->date('tanggal_kembali');
    $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
    $table->text('keterangan')->nullable();
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();
});
```

### 6.7 Migration Borrowings
```bash
php artisan make:migration create_borrowings_table
```

Isi migration:
```php
Schema::create('borrowings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('item_id')->constrained()->onDelete('cascade');
    $table->integer('jumlah');
    $table->date('tanggal_pinjam');
    $table->date('tanggal_kembali');
    $table->date('tanggal_dikembalikan')->nullable();
    $table->enum('status', ['active', 'returned', 'overdue'])->default('active');
    $table->text('keterangan')->nullable();
    $table->timestamps();
});
```

### 6.8 Migration Companies (Settings)
```bash
php artisan make:migration create_companies_table
```

Isi migration:
```php
Schema::create('companies', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('email')->nullable();
    $table->string('telepon')->nullable();
    $table->text('alamat')->nullable();
    $table->string('logo')->nullable();
    $table->timestamps();
});
```

### 6.9 Migration Login Logs
```bash
php artisan make:migration create_logins_table
```

Isi migration:
```php
Schema::create('logins', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('ip_address');
    $table->string('user_agent')->nullable();
    $table->timestamp('login_at');
    $table->timestamps();
});
```

### 6.10 Jalankan Migrations
```bash
php artisan migrate
```

---

## 7. MEMBUAT ENUMS

### 7.1 Enum UsersRole
```bash
php artisan make:enum UsersRole
```

File: `app/Enums/UsersRole.php`:
```php
<?php

namespace App\Enums;

enum UsersRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::USER => 'User',
        };
    }
}
```

### 7.2 Enum ItemType
```bash
php artisan make:enum ItemType
```

File: `app/Enums/ItemType.php`:
```php
<?php

namespace App\Enums;

enum ItemType: string
{
    case CONSUMABLE = 'consumable';
    case BORROWABLE = 'borrowable';
    case BOTH = 'both';

    public function label(): string
    {
        return match($this) {
            self::CONSUMABLE => 'Consumable',
            self::BORROWABLE => 'Borrowable',
            self::BOTH => 'Both',
        };
    }
}
```

---

## 8. MEMBUAT MODELS

### 8.1 Model User
File: `app/Models/User.php` (modifikasi yang sudah ada):
```php
<?php

namespace App\Models;

use App\Enums\UsersRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'bio',
        'email',
        'profil',
        'password',
        'role',
        'is_active',
    ];

    public function hasRole(UsersRole|string $role): bool
    {
        if ($this->role instanceof UsersRole) {
            return $this->role->value === (is_string($role) ? $role : $role->value);
        }
        return $this->role === (is_string($role) ? $role : $role->value);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UsersRole::class,
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
```

### 8.2 Model Category
```bash
php artisan make:model Category
```

File: `app/Models/Category.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
```

### 8.3 Model Supplier
```bash
php artisan make:model Supplier
```

File: `app/Models/Supplier.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
```

### 8.4 Model Item
```bash
php artisan make:model Item
```

File: `app/Models/Item.php`:
```php
<?php

namespace App\Models;

use App\Enums\ItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'category_id',
        'supplier_id',
        'stok_total',
        'stok_reguler',
        'stok_peminjaman',
        'harga',
        'gambar',
        'keterangan',
        'type',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok_total' => 'integer',
        'stok_reguler' => 'integer',
        'stok_peminjaman' => 'integer',
        'type' => ItemType::class,
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function jumlahMasuk()
    {
        return $this->inventories()->where('tipe', 'masuk')->sum('jumlah');
    }

    public function jumlahKeluar()
    {
        return $this->inventories()->where('tipe', 'keluar')->sum('jumlah');
    }

    public function updateStokTotal()
    {
        $this->stok_total = $this->stok_reguler + $this->stok_peminjaman;
        $this->save();
    }

    public function addStok($jumlah, $tipe = 'reguler')
    {
        if ($tipe === 'peminjaman') {
            $this->increment('stok_peminjaman', $jumlah);
        } else {
            $this->increment('stok_reguler', $jumlah);
        }
        $this->updateStokTotal();
    }

    public function reduceStok($jumlah, $tipe = 'reguler')
    {
        if ($tipe === 'peminjaman') {
            $this->decrement('stok_peminjaman', $jumlah);
        } else {
            $this->decrement('stok_reguler', $jumlah);
        }
        $this->updateStokTotal();
    }

    public function getAvailableStokForBorrowing()
    {
        return $this->stok_peminjaman;
    }

    public function getAvailableStokForSale()
    {
        return $this->stok_reguler;
    }
}
```

### 8.5 Model Inventory
```bash
php artisan make:model Inventory
```

File: `app/Models/Inventory.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    public const TYPE_IN = 'masuk';
    public const TYPE_OUT = 'keluar';

    public const STATUS_RECEIVED = 'received';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'item_id',
        'user_id',
        'tipe',
        'jumlah',
        'status',
        'keterangan',
        'reference_id',
        'reference_type',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeIncoming(Builder $query): Builder
    {
        return $query->where('tipe', self::TYPE_IN);
    }

    public function scopeOutgoing(Builder $query): Builder
    {
        return $query->where('tipe', self::TYPE_OUT);
    }

    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function reference()
    {
        return $this->morphTo('reference');
    }
}
```

### 8.6 Model BorrowingRequest
```bash
php artisan make:model BorrowingRequest
```

File: `app/Models/BorrowingRequest.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'keterangan',
        'completed_at',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
```

### 8.7 Model Borrowing
```bash
php artisan make:model Borrowing
```

File: `app/Models/Borrowing.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
```

### 8.8 Model Company
```bash
php artisan make:model Company
```

File: `app/Models/Company.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat',
        'logo',
    ];
}
```

### 8.9 Model LoginLog
```bash
php artisan make:model LoginLog
```

File: `app/Models/LoginLog.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'login_at',
    ];

    protected $casts = [
        'login_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## 9. MEMBUAT CONTROLLERS

### 9.1 AccessController (Login/Logout)
```bash
php artisan make:controller AccessController
```

File: `app/Http/Controllers/AccessController.php`:
```php
<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan.']);
            }

            // Log login activity
            LoginLog::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
            ]);

            $request->session()->regenerate();

            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('loginform');
    }

    public function ShowDashboard()
    {
        // Admin dashboard logic
        return view('admin.dashboard');
    }

    public function ShowDashboardUser()
    {
        // User dashboard logic
        return view('user.dashboard');
    }
}
```

### 9.2 InventoryController
```bash
php artisan make:controller InventoryController --resource
```

Implementasikan CRUD untuk inventory management.

### 9.3 UsersAccountController
```bash
php artisan make:controller UsersAccountController --resource
```

Implementasikan CRUD untuk user management.

### 9.4 CategoryController
```bash
php artisan make:controller CategoryController --resource
```

### 9.5 SupplierController
```bash
php artisan make:controller SupplierController --resource
```

### 9.6 BorrowingController
```bash
php artisan make:controller BorrowingController --resource
```

### 9.7 UserBorrowingController
```bash
php artisan make:controller UserBorrowingController
```

### 9.8 AdminBorrowingRequestController
```bash
php artisan make:controller AdminBorrowingRequestController
```

### 9.9 IncomingItemsController & OutgoingItemsController
```bash
php artisan make:controller IncomingItemsController --resource
php artisan make:controller OutgoingItemsController --resource
```

### 9.10 ReportsController
```bash
php artisan make:controller ReportsController
```

### 9.11 ActivityController
```bash
php artisan make:controller ActivityController
```

### 9.12 ProfileController & UserProfileController
```bash
php artisan make:controller ProfileController
php artisan make:controller UserProfileController
```

### 9.13 CompanyController
```bash
php artisan make:controller CompanyController
```

---

## 10. MEMBUAT MIDDLEWARE

### 10.1 RoleMiddleware
```bash
php artisan make:middleware RoleMiddleware
```

File: `app/Http/Middleware/RoleMiddleware.php`:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('loginform');
        }

        $user = Auth::user();
        
        if (!$user->hasRole($role)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
```

Daftarkan di `bootstrap/app.php` atau `app/Http/Kernel.php`:
```php
// Di bootstrap/app.php (Laravel 11)
$middleware->alias([
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);
```

---

## 11. MEMBUAT SERVICES

### 11.1 AuthService
File: `app/Services/AuthService.php`:
```php
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
```

### 11.2 InventoryService
File: `app/Services/InventoryService.php`:
```php
<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Item;

class InventoryService
{
    public function createIncomingInventory(array $data): Inventory
    {
        $inventory = Inventory::create([
            'item_id' => $data['item_id'],
            'user_id' => auth()->id(),
            'tipe' => Inventory::TYPE_IN,
            'jumlah' => $data['jumlah'],
            'status' => Inventory::STATUS_RECEIVED,
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        // Update item stock
        $item = Item::find($data['item_id']);
        $item->addStok($data['jumlah'], $data['stock_type'] ?? 'reguler');

        return $inventory;
    }

    public function createOutgoingInventory(array $data): Inventory
    {
        $inventory = Inventory::create([
            'item_id' => $data['item_id'],
            'user_id' => auth()->id(),
            'tipe' => Inventory::TYPE_OUT,
            'jumlah' => $data['jumlah'],
            'status' => Inventory::STATUS_PROCESSED,
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        // Update item stock
        $item = Item::find($data['item_id']);
        $item->reduceStok($data['jumlah'], $data['stock_type'] ?? 'reguler');

        return $inventory;
    }
}
```

### 11.3 ItemService
File: `app/Services/ItemService.php`:
```php
<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemService
{
    public function createItem(array $data): Item
    {
        if (isset($data['gambar'])) {
            $data['gambar'] = $this->storeImage($data['gambar']);
        }

        return Item::create($data);
    }

    public function updateItem(Item $item, array $data): Item
    {
        if (isset($data['gambar'])) {
            if ($item->gambar) {
                Storage::delete($item->gambar);
            }
            $data['gambar'] = $this->storeImage($data['gambar']);
        }

        $item->update($data);
        return $item;
    }

    private function storeImage($file): string
    {
        return $file->store('items', 'public');
    }
}
```

### 11.4 InvoiceService
File: `app/Services/InvoiceService.php`:
```php
<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function generateInventoryReport($data)
    {
        $pdf = Pdf::loadView('admin.reports.inventory-pdf', $data);
        return $pdf->download('inventory-report.pdf');
    }
}
```

---

## 12. MEMBUAT VIEWS (BLADE TEMPLATES)

### 12.1 Layout Structure
```
resources/views/
├── layouts/
│   └── app.blade.php
├── admin/
│   ├── login.blade.php
│   ├── dashboard.blade.php
│   ├── components/
│   │   ├── sidebar.blade.php
│   │   ├── header.blade.php
│   │   └── ...
│   └── ...
└── user/
    ├── dashboard.blade.php
    └── ...
```

### 12.2 Layout Base (app.blade.php)
File: `resources/views/layouts/app.blade.php`:
```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Artilia')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @yield('content')
</body>
</html>
```

### 12.3 Login Page
File: `resources/views/admin/login.blade.php`:
```blade
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Login ke Artilia
            </h2>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" required>
                @error('email') <span>{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</div>
@endsection
```

### 12.4 Admin Dashboard
File: `resources/views/admin/dashboard.blade.php`:
```blade
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    @include('admin.components.sidebar')
    <div class="ml-64">
        @include('admin.components.header')
        <main class="p-8">
            <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
            <!-- Dashboard content -->
        </main>
    </div>
</div>
@endsection
```

---

## 13. SETUP FRONTEND (TAILWIND CSS & VUE)

### 13.1 Setup CSS
File: `resources/css/app.css`:
```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer components {
    /* Custom components */
}
```

### 13.2 Setup JavaScript
File: `resources/js/app.js`:
```javascript
import './bootstrap';
import '../css/app.css';

// Inertia.js setup (jika menggunakan Inertia)
// import { createApp, h } from 'vue';
// import { createInertiaApp } from '@inertiajs/vue3';

// createInertiaApp({
//     resolve: name => require(`./Pages/${name}`),
//     setup({ el, App, props, plugin }) {
//         createApp({ render: () => h(App, props) })
//             .use(plugin)
//             .mount(el)
//     },
// });
```

File: `resources/js/bootstrap.js`:
```javascript
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

### 13.3 Build Assets
```bash
npm run dev      # Development mode
npm run build    # Production build
```

---

## 14. MEMBUAT SEEDERS

### 14.1 AdminUserSeeder
```bash
php artisan make:seeder AdminUserSeeder
```

File: `database/seeders/AdminUserSeeder.php`:
```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@artilia.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
```

### 14.2 CategorySeeder
```bash
php artisan make:seeder CategorySeeder
```

### 14.3 DatabaseSeeder
File: `database/seeders/DatabaseSeeder.php`:
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
```

### 14.4 Jalankan Seeder
```bash
php artisan db:seed
```

---

## 15. SETUP ROUTES

### 15.1 File routes/web.php
Buat semua routes sesuai dengan struktur aplikasi (lihat contoh di file routes/web.php yang sudah ada).

---

## 16. SETUP PROVIDERS

### 16.1 ComponentsServiceProvider
File: `app/Providers/ComponentsServiceProviders.php`:
```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ComponentsServiceProviders extends ServiceProvider
{
    public function boot(): void
    {
        Blade::component('admin.components.sidebar', 'sidebar');
        Blade::component('admin.components.header', 'header');
        // ... tambahkan components lain
    }
}
```

Daftarkan di `bootstrap/providers.php`:
```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\ComponentsServiceProviders::class,
    // ...
];
```

---

## 17. TESTING & DEPLOY

### 17.1 Jalankan Development Server
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

### 17.2 Akses Aplikasi
- Frontend: http://localhost:8000
- Login dengan:
  - Email: `admin@artilia.com`
  - Password: `password`

### 17.3 Build untuk Production
```bash
# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📝 CATATAN PENTING

1. **File Upload**: Pastikan folder `storage/app/public` dan `public/storage` sudah di-link
   ```bash
   php artisan storage:link
   ```

2. **Permissions**: Pastikan folder `storage` dan `bootstrap/cache` writable
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

3. **Environment**: Jangan lupa setup `.env` dengan benar

4. **Database**: Pastikan semua migrations sudah dijalankan

5. **Composer Autoload**: Setelah menambah file baru, jalankan:
   ```bash
   composer dump-autoload
   ```

---

## 🎉 SELESAI!

Setelah mengikuti semua langkah di atas, aplikasi Artilia sudah siap digunakan. Anda dapat mulai mengembangkan fitur-fitur tambahan sesuai kebutuhan.

**Tips Tambahan:**
- Gunakan Git untuk version control
- Dokumentasikan setiap perubahan penting
- Lakukan testing sebelum deploy ke production
- Backup database secara berkala

---

**Dibuat dengan ❤️ untuk pengembangan aplikasi Artilia**

