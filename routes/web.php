<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CafeProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OutletController;
use App\Http\Controllers\Admin\RestaurantTableController;
use App\Http\Controllers\Admin\TableQrCodeController;
use App\Http\Controllers\Customer\OrderTableController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/kasir/dashboard', [DashboardController::class, 'kasir'])
        ->middleware('role:kasir')
        ->name('kasir.dashboard');

    Route::get('/owner/dashboard', [DashboardController::class, 'owner'])
        ->middleware('role:owner')
        ->name('owner.dashboard');

    Route::get('/admin/cafe-profile', [CafeProfileController::class, 'edit'])
        ->middleware('role:admin')
        ->name('admin.cafe-profile.edit');

    Route::put('/admin/cafe-profile', [CafeProfileController::class, 'update'])
        ->middleware('role:admin')
        ->name('admin.cafe-profile.update');

    Route::get('/admin/outlets', [OutletController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.outlets.index');

    Route::get('/admin/outlets/create', [OutletController::class, 'create'])
        ->middleware('role:admin')
        ->name('admin.outlets.create');

    Route::post('/admin/outlets', [OutletController::class, 'store'])
        ->middleware('role:admin')
        ->name('admin.outlets.store');

    Route::get('/admin/outlets/{outlet}/edit', [OutletController::class, 'edit'])
        ->middleware('role:admin')
        ->name('admin.outlets.edit');

    Route::put('/admin/outlets/{outlet}', [OutletController::class, 'update'])
        ->middleware('role:admin')
        ->name('admin.outlets.update');

    Route::delete('/admin/outlets/{outlet}', [OutletController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('admin.outlets.destroy');

        Route::get('/admin/restaurant-tables', [RestaurantTableController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.restaurant-tables.index');

    Route::get('/admin/restaurant-tables/create', [RestaurantTableController::class, 'create'])
        ->middleware('role:admin')
        ->name('admin.restaurant-tables.create');

    Route::post('/admin/restaurant-tables', [RestaurantTableController::class, 'store'])
        ->middleware('role:admin')
        ->name('admin.restaurant-tables.store');

    Route::get('/admin/restaurant-tables/{restaurantTable}/edit', [RestaurantTableController::class, 'edit'])
        ->middleware('role:admin')
        ->name('admin.restaurant-tables.edit');

    Route::put('/admin/restaurant-tables/{restaurantTable}', [RestaurantTableController::class, 'update'])
        ->middleware('role:admin')
        ->name('admin.restaurant-tables.update');

    Route::delete('/admin/restaurant-tables/{restaurantTable}', [RestaurantTableController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('admin.restaurant-tables.destroy');

        Route::get('/admin/table-qr-codes', [TableQrCodeController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.table-qr-codes.index');

    Route::post('/admin/table-qr-codes/generate/{restaurantTable}', [TableQrCodeController::class, 'generate'])
        ->middleware('role:admin')
        ->name('admin.table-qr-codes.generate');

    Route::get('/admin/table-qr-codes/{tableQrCode}', [TableQrCodeController::class, 'show'])
        ->middleware('role:admin')
        ->name('admin.table-qr-codes.show');

    Route::post('/admin/table-qr-codes/{tableQrCode}/regenerate', [TableQrCodeController::class, 'regenerate'])
        ->middleware('role:admin')
        ->name('admin.table-qr-codes.regenerate');

    Route::patch('/admin/table-qr-codes/{tableQrCode}/toggle', [TableQrCodeController::class, 'toggle'])
        ->middleware('role:admin')
        ->name('admin.table-qr-codes.toggle');

        Route::get('/admin/categories', [CategoryController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.categories.index');

    Route::get('/admin/categories/create', [CategoryController::class, 'create'])
        ->middleware('role:admin')
        ->name('admin.categories.create');

    Route::post('/admin/categories', [CategoryController::class, 'store'])
        ->middleware('role:admin')
        ->name('admin.categories.store');

    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])
        ->middleware('role:admin')
        ->name('admin.categories.edit');

    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])
        ->middleware('role:admin')
        ->name('admin.categories.update');

    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('admin.categories.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/order/table/{token}', [OrderTableController::class, 'show'])
        ->name('customer.order.table');
});

require __DIR__.'/auth.php';