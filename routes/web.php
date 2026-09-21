<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminBorrowingRequestController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\IncomingItemsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OutgoingItemsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TabsInventoryController;
use App\Http\Controllers\UserBorrowingController;
use App\Http\Controllers\UsersAccountController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\AdminProcurementController;
use App\Http\Controllers\CalibrationController;
use App\Http\Controllers\LogisticsController;
use App\Http\Controllers\ToolingKitController;
use App\Http\Controllers\UserProcurementController;
use App\Http\Controllers\SignatureController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AccessController::class, 'showLoginForm'])->name('loginform');

Route::get('/login', [AccessController::class, 'showLoginForm']);
Route::post('/login', [AccessController::class, 'login'])->name('login');
Route::post('/logout', [AccessController::class, 'logout'])->name('logout');

// Public BAP QR verification (No auth required)
Route::get('/verify/bap/{token}', [SignatureController::class, 'verify'])->name('bap.verify');

Route::prefix('admin')->middleware('auth', RoleMiddleware::class.':admin')->name('admin.')->group(function () {
    Route::get('/search', [InventoryController::class, 'search'])->name('search');

    Route::get('/dashboard', [AccessController::class, 'showDashboard'])->name('dashboard');
    Route::get('/dashboard-manage', [AccessController::class, 'ShowDashboardManage'])->name('dashboard.manage');
    Route::get('/dashboard-operation', [AccessController::class, 'ShowDashboardOperation'])->name('dashboard.operation');
    Route::get('/dashboard-statistic', [AccessController::class, 'ShowDashboardStatistic'])->name('dashboard.statistic');
    Route::get('/dashboard-user', [AccessController::class, 'ShowDashboardUser'])->name('dashboard.user');


    // -------------------------------------------------------
    // ADMIN-ONLY: User Management
    // -------------------------------------------------------
    Route::middleware(RoleMiddleware::class.':admin')->group(function () {
        Route::prefix('content')->name('content.')->group(function () {
            Route::post('/store-users', [UsersAccountController::class, 'store'])->name('savedatausers');
            Route::get('/list-users', [UsersAccountController::class, 'list'])->name('listusers');
            Route::get('/create-users', [UsersAccountController::class, 'create'])->name('createusers');
            Route::get('/show-users/{id}', [UsersAccountController::class, 'show'])->name('showusers');
            Route::post('/update-users/{id}', [UsersAccountController::class, 'update'])->name('updateusers');
            Route::get('/update-users/{id}', [UsersAccountController::class, 'update'])->name('updateusers.get');
            Route::post('/edit-users/{id}', [UsersAccountController::class, 'edit'])->name('editusers');
            Route::get('/edit-users/{id}', [UsersAccountController::class, 'edit'])->name('editusers.get');
            Route::patch('/update-users/{id}', [UsersAccountController::class, 'updateRole'])->name('updateuserrole');
            Route::delete('/delete-users/{id}', [UsersAccountController::class, 'destroy'])->name('deleteusers');
            Route::post('/bulk-delete-users', [UsersAccountController::class, 'bulkDelete'])->name('bulkdeleteusers');
            Route::post('/toggle-status-users/{id}', [UsersAccountController::class, 'toggleStatus'])->name('togglestatususers');
            Route::get('/print-id-card/{id}', [UsersAccountController::class, 'printIdCard'])->name('printidcard');
        });
    });


    // Inventory management routes (view, edit, delete only)
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/show/{id}', [InventoryController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [InventoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [InventoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [InventoryController::class, 'destroy'])->name('destroy');
        
        // Only allow adding new items (without stock)
        Route::get('/add-items', [InventoryController::class, 'additems'])->name('add');
        Route::post('/store', [InventoryController::class, 'store'])->name('store');
        
        Route::get('/{item}/print-label', [InventoryController::class, 'printLabel'])->name('print-label');
        Route::get('/lookup/by-kode', [InventoryController::class, 'lookupByKode'])->name('lookup');
        
        Route::prefix('tabs')->name('tab.')->group(function () {
            Route::get('/detail', [TabsInventoryController::class, 'Detail'])->name('detail');
            Route::get('/history', [TabsInventoryController::class, 'History'])->name('history');
            Route::get('/history/{status}', [TabsInventoryController::class, 'historyByStatus'])->name('history.status');
        });
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/profile/update', [ProfileController::class, 'update'])->name('update');
        Route::patch('/profile/update-email', [ProfileController::class, 'updateEmail'])->name('update-email');
        Route::patch('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');
        Route::patch('/profile/update-whatsapp', [ProfileController::class, 'updateWhatsapp'])->name('update-whatsapp');
    });


    // Reports Routes
    Route::get('/insights', [AnalyticsController::class, 'index'])->name('insights.index');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/inventory', [ReportsController::class, 'inventoryReport'])->name('reports.inventory');
    Route::get('/reports/outgoing', [ReportsController::class, 'outgoingReport'])->name('reports.outgoing');
    Route::get('/reports/incoming', [ReportsController::class, 'incomingReport'])->name('reports.incoming');
    Route::get('/reports/suppliers', [ReportsController::class, 'suppliersReport'])->name('reports.suppliers');
    Route::get('/reports/borrowing', [ReportsController::class, 'borrowingReport'])->name('reports.borrowing');

    // Activities Routes
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/export', [ActivityController::class, 'export'])->name('activities.export');

    // -------------------------------------------------------
    // ADMIN-ONLY: Categories, Suppliers & Settings
    // -------------------------------------------------------
    Route::middleware(RoleMiddleware::class.':admin')->group(function () {

        // Categories Management
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Suppliers Management
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        // Locations Management
        Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
        Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
        Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
        Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');
        Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
        Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
        Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/company', [SettingController::class, 'updateCompany'])->name('settings.company.update');
        Route::post('/settings/test-whatsapp', [SettingController::class, 'testWhatsapp'])->name('settings.test-whatsapp');

        // Stock Opname
        Route::prefix('stock-opnames')->name('stock-opnames.')->group(function () {
            Route::get('/', [App\Http\Controllers\StockOpnameController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\StockOpnameController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\StockOpnameController::class, 'store'])->name('store');
            Route::get('/{stockOpname}', [App\Http\Controllers\StockOpnameController::class, 'show'])->name('show');
            Route::post('/{stockOpname}/complete', [App\Http\Controllers\StockOpnameController::class, 'complete'])->name('complete');
            Route::post('/{stockOpname}/cancel', [App\Http\Controllers\StockOpnameController::class, 'cancel'])->name('cancel');
            Route::patch('/items/{item}', [App\Http\Controllers\StockOpnameController::class, 'updateItem'])->name('items.update');
        });

        // -------------------------------------------------------
        // Import Routes (admin only)
        // -------------------------------------------------------
        Route::prefix('import')->name('import.')->group(function () {
            Route::get('/items/template',  [ImportController::class, 'itemsTemplate'])->name('items.template');
            Route::post('/items',          [ImportController::class, 'importItems'])->name('items');
            Route::get('/users/template',  [ImportController::class, 'usersTemplate'])->name('users.template');
            Route::post('/users',          [ImportController::class, 'importUsers'])->name('users');
        });
    });

    Route::prefix('outgoing')->name('outgoing.')->group(function () {
        Route::get('/', [OutgoingItemsController::class, 'index'])->name('index');
        Route::get('/create', [OutgoingItemsController::class, 'create'])->name('create');
        Route::post('/', [OutgoingItemsController::class, 'store'])->name('store');
        Route::delete('/bulk-delete', [OutgoingItemsController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/{outgoingItem}', [OutgoingItemsController::class, 'show'])->name('show');
        Route::get('/{outgoingItem}/edit', [OutgoingItemsController::class, 'edit'])->name('edit');
        Route::put('/{outgoingItem}', [OutgoingItemsController::class, 'update'])->name('update');
        Route::delete('/{outgoingItem}', [OutgoingItemsController::class, 'destroy'])->name('destroy');
        Route::post('/{outgoingItem}/return', [OutgoingItemsController::class, 'returnItem'])->name('return');
    });

    // Incoming Items Routes
    Route::prefix('incoming')->name('incoming.')->group(function () {
        Route::get('/', [IncomingItemsController::class, 'index'])->name('index');
        Route::get('/create', [IncomingItemsController::class, 'create'])->name('create');
        Route::post('/', [IncomingItemsController::class, 'store'])->name('store');
        Route::delete('/bulk-delete', [IncomingItemsController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/{incomingItem}', [IncomingItemsController::class, 'show'])->name('show');
        Route::get('/{incomingItem}/edit', [IncomingItemsController::class, 'edit'])->name('edit');
        Route::put('/{incomingItem}', [IncomingItemsController::class, 'update'])->name('update');
        Route::delete('/{incomingItem}', [IncomingItemsController::class, 'destroy'])->name('destroy');
    });

    // Borrowing Routes
    Route::prefix('borrowings')->name('borrowings.')->group(function () {
        Route::get('/', [BorrowingController::class, 'index'])->name('index');
        Route::get('/history', [BorrowingController::class, 'history'])->name('history');
        Route::get('/create', [BorrowingController::class, 'create'])->name('create');
        Route::post('/', [BorrowingController::class, 'store'])->name('store');
        Route::get('/{borrowing}', [BorrowingController::class, 'show'])->name('show');
        Route::get('/{borrowing}/edit', [BorrowingController::class, 'edit'])->name('edit');
        Route::put('/{borrowing}', [BorrowingController::class, 'update'])->name('update');
        Route::delete('/{borrowing}', [BorrowingController::class, 'destroy'])->name('destroy');
        Route::patch('/{borrowing}/return', [BorrowingController::class, 'returnItem'])->name('return');
    });

    Route::prefix('borrowing-requests')->name('borrowing-requests.')->group(function () {
        Route::get('/', [AdminBorrowingRequestController::class, 'index'])->name('index');
        Route::get('/pending', [AdminBorrowingRequestController::class, 'pending'])->name('pending');
        Route::get('/history', [AdminBorrowingRequestController::class, 'history'])->name('history');
        Route::get('/{id}', [AdminBorrowingRequestController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [AdminBorrowingRequestController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [AdminBorrowingRequestController::class, 'reject'])->name('reject');
        Route::post('/{id}/complete', [AdminBorrowingRequestController::class, 'complete'])->name('complete');
    });

    // Procurement Requests (Permintaan Pengadaan)
    Route::prefix('procurement-requests')->name('procurement-requests.')->group(function () {
        Route::get('/', [AdminProcurementController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminProcurementController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [AdminProcurementController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [AdminProcurementController::class, 'reject'])->name('reject');
    });



    // Admin Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/api/notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');

    // Maintenance Routes — Admin hanya bisa create & melihat
    // Complete & Scrap HANYA Operator yang bisa (lihat staff routes)
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
        Route::post('/', [MaintenanceController::class, 'store'])->name('store');
        Route::get('/{maintenance}', [MaintenanceController::class, 'show'])->name('show');
    });

    // Modul Perkakas & Kalibrasi Presisi (Prodi 4P)
    Route::prefix('calibration')->name('calibration.')->group(function () {
        Route::get('/', [CalibrationController::class, 'index'])->name('index');
        Route::post('/{item}/update-cert', [CalibrationController::class, 'updateCalibration'])->name('update-cert');
        Route::post('/{item}/update-life', [CalibrationController::class, 'updateToolLife'])->name('update-life');
        Route::post('/{item}/update-type', [CalibrationController::class, 'updateToolType'])->name('update-type');
    });

    // Modul Smart Logistics & Bin Location (Prodi Logistik)
    Route::prefix('logistics')->name('logistics.')->group(function () {
        Route::get('/', [LogisticsController::class, 'index'])->name('index');
        Route::post('/{item}/convert-procurement', [LogisticsController::class, 'convertToProcurement'])->name('convert-procurement');
        Route::post('/{item}/update-param', [LogisticsController::class, 'updateParameters'])->name('update-param');
    });

    // Modul Tooling Kit SPK Manufaktur (Prodi Manufaktur)
    Route::prefix('tooling-kits')->name('tooling-kits.')->group(function () {
        Route::get('/', [ToolingKitController::class, 'index'])->name('index');
        Route::post('/', [ToolingKitController::class, 'store'])->name('store');
        Route::post('/{toolingKit}/toggle-status', [ToolingKitController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{toolingKit}', [ToolingKitController::class, 'destroy'])->name('destroy');
    });

    // Tahap 4 – Digital Signature BAP (Prodi TRPL)
    Route::get('/bap-documents', [SignatureController::class, 'index'])->name('bap.index');
    Route::prefix('borrowing-requests')->name('borrowing-requests.')->group(function () {
        Route::get('/{id}/bap', [SignatureController::class, 'showBap'])->name('bap');
        Route::post('/{id}/sign', [SignatureController::class, 'sign'])->name('sign');
    });
});

// -------------------------------------------------------
// STAFF (OPERATOR) Routes — Maintenance Only
// -------------------------------------------------------
Route::prefix('staff')->middleware('auth', RoleMiddleware::class.':operator')->name('staff.')->group(function () {

    Route::get('/dashboard', [AccessController::class, 'showDashboard'])->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/profile/update', [ProfileController::class, 'update'])->name('update');
        Route::patch('/profile/update-email', [ProfileController::class, 'updateEmail'])->name('update-email');
        Route::patch('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');
        Route::patch('/profile/update-whatsapp', [ProfileController::class, 'updateWhatsapp'])->name('update-whatsapp');
    });

    // Maintenance — Operator hanya bisa melihat & mengeksekusi (complete/scrap)
    // Create & store hanya Admin yang bisa (lihat admin routes)
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::get('/{maintenance}', [MaintenanceController::class, 'show'])->name('show');
        Route::patch('/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('complete');
        Route::patch('/{maintenance}/scrap', [MaintenanceController::class, 'scrap'])->name('scrap');
    });

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/api/notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
});

// -------------------------------------------------------
// PUBLIC: BAP QR Verification (no auth required)
// -------------------------------------------------------
Route::get('/verify/bap/{token}', [SignatureController::class, 'verify'])->name('bap.verify');

// -------------------------------------------------------
// SHARED ROUTES: Scanner and Quick Actions (Admin & Operator)
// -------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/scanner', [ScanController::class, 'index'])->name('scanner.index');
    Route::get('/scan/{kode}', [ScanController::class, 'handleScan'])->name('scanner.handle');
    Route::patch('/scan/return/{borrowing}', [ScanController::class, 'quickReturn'])->name('scanner.return');
    Route::post('/scan/maintenance/{item}', [ScanController::class, 'quickMaintenance'])->name('scanner.maintenance');
    // Shared inventory lookup (used by barcode scanner modal on any panel)
    Route::get('/api/inventory/lookup', [InventoryController::class, 'lookupByKode'])->name('inventory.lookup.shared');
});

Route::prefix('user')->middleware('auth', RoleMiddleware::class.':user')->name('user.')->group(function () {
    Route::get('/dashboard', [AccessController::class, 'ShowDashboardUser'])->name('dashboard');

    Route::prefix('borrowing')->name('borrowing.')->group(function () {
        Route::get('/', [UserBorrowingController::class, 'index'])->name('index');
        Route::get('/my-requests', [UserBorrowingController::class, 'myRequests'])->name('my-requests');
        Route::get('/history', [UserBorrowingController::class, 'history'])->name('history');
        Route::get('/create/{itemId}', [UserBorrowingController::class, 'create'])->name('create');
        Route::post('/store', [UserBorrowingController::class, 'store'])->name('store');
        Route::get('/show/{id}', [UserBorrowingController::class, 'show'])->name('show');
        Route::get('/show/{id}/print', [UserBorrowingController::class, 'printDetail'])->name('print');
        Route::get('/item/{id}', [UserBorrowingController::class, 'showItem'])->name('show-item');
        Route::delete('/{id}/cancel', [UserBorrowingController::class, 'cancel'])->name('cancel');
        
      
        Route::get('/cart', [\App\Http\Controllers\BorrowingCartController::class, 'index'])->name('cart');
        Route::post('/cart/add', [\App\Http\Controllers\BorrowingCartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/{id}', [\App\Http\Controllers\BorrowingCartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{id}', [\App\Http\Controllers\BorrowingCartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart', [\App\Http\Controllers\BorrowingCartController::class, 'clear'])->name('cart.clear');
        Route::post('/cart/checkout', [\App\Http\Controllers\BorrowingCartController::class, 'checkout'])->name('cart.checkout');
        Route::post('/kit/{toolingKit}/borrow', [ToolingKitController::class, 'borrowKit'])->name('kit.borrow');
    });

    // Procurement Requests (User side)
    Route::prefix('procurement')->name('procurement.')->group(function () {
        Route::get('/', [UserProcurementController::class, 'index'])->name('index');
        Route::get('/create', [UserProcurementController::class, 'create'])->name('create');
        Route::post('/', [UserProcurementController::class, 'store'])->name('store');
        Route::get('/{id}', [UserProcurementController::class, 'show'])->name('show');
        Route::delete('/{id}/cancel', [UserProcurementController::class, 'cancel'])->name('cancel');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserProfileController::class, 'index'])->name('index');
        Route::patch('/update-email', [\App\Http\Controllers\UserProfileController::class, 'updateEmail'])->name('update-email');
        Route::patch('/update-password', [\App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('update-password');
        Route::patch('/update-photo', [\App\Http\Controllers\UserProfileController::class, 'updatePhoto'])->name('update-photo');
        Route::patch('/update-whatsapp', [\App\Http\Controllers\UserProfileController::class, 'updateWhatsapp'])->name('update-whatsapp');
    });

    // User Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/api/notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
});