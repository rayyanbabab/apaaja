<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminBorrowingRequestController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\IncomingItemsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OutgoingItemsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TabsInventoryController;
use App\Http\Controllers\UserBorrowingController;
use App\Http\Controllers\UsersAccountController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [AccessController::class, 'showLoginForm'])->name('loginform');

Route::get('/login', [AccessController::class, 'showLoginForm']);
Route::post('/login', [AccessController::class, 'login'])->name('login');
Route::post('/logout', [AccessController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware('auth', RoleMiddleware::class.':admin')->name('admin.')->group(function () {
    Route::get('/search', [InventoryController::class, 'search'])->name('search');

    Route::get('/dashboard', [AccessController::class, 'ShowDashboard'])->name('dashboard');
    Route::get('/dashboard-manage', [AccessController::class, 'ShowDashboardManage'])->name('dashboard.manage');
    Route::get('/dashboard-operation', [AccessController::class, 'ShowDashboardOperation'])->name('dashboard.operation');
    Route::get('/dashboard-statistic', [AccessController::class, 'ShowDashboardStatistic'])->name('dashboard.statistic');
    Route::get('/dashboard-user', [AccessController::class, 'ShowDashboardUser'])->name('dashboard.user');

    Route::prefix('content')->name('content.')->group(function () {
        Route::post('/store-users', [UsersAccountController::class, 'store'])->name('savedatausers');
        Route::get('/list-users', [UsersAccountController::class, 'list'])->name('listusers');

        Route::get('/create-users', [UsersAccountController::class, 'create'])->name('createusers');
        Route::get('/show-users/{id}', [UsersAccountController::class, 'show'])->name('showusers');

        Route::post('/update-users/{id}', [UsersAccountController::class, 'update'])->name('updateusers');
        Route::get('/update-users/{id}', [UsersAccountController::class, 'update'])->name('updateusers');

        Route::post('/edit-users/{id}', [UsersAccountController::class, 'edit'])->name('editusers');
        Route::get('/edit-users/{id}', [UsersAccountController::class, 'edit'])->name('editusers');

        Route::delete('/delete-users/{id}', [UsersAccountController::class, 'destroy'])->name('deleteusers');
        Route::post('/bulk-delete-users', [UsersAccountController::class, 'bulkDelete'])->name('bulkdeleteusers');
        Route::post('/toggle-status-users/{id}', [UsersAccountController::class, 'toggleStatus'])->name('togglestatususers');
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
    });


    // Reports Routes
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/inventory', [ReportsController::class, 'inventoryReport'])->name('reports.inventory');
    Route::get('/reports/outgoing', [ReportsController::class, 'outgoingReport'])->name('reports.outgoing');
    Route::get('/reports/incoming', [ReportsController::class, 'incomingReport'])->name('reports.incoming');
    Route::get('/reports/suppliers', [ReportsController::class, 'suppliersReport'])->name('reports.suppliers');

    // Activities Routes
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/export', [ActivityController::class, 'export'])->name('activities.export');

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
    // Outgoing Items Routes
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
        Route::get('/{id}', [AdminBorrowingRequestController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [AdminBorrowingRequestController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [AdminBorrowingRequestController::class, 'reject'])->name('reject');
        Route::post('/{id}/complete', [AdminBorrowingRequestController::class, 'complete'])->name('complete');
    });
});

Route::prefix('user')->middleware('auth', RoleMiddleware::class.':user')->name('user.')->group(function () {
    Route::get('/dashboard', [AccessController::class, 'ShowDashboardUser'])->name('dashboard');

    Route::prefix('borrowing')->name('borrowing.')->group(function () {
        Route::get('/', [UserBorrowingController::class, 'index'])->name('index');
        Route::get('/my-requests', [UserBorrowingController::class, 'myRequests'])->name('my-requests');
        Route::get('/create/{itemId}', [UserBorrowingController::class, 'create'])->name('create');
        Route::post('/store', [UserBorrowingController::class, 'store'])->name('store');
        Route::get('/show/{id}', [UserBorrowingController::class, 'show'])->name('show');
        Route::get('/item/{id}', [UserBorrowingController::class, 'showItem'])->name('show-item');
        
        // Cart routes
        Route::get('/cart', [\App\Http\Controllers\BorrowingCartController::class, 'index'])->name('cart');
        Route::post('/cart/add', [\App\Http\Controllers\BorrowingCartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/{id}', [\App\Http\Controllers\BorrowingCartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{id}', [\App\Http\Controllers\BorrowingCartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart', [\App\Http\Controllers\BorrowingCartController::class, 'clear'])->name('cart.clear');
        Route::post('/cart/checkout', [\App\Http\Controllers\BorrowingCartController::class, 'checkout'])->name('cart.checkout');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserProfileController::class, 'index'])->name('index');
        Route::patch('/update-email', [\App\Http\Controllers\UserProfileController::class, 'updateEmail'])->name('update-email');
        Route::patch('/update-password', [\App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('update-password');
        Route::patch('/update-photo', [\App\Http\Controllers\UserProfileController::class, 'updatePhoto'])->name('update-photo');
    });
});
