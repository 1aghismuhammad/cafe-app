<?php

use App\Http\Controllers\Admin\CafeProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OutletController;
use App\Http\Controllers\Admin\RestaurantTableController;
use App\Http\Controllers\Admin\TableQrCodeController;
use App\Http\Controllers\Customer\OrderTableController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Cashier\OrderController as CashierOrderController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Public Customer Routes
|--------------------------------------------------------------------------
| Route ini tidak boleh masuk middleware auth.
| Pelanggan yang scan QR harus bisa membuka halaman order tanpa login.
|--------------------------------------------------------------------------
*/

Route::get('/order/table/{token}', [OrderTableController::class, 'show'])
    ->name('customer.order.table');
    
Route::post('/order/table/{token}/cart/add/{menu}', [CartController::class, 'add'])
    ->name('customer.cart.add');

Route::get('/order/table/{token}/cart', [CartController::class, 'show'])
    ->name('customer.cart.show');

Route::patch('/order/table/{token}/cart/{menu}/increase', [CartController::class, 'increase'])
    ->name('customer.cart.increase');

Route::patch('/order/table/{token}/cart/{menu}/decrease', [CartController::class, 'decrease'])
    ->name('customer.cart.decrease');

Route::delete('/order/table/{token}/cart/{menu}/remove', [CartController::class, 'remove'])
    ->name('customer.cart.remove');

Route::patch('/order/table/{token}/cart/{menu}/note', [CartController::class, 'updateNote'])
    ->name('customer.cart.note');

Route::get('/order/table/{token}/checkout', [CheckoutController::class, 'show'])
    ->name('customer.checkout.show');

Route::post('/order/table/{token}/checkout', [CheckoutController::class, 'store'])
    ->name('customer.checkout.store');

Route::get('/order/table/{token}/success/{order}', [CheckoutController::class, 'success'])
    ->name('customer.checkout.success');
/*
|--------------------------------------------------------------------------
| Authenticated Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Role Based Dashboards
|--------------------------------------------------------------------------
*/

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
});


/*
|--------------------------------------------------------------------------
| Kasir Routes
|--------------------------------------------------------------------------
| Route operasional kasir dibuat terpisah dari admin.
|--------------------------------------------------------------------------
*/

Route::prefix('kasir')
    ->name('kasir.')
    ->middleware(['auth', 'verified', 'role:kasir'])
    ->group(function () {
        Route::get('/orders', [CashierOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [CashierOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{order}/status', [CashierOrderController::class, 'updateStatus'])
            ->name('orders.update-status');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Semua route master data admin dikumpulkan di sini.
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/cafe-profile', [CafeProfileController::class, 'edit'])
            ->name('cafe-profile.edit');

        Route::put('/cafe-profile', [CafeProfileController::class, 'update'])
            ->name('cafe-profile.update');

        Route::get('/outlets', [OutletController::class, 'index'])
            ->name('outlets.index');

        Route::get('/outlets/create', [OutletController::class, 'create'])
            ->name('outlets.create');

        Route::post('/outlets', [OutletController::class, 'store'])
            ->name('outlets.store');

        Route::get('/outlets/{outlet}/edit', [OutletController::class, 'edit'])
            ->name('outlets.edit');

        Route::put('/outlets/{outlet}', [OutletController::class, 'update'])
            ->name('outlets.update');

        Route::delete('/outlets/{outlet}', [OutletController::class, 'destroy'])
            ->name('outlets.destroy');

        Route::get('/restaurant-tables', [RestaurantTableController::class, 'index'])
            ->name('restaurant-tables.index');

        Route::get('/restaurant-tables/create', [RestaurantTableController::class, 'create'])
            ->name('restaurant-tables.create');

        Route::post('/restaurant-tables', [RestaurantTableController::class, 'store'])
            ->name('restaurant-tables.store');

        Route::get('/restaurant-tables/{restaurantTable}/edit', [RestaurantTableController::class, 'edit'])
            ->name('restaurant-tables.edit');

        Route::put('/restaurant-tables/{restaurantTable}', [RestaurantTableController::class, 'update'])
            ->name('restaurant-tables.update');

        Route::delete('/restaurant-tables/{restaurantTable}', [RestaurantTableController::class, 'destroy'])
            ->name('restaurant-tables.destroy');

        Route::get('/table-qr-codes', [TableQrCodeController::class, 'index'])
            ->name('table-qr-codes.index');

        Route::post('/table-qr-codes/generate/{restaurantTable}', [TableQrCodeController::class, 'generate'])
            ->name('table-qr-codes.generate');

        Route::get('/table-qr-codes/{tableQrCode}', [TableQrCodeController::class, 'show'])
            ->name('table-qr-codes.show');

        Route::post('/table-qr-codes/{tableQrCode}/regenerate', [TableQrCodeController::class, 'regenerate'])
            ->name('table-qr-codes.regenerate');

        Route::patch('/table-qr-codes/{tableQrCode}/toggle', [TableQrCodeController::class, 'toggle'])
            ->name('table-qr-codes.toggle');

        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::get('/categories/create', [CategoryController::class, 'create'])
            ->name('categories.create');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->name('categories.edit');

        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');

        Route::get('/menus', [MenuController::class, 'index'])
            ->name('menus.index');

        Route::get('/menus/create', [MenuController::class, 'create'])
            ->name('menus.create');

        Route::post('/menus', [MenuController::class, 'store'])
            ->name('menus.store');

        Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])
            ->name('menus.edit');

        Route::put('/menus/{menu}', [MenuController::class, 'update'])
            ->name('menus.update');

        Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])
            ->name('menus.destroy');
    });

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';